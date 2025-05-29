<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::with('category');

        // Apply filters
        if (request()->filled('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        if (request()->filled('category')) {
            $query->where('category_id', request('category'));
        }

        if (request()->filled('price_min')) {
            $query->where('price', '>=', request('price_min'));
        }

        if (request()->filled('price_max')) {
            $query->where('price', '<=', request('price_max'));
        }

        // Apply sorting
        if (request()->has('sort') && request()->has('direction')) {
            $sortableColumns = ['name', 'category', 'price', 'stock'];
            $sort = request('sort');
            $direction = request('direction') === 'desc' ? 'desc' : 'asc';

            if (in_array($sort, $sortableColumns)) {
                if ($sort === 'category') {
                    $query->join('categories', 'products.category_id', '=', 'categories.id')
                          ->orderBy('categories.name', $direction);
                } else {
                    $query->orderBy($sort, $direction);
                }
            }
        }

        $products = $query->paginate(10);
        $categories = Category::all();

        return view('pages.dashboard.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('pages.dashboard.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('photo')) {
                $fileName = $request->file('photo')->store('products', 'public');
                $data['photo'] = basename($fileName);
            }

            Product::create($data);

            flash()->success('Product added successfully.');
            return redirect()->route('dashboard.products.index');
        } catch (\Exception $e) {
            flash()->error('An error occurred while adding the product.');
            return back()->withInput();
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('pages.dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('photo')) {
                if ($product->photo && Storage::disk('public')->exists('products/' . $product->photo)) {
                    Storage::disk('public')->delete('products/' . $product->photo);
                }
                $fileName = $request->file('photo')->store('products', 'public');
                $data['photo'] = basename($fileName);
            }

            $product->update($data);

            flash()->success('Product updated successfully.');
            return redirect()->route('dashboard.products.index');
        } catch (\Exception $e) {
            flash()->error('An error occurred while updating the product.');
            return back()->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->photo && Storage::disk('public')->exists('products/' . $product->photo)) {
                Storage::disk('public')->delete('products/' . $product->photo);
            }

            $product->delete();

            flash()->success('Product deleted successfully.');
            return redirect()->route('dashboard.products.index');
        } catch (\Exception $e) {
            flash()->error('An error occurred while deleting the product.');
            return redirect()->route('dashboard.products.index');
        }
    }

    public function show(Product $product)
    {
        $adjustments = $product->stockAdjustments()->with('registeredBy')->orderByDesc('created_at')->paginate(10);

        return view('pages.dashboard.products.show', compact('product', 'adjustments'));
    }
}
