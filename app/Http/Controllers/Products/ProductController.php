<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
    * Show view of all products
    */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filtro por faixa de preço
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Ordenação flexível
        $sort = $request->input('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->whereNull('deleted_at')->paginate(12);

        $categories = Category::all();

        return view('pages.products.index', [
            'products' => $products,
            'categories' => $categories,
            'sort' => $sort,
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
        ]);
    }

    /**
    * Show view of a specific products
    */
    public function show(Product $product)
    {
        return view('pages.products.product-page', compact('product'));
    }

    /**
    * Show view of all products of a specific category
    */
    public function categoryProducts($category_id)
    {
        $category = Category::findOrFail($category_id);

        $products = Product::where('category_id', $category_id)->paginate(12);

        return view('pages.products.category_products', compact('products', 'category'));
    }

}
