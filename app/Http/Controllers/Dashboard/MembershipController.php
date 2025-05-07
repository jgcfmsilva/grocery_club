<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class MembershipController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.memberships.index');
    }
}
