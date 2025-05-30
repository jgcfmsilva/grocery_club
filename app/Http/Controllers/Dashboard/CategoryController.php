<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CategoryStoreRequest;
use App\Http\Requests\Product\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $categories = $query->paginate(10)->appends($request->all());

        return view('pages.dashboard.categories.index', compact('categories', 'sort', 'direction'));
    }

    public function create()
    {
        return view('pages.dashboard.categories.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                $fileName = $request->file('image')->store('categories', 'public');
                $data['image'] = basename($fileName);
            }

            Category::create($data);

            return redirect()->route('dashboard.categories.index')->with('success', 'Category added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.categories.create')->with('error', 'Failed to add category: ' . $e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        return view('pages.dashboard.categories.edit', compact('category'));
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                if ($category->image && Storage::disk('public')->exists('categories/' . $category->image)) {
                    Storage::disk('public')->delete('categories/' . $category->image);
                }
                $fileName = $request->file('image')->store('categories', 'public');
                $data['image'] = basename($fileName);
            }

            $category->update($data);

            return redirect()->route('dashboard.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.categories.edit', $category->id)->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->image && Storage::disk('public')->exists('categories/' . $category->image)) {
                Storage::disk('public')->delete('categories/' . $category->image);
            }

            $category->delete();

            return redirect()->route('dashboard.categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.categories.index')->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
