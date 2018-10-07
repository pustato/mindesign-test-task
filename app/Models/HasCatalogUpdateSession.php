<?php

namespace App\Models;

/**
 * @mixin \Eloquent
 */
trait HasCatalogUpdateSession
{
    public function catalogUpdateSession()
    {
        return $this->belongsTo(CatalogUpdateSession::class, 'session_id');
    }
}
