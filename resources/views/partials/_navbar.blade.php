<header class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Каталог</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            @foreach($categories as $category)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="cat-{{ $category->alias }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ $category->title }}
                    </a>

                    <div class="dropdown-menu" aria-labelledby="cat-{{ $category->alias }}">
                        @foreach($category->subcategories as $subcategory)
                            <a class="dropdown-item" href="{{ route('category', $subcategory) }}">{{ $subcategory->title }}</a>
                        @endforeach

                        @if($category->subcategories->count())
                            <div class="dropdown-divider"></div>
                        @endif

                        <a class="dropdown-item" href="{{ route('category', $category) }}">Все товары категории</a>
                    </div>
                </li>
            @endforeach
        </ul>

        <form class="form-inline my-2 my-lg-0" method="GET" action="{{ route('search') }}">
            <input class="form-control mr-sm-2" name="q" type="search" placeholder="Поиск" aria-label="Поиск">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Найти</button>
        </form>
    </div>
</header>