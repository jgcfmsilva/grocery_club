<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
    * Show view of all products
    */
    public function index(Request $request)
    {
        $hasFilters = $request->filled(['category_id', 'search', 'min_price', 'max_price', 'sort']);
        $sort = $request->input('sort', 'name_asc');
        
        $filters = ['category_id', 'search', 'min_price', 'max_price', 'sort'];
        $hasFilters = false;
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $hasFilters = true;
                break;
            }
        }

        if (!$hasFilters) {
            $page = $request->input('page', 1);
            $cacheKey = Product::CACHE_KEY_PAGE_PREFIX . $page;
            
            $products = \Cache::store('redis')->tags(['products'])->remember($cacheKey, 3600, function () {
                return Product::with('category')
                    ->whereNull('deleted_at')
                    ->orderBy('name', 'asc')
                    ->paginate(12);
            });
        } else {
            $query = Product::with('category')->whereNull('deleted_at');

            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('name', 'like', "%{$search}%");
            }
            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->input('min_price'));
            }
            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->input('max_price'));
            }

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

            $products = $query->paginate(12)->withQueryString();
        }

        $categories = Category::allCategories();

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
        $category = Category::allCategories()->where('id', $category_id)->first();

        $page = request()->input('page', 1);
        $cacheKey = Product::CACHE_KEY_CATEGORY_PAGE . ":{$category_id}:page:{$page}";

        $products = Cache::store('redis')->tags(['products'])->remember($cacheKey, 3600, function () use ($category_id, $page) {
            return Product::where('category_id', $category_id)
                        ->whereNull('deleted_at')
                        ->paginate(12, ['*'], 'page', $page);
        });

        return view('pages.products.category_products', compact('products', 'category'));
    }

}
