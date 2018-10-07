<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PublishedScope implements Scope
{
    /** @var string */
    private $parameterName;

    /** @var string */
    private $parameterValue;

    public function __construct(string $parameterName = 'status', string $parameterValue = 'published')
    {
        $this->parameterName = $parameterName;
        $this->parameterValue = $parameterValue;
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->where($this->parameterName, $this->parameterValue);
    }
}
