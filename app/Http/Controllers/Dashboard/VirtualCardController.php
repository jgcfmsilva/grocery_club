<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class VirtualCardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.virtual-cards.index');
    }
}
