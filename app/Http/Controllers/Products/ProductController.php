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

        $products = $query->whereNull('deleted_at')->paginate(12);

        $categories = Category::all();

        return view('pages.products.index', compact('products', 'categories'));
    }

    /**
    * Show view of a specific products
    */
    public function show(Product $product)
    {
        return view('pages.products.show', compact('product'));
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
