<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = authUser();

        if (!$user->isBoard()) {
            abort(403, 'Unauthorized access!');
        }

        return view('pages.dashboard.index');
    }
}
