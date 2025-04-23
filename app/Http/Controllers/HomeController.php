<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('deleted_at')->get();

        return view('home', compact('categories'));
    }
}
