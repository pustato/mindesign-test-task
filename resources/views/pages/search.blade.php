@extends('layouts.main')

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h1>Результаты поиска для запроса "{{ $search }}"</h1>
            </div>
        </div>

        <div class="row">
            @each('partials._products', $products, 'product')
        </div>
    </div>
@endsection