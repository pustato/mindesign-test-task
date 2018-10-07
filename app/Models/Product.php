<?php

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product.
 *
 * @property int                                                             $id
 * @property int                                                             $external_id
 * @property int|null                                                        $session_id
 * @property string                                                          $status
 * @property string                                                          $title
 * @property string                                                          $description
 * @property string                                                          $image
 * @property \Illuminate\Support\Carbon|null                                 $first_invoice
 * @property string                                                          $url
 * @property float                                                           $price
 * @property int                                                             $amount
 * @property \Illuminate\Support\Carbon|null                                 $created_at
 * @property \Illuminate\Support\Carbon|null                                 $updated_at
 * @property \App\Models\CatalogUpdateSession|null                           $catalogUpdateSession
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property \App\Models\Offer                                               $offers
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product byCategory(\App\Models\Category $category)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product popular($count)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product search($input)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereFirstInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUrl($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasCatalogUpdateSession;

    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'first_invoice' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PublishedScope('products.status', static::STATUS_PUBLISHED));
    }

    public function offers()
    {
        return $this->belongsTo(Offer::class);
    }

    public function catalogUpdateSession()
    {
        return $this->belongsTo(CatalogUpdateSession::class, 'session_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_categories');
    }

    public function scopeSearch(Builder $query, string $input): Builder
    {
        $input = sprintf('%%%s%%', trim($input, " \t\n\r \v%"));

        return $query->where(function (Builder $query) use ($input) {
            return $query
                ->where('title', 'like', $input)
                ->orWhere('description', 'like', $input)
            ;
        });
    }

    public function scopePopular(Builder $query, int $count): Builder
    {
        return $query->select('products.*')
            ->join('offers', 'offers.product_id', '=', 'products.id')
            ->orderBy('offers.amount', 'desc')
            ->limit($count)
        ;
    }

    public function scopeByCategory(Builder $query, Category $category): Builder
    {
        return $query->select('products.*')
            ->join('products_categories', 'products_categories.product_id', '=', 'products.id')
            ->where('products_categories.category_id', $category->id)
        ;
    }
}
