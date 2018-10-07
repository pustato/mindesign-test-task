@extends('layouts.main')

@section('body')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1>Топ 20 товаров</h1>
        </div>
    </div>

    <div class="row">
        @each('partials._products', $products, 'product');
    </div>
</div>
@endsection