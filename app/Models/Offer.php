<?php

namespace App\Models;

use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Offer.
 *
 * @property int                                   $id
 * @property int                                   $external_id
 * @property int|null                              $session_id
 * @property int                                   $product_id
 * @property string                                $status
 * @property float                                 $price
 * @property int                                   $amount
 * @property int                                   $sales
 * @property string                                $article
 * @property \Illuminate\Support\Carbon|null       $created_at
 * @property \Illuminate\Support\Carbon|null       $updated_at
 * @property \App\Models\CatalogUpdateSession|null $catalogUpdateSession
 * @property \App\Models\Product                   $product
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Offer whereStatus($value)
 */
class Offer extends Model
{
    use HasCatalogUpdateSession;

    const STATUS_PUBLISHED = 'published';
    const STATUS_UNPUBLISHED = 'unpublished';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PublishedScope('offers.status', static::STATUS_PUBLISHED));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
