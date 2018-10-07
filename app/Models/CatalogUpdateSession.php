<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CatalogUpdateSession.
 *
 * @property int                             $id
 * @property string                          $status
 * @property string                          $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatalogUpdateSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatalogUpdateSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatalogUpdateSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatalogUpdateSession whereUpdatedAt($value)
 * @mixin \Eloquent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatalogUpdateSession whereMessage($value)
 */
class CatalogUpdateSession extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
