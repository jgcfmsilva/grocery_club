<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.settings.index');
    }
}
