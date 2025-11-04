<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class UserManajemenController extends Controller
{
    public function index()
    {
        $user = User::all();
        return view('user_manajemen.index', compact('user'));
    }

    public function create()
    {
        return view('user_manajemen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'string|min:8',
            'role' => 'required|string',
            'jabatan' => 'required|string',
            'tanda_tangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('tanda_tangan')) {
            $filename = time() . '.' . $request->tanda_tangan->getClientOriginalExtension();
            $tanda_tangan = $request->file('tanda_tangan')->storeAs('tanda_tangan',$filename, 'public');
        }

        $user =User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('user_manajemen.index', compact('user'))->with('success', 'User berhasil ditambahkan');

    }

    public function edit(User $user)
    {
        return view('user_manajemen.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string',
            'jabatan' => 'required|string',
            'tanda_tangan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('tanda_tangan')) {
            $filename = time() . '.' . $request->tanda_tangan->getClientOriginalExtension();
            $tanda_tangan = $request->file('tanda_tangan')->storeAs('tanda_tangan',$filename, 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('user_manajemen.index', compact('user'))->with('success', 'User berhasil diupdate');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user_manajemen.index')->with('success', 'User berhasil dihapus');
    }
}
