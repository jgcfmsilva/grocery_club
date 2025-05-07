<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.users.index');
    }
}
