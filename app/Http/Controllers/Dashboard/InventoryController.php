<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.inventory.index');
    }
}
