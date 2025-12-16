<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountApprovedNotification;
use App\Notifications\AccountRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'all');
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        $users = User::with(['student', 'company'])
            ->where('role', '!=', 'admin')
            ->when($role !== 'all', function($q) use ($role) {
                $q->where('role', $role);
            })
            ->when($status !== 'all', function($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($search, function($q, $search) {
                $q->where(function($query) use ($search) {
                    $query->where('email', 'ILIKE', "%{$search}%")
                        ->orWhereHas('student', function($q) use ($search) {
                            $q->where('full_name', 'ILIKE', "%{$search}%")
                              ->orWhere('nis', 'ILIKE', "%{$search}%");
                        })
                        ->orWhereHas('company', function($q) use ($search) {
                            $q->where('name', 'ILIKE', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users', 'role', 'status'));
    }

    public function pending()
    {
        $pendingUsers = User::with(['student', 'company'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.users.pending', compact('pendingUsers'));
    }

    public function show($id)
    {
        $user = User::with(['student', 'company'])->findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat melihat detail akun admin.');
        }

        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with(['student', 'company'])->findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat mengedit akun admin.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat mengedit akun admin.');
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'status' => ['required', 'in:pending,approved,rejected'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $updateData = [
            'email' => $validated['email'],
            'status' => $validated['status'],
        ];

        // Update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()
            ->route('admin.users.show', $user->id)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $id)
    {
        $validated = $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat mereset password admin.');
        }

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Password user berhasil direset.');
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        // Send notification
        $user->notify(new AccountApprovedNotification());

        return back()->with('success', 'User berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Send notification
        $user->notify(new AccountRejectedNotification($validated['rejection_reason']));

        return back()->with('success', 'User berhasil ditolak.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus akun admin.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}