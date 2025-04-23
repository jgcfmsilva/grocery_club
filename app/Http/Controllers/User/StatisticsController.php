<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{

    public function index()
    {
        return view('pages.my-account.statistics.index');
    }

    public function funcao(Request $request)
    {
        // Vai ser implementado mais tarde
    }
}
