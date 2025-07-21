<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Dashboard\User\UpdateUserRequest;
use App\Http\Requests\Dashboard\User\CreateUserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

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

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at')->where('blocked', false);
            } elseif ($request->status === 'blocked') {
                $query->where('blocked', true)->whereNull('deleted_at');
            } elseif ($request->status === 'deleted') {
                $query->onlyTrashed();
            }
        }

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
        $user->changeType(UserType::Board);
        return back()->with('success', 'User promoted to board.');
    }

    public function demote(User $user)
    {
        $user->changeType(UserType::Member);
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
        return view('pages.dashboard.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->type = $validated['type'];
        $user->blocked = $validated['blocked'];
        $user->gender = $validated['gender'];

        if ($request->hasFile('photo')) {
            if ($user->photo && \Storage::disk('public')->exists('users/' . $user->photo)) {
                \Storage::disk('public')->delete('users/' . $user->photo);
            }
            $file = $request->file('photo');
            $filename = uniqid('user_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('users', $filename, 'public');
            $user->photo = $filename;
        }

        $user->save();

        return redirect()->route('dashboard.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User permanently deleted.');
    }

    public function show($user)
    {      
        $user = User::withTrashed()->findOrFail($user);
        return view('pages.dashboard.users.show', compact('user'));
    }
    
    public function create()
    {
        return view('pages.dashboard.users.create');
    }

    public function store(CreateUserRequest $request)
    {
        $validated = $request->validated();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
            $photoPath = basename($photoPath);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'],
            'blocked' => $validated['blocked'] ?? false,
            'nif' => $validated['nif'] ?? null,
            'default_delivery_address' => $validated['default_delivery_address'] ?? null,
            'default_payment_type' => $validated['default_payment_type'] ?? null,
            'default_payment_reference' => $validated['default_payment_reference'] ?? null,
            'photo' => $photoPath,
            'type' => $validated['type'] ?: UserType::PendingMember->value,
        ]);

        $user->save();

        return redirect()->route('dashboard.users.index')->with('success', 'User created successfully.');
    }
}
