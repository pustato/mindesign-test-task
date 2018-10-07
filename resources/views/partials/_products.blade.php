<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 product">
    <div class="border border-primary">
        <div class="text-center image-container">
            <img src="{{ $product->image }}" alt="{{ $product->title }}">
        </div>

        <div class="spacer"></div>

        <div class="container-fluid">
            <div class="row product-title">
                <div class="col text-center">
                    <a href="{{ $product->url }}">{{ $product->title }}</a>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    {{ $product->price }} руб.
                </div>
            </div>
        </div>
    </div>
</div>