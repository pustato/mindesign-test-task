<?php

namespace App\Services;

use App\Models\CatalogUpdateSession;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Scopes\PublishedScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CatalogUpdateService
{
    /** @var Collection */
    private $categoriesCache;

    public function __construct()
    {
        $this->categoriesCache = Category::query()
            ->get()
            ->keyBy('external_id')
        ;
    }

    public function openSession(): CatalogUpdateSession
    {
        return CatalogUpdateSession::create([
            'status' => CatalogUpdateSession::STATUS_NEW,
        ]);
    }

    public function closeSessionWithSuccess(CatalogUpdateSession $session, string $message = 'Success')
    {
        $this->closeSessionWithStatus($session, CatalogUpdateSession::STATUS_SUCCESS, $message);
    }

    public function closeSessionWithError(CatalogUpdateSession $session, string $message = 'Unknown error')
    {
        $this->closeSessionWithStatus($session, CatalogUpdateSession::STATUS_ERROR, $message);
    }

    public function updateProduct(array $productDesc, CatalogUpdateSession $session): Product
    {
        $externalId = $productDesc['id'];

        $categories = collect($productDesc['categories'] ?? [])
            ->sortBy('parent_id')
            ->map(function (array $categoryDesc) {
                return $this->storeCategory($categoryDesc);
            })
        ;

        try {
            \DB::beginTransaction();

            $product = Product::query()
                ->withoutGlobalScope(PublishedScope::class)
                ->where('external_id', $externalId)
                ->first()
            ;
            if (!$product) {
                $product = new Product();
                $product->external_id = (int) $externalId;
            }

            $product->session_id = $session->id;
            $product->status = Product::STATUS_PUBLISHED;
            $product->title = (string) $productDesc['title'];
            $product->description = (string) $productDesc['description'];
            $product->image = (string) $productDesc['image'];
            $product->first_invoice = $productDesc['first_invoice']
                ? Carbon::createFromFormat('Y-m-d H:i:s', $productDesc['first_invoice'])
                : null;
            $product->url = (string) $productDesc['url'];
            $product->price = (float) $productDesc['price'];
            $product->amount = (int) $productDesc['amount'];
            $product->save();

            //
            // Sync categories
            $product->categories()->sync($categories->pluck('id'), true);

            //
            // Store offers
            collect($productDesc['offers'] ?? [])
                ->each(function (array $offerDesc) use ($product, $session) {
                    $this->updateOffer($offerDesc, $product, $session);
                })
            ;

            \DB::commit();

            return $product;
        } catch (\Exception $e) {
            \DB::rollBack();

            throw $e;
        }
    }

    public function rotateProductsInCatalog(CatalogUpdateSession $session)
    {
        Product::query()
            ->where('session_id', '<>', $session->id)
            ->update([
                'status' => Product::STATUS_UNPUBLISHED,
            ])
        ;
    }

    public function rotateProductOffersInCatalog(CatalogUpdateSession $session)
    {
        Offer::query()
            ->where('session_id', '<>', $session->id)
            ->update([
                'status' => Product::STATUS_UNPUBLISHED,
            ])
        ;
    }

    private function storeCategory(array $categoryDesc): Category
    {
        $externalId = $categoryDesc['id'];
        if ($this->categoriesCache->has($externalId)) {
            return $this->categoriesCache->get($externalId);
        }

        $category = Category::query()->where('external_id', $externalId)->first();
        if (!$category) {
            $parentId = null;
            if (null !== $categoryDesc['parent']) {
                /** @var Category $parent */
                $parent = $this->categoriesCache->get($categoryDesc['parent']);

                if (!$parent) {
                    throw new \RuntimeException("Not found category with external_id {$categoryDesc['parent']}");
                }

                $parentId = (int) $parent->id;
            }

            $category = Category::create([
                'external_id' => (int) $externalId,
                'parent_id' => $parentId,
                'alias' => (string) $categoryDesc['alias'],
                'title' => (string) $categoryDesc['title'],
            ]);

            $this->categoriesCache->put($externalId, $category);
        }

        return $category;
    }

    private function updateOffer(array $offerDesc, Product $product, CatalogUpdateSession $session): Offer
    {
        $externalId = $offerDesc['id'];

        $offer = Offer::query()
            ->withoutGlobalScope(PublishedScope::class)
            ->where('external_id', $externalId)
            ->first()
        ;
        if (!$offer) {
            $offer = new Offer();
            $offer->external_id = (int) $externalId;
            $offer->product_id = (int) $product->id;
        }

        $offer->session_id = $session->id;
        $offer->status = Offer::STATUS_PUBLISHED;
        $offer->price = (float) $offerDesc['price'];
        $offer->amount = (float) $offerDesc['amount'];
        $offer->sales = (int) $offerDesc['sales'];
        $offer->article = (string) $offerDesc['article'];
        $offer->save();

        return $offer;
    }

    private function closeSessionWithStatus(CatalogUpdateSession $session, string $status, string $message)
    {
        $session->status = $status;
        $session->message = $message;
        $session->save();
    }
}
