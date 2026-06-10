<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        $totalUsers = $users->count();
        $totalExp = $users->sum('exp');
        
        $avgLevel = 1;
        if ($totalUsers > 0) {
            $avgLevel = round($users->avg('level'), 1);
        }

        // Leaderboard / Paginated users list for the table
        $paginatedUsers = User::orderBy('exp', 'desc')->paginate(10);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalExp',
            'avgLevel',
            'paginatedUsers'
        ));
    }

    public function updateUserExp(Request $request, User $user)
    {
        $request->validate([
            'exp' => 'required|integer|min:0'
        ]);

        $user->exp = $request->exp;
        $user->save();

        return back()->with('success', "EXP untuk {$user->name} berhasil diperbarui menjadi {$request->exp}.");
    }

    public function deleteUser(User $user)
    {
        // Don't allow deleting yourself if the admin has a user record
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $user->delete();
        return back()->with('success', "Pengguna {$user->name} berhasil dihapus dari sistem.");
    }
}
