<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter by email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter by type/role
        if ($request->filled('type')) {
            if ($request->type === 'pending_member') {
                $query->where('type', UserType::PendingMember);
            } elseif ($request->type === 'member') {
                $query->where('type', UserType::Member);
            } elseif ($request->type === 'employee') {
                $query->where('type', UserType::Employee);
            } elseif ($request->type === 'board') {
                $query->where('type', UserType::Board);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at')->where('blocked', false);
            } elseif ($request->status === 'blocked') {
                $query->where('blocked', true)->whereNull('deleted_at');
            } elseif ($request->status === 'deleted') {
                $query->onlyTrashed();
            }
        }

        // Sorting
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
        $sortable = ['id', 'name', 'email', 'type', 'status'];

        if (in_array($sort, $sortable)) {
            if ($sort === 'status') {
                if ($direction === 'desc') {
                    $query->orderByRaw("
                        CASE 
                            WHEN deleted_at IS NOT NULL THEN 1 
                            WHEN blocked = true AND deleted_at IS NULL THEN 2 
                            ELSE 3 
                        END ASC
                    ");
                } else {
                    $query->orderByRaw("
                        CASE 
                            WHEN deleted_at IS NOT NULL THEN 1 
                            WHEN blocked = true AND deleted_at IS NULL THEN 2 
                            ELSE 3 
                        END DESC
                    ");
                }
            } elseif ($sort === 'type') {
                // Ordenação personalizada por nome do tipo
                $query->orderByRaw("
                    CASE type
                        WHEN ? THEN 'Board'
                        WHEN ? THEN 'Employee'
                        WHEN ? THEN 'Member'
                        WHEN ? THEN 'Pending Member'
                        ELSE ''
                    END $direction
                ", [
                    UserType::Board,
                    UserType::Employee,
                    UserType::Member,
                    UserType::PendingMember
                ]);
            } else {
                $query->orderBy($sort, $direction);
            }
        }

        $users = $query->withTrashed()->paginate(20)->appends($request->all());

        return view('pages.dashboard.users.index', compact('users'));
    }

    public function promote(User $user)
    {
        $user->changeType(\App\Enums\UserType::Board);
        return back()->with('success', 'User promoted to board.');
    }

    public function demote(User $user)
    {
        $user->changeType(\App\Enums\UserType::Member);
        return back()->with('success', 'User demoted from board.');
    }

    public function block(User $user)
    {
        $user->blocked = true;
        $user->save();
        return back()->with('success', 'User blocked.');
    }

    public function unblock(User $user)
    {
        $user->blocked = false;
        $user->save();
        return back()->with('success', 'User unblocked.');
    }

    public function cancelMembership(User $user)
    {
        $user->delete();
        return back()->with('success', 'Membership canceled (user soft deleted).');
    }

    public function edit(User $user)
    {
        // ...existing code for edit page...
        return view('pages.dashboard.users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->forceDelete();
        return back()->with('success', 'User permanently deleted.');
    }
}
