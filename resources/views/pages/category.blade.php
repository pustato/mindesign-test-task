@extends('layouts.main')

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h1>Все товары категории <i>{{ $category->title }}</i></h1>
            </div>
        </div>

        <div class="row">
            @each('partials._products', $products, 'product')
        </div>

        <div class="row">
            {!! $products->links() !!}
        </div>
    </div>
@endsection