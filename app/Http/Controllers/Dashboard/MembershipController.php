<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('type', UserType::Member);

        // Pesquisa por nome
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filtros adicionais (exemplo: email confirmado)
        if ($request->filled('email_verified')) {
            if ($request->input('email_verified') === 'yes') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->input('email_verified') === 'no') {
                $query->whereNull('email_verified_at');
            }
        }

        // Ordenação dinâmica
        $sortable = [
            'name' => 'name',
            'email' => 'email',
            'created_at' => 'created_at',
        ];
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        if (array_key_exists($sort, $sortable)) {
            $query->orderBy($sortable[$sort], $direction);
        } else {
            $query->orderBy('name', 'asc');
        }

        $members = $query->paginate(20)->appends($request->all());

        return view('pages.dashboard.memberships.index', compact('members', 'sort', 'direction'));
    }
}
