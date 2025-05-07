<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.orders.index');
    }
}
