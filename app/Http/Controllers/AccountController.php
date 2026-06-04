<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['admin', 'super_admin']);

        // Search capability
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $accounts = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.accounts.table', compact('accounts'));
        }

        return view('admin.accounts.index', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountRequest $request)
    {
        $data = $request->validated();

        try {
            $data['password'] = Hash::make($data['password']);
            $account = User::create($data);

            return response()->json([
                'message' => 'Admin account created successfully.',
                'account' => $account
            ], 201);
        } catch (\Exception $e) {
            Log::error('Store account error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Failed to create account.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return response()->json(['error' => 'User is not an administrator.'], 404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountRequest $request, User $user)
    {
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return response()->json(['error' => 'User is not an administrator.'], 404);
        }

        $data = $request->validated();

        try {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return response()->json([
                'message' => 'Admin account updated successfully.',
                'account' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Update account error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Failed to update account.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return response()->json(['error' => 'User is not an administrator.'], 404);
        }

        if (auth()->id() === $user->id) {
            return response()->json(['error' => 'You cannot delete your own account.'], 400);
        }

        try {
            $user->delete();
            return response()->json([
                'message' => 'Admin account deleted successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete account error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Failed to delete account.'], 500);
        }
    }
}
