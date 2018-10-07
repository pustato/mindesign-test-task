<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{
    public function index()
    {
        $products = Product::popular(20)->get();

        return view('pages.index', [
            'products' => $products,
        ]);
    }

    public function search(Request $request)
    {
        if (!$request->filled('q')) {
            return redirect()->route('index');
        }

        $searchStr = $request->get('q');
        $products = Product::search($searchStr)->paginate(24);

        return view('pages.search', [
            'search' => $searchStr,
            'products' => $products,
        ]);
    }

    public function category(Category $category)
    {
        $products = Product::byCategory($category)->paginate(24);

        return view('pages.category', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function e404()
    {
        return response('Здесь ничего нет', Response::HTTP_NOT_FOUND);
    }
}
