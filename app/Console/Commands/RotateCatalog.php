<?php

namespace App\Console\Commands;

use App\Http\Transports\TransportContract;
use App\Services\CatalogUpdateService;
use Illuminate\Console\Command;

class RotateCatalog extends Command
{
    protected $signature = 'catalog:rotate';

    protected $description = 'Rotate products in catalog';

    /** @var TransportContract */
    private $transport;

    /** @var CatalogUpdateService */
    private $catalogUpdateService;

    public function __construct(TransportContract $transport, CatalogUpdateService $catalogUpdateService)
    {
        parent::__construct();

        $this->transport = $transport;
        $this->catalogUpdateService = $catalogUpdateService;
    }

    public function handle()
    {
        $this->info('Opening session');
        $session = $this->catalogUpdateService->openSession();

        $this->info('Requesting API');
        $products = $this->getProductsFromApi();
        if (0 === count($products)) {
            $this->info('Empty product list from API');
            $this->catalogUpdateService->closeSessionWithError($session, 'Product list is empty');

            return 1;
        }

        foreach ($products as $productDesc) {
            $this->info("Saving product: {$productDesc['id']} {$productDesc['title']}");

            try {
                $product = $this->catalogUpdateService->updateProduct($productDesc, $session);
                $this->info("Product saved. Id: {$product->id}");
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                \Log::error($e);

                continue;
            }
        }

        $this->info('Rotating catalog');
        $this->catalogUpdateService->rotateProductsInCatalog($session);
        $this->catalogUpdateService->rotateProductOffersInCatalog($session);
        $this->catalogUpdateService->closeSessionWithSuccess($session);

        return 0;
    }

    private function getProductsFromApi()
    {
        $url = config('services.markethot.products_url');

        try {
            $rsp = $this->transport->get($url);

            $decodedBody = \GuzzleHttp\json_decode($rsp, true);

            return $decodedBody['products'] ?? [];
        } catch (\Exception $e) {
            \Log::error($e);
        }

        return [];
    }
}
