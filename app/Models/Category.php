<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category.
 *
 * @property int                                                             $id
 * @property int                                                             $external_id
 * @property int|null                                                        $parent_id
 * @property string                                                          $alias
 * @property string                                                          $title
 * @property \App\Models\Category|null                                       $parent
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Product[]  $products
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $subcategories
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereTitle($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return 'alias';
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_categories');
    }
}
