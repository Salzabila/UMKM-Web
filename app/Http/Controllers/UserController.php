<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.users.index', [
            'active' => 'users',
            'users' => User::latest()
                ->filter(request(['search']))
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create', [
            'active' => 'data',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'username' => 'required|unique:users|min:3|max:255',
            'role' => 'required|in:ADMIN,KASIR,MANAJER', 
            'password' => 'required|min:6',
        ]);

        User::create($validatedData);

        return redirect('/dashboard/users')->with('success', 'Pengguna baru telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit', [
            'user' => $user,
            'active' => 'data',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $rules = [
            'nama' => 'required|max:255',
            'role' => 'required|in:ADMIN,KASIR,MANAJER', 
        ];

        if($request->username != $user->username) {
            $rules['username'] = 'required|unique:users|min:3|max:255';
        }

        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $validatedData = $request->validate($rules);

        if (!$request->filled('password')) {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect('/dashboard/users')->with('success', 'Data pengguna telah diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Mencegah user menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return redirect('/dashboard/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        DB::transaction(function () use ($user) {
            $user->returns()->delete();
            $user->transactions()->delete();
            $user->delete();
        });

        return redirect('/dashboard/users')->with('success', 'Pengguna beserta data terkait telah dihapus.');
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:users,id',
        ]);

        $selectedIds = $request->input('selected_ids');
        
        // Pastikan user yang sedang login tidak ada di dalam daftar yang akan dihapus
        if (in_array(Auth::id(), $selectedIds)) {
            return redirect('/dashboard/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri dari daftar hapus massal.');
        }

        if (empty($selectedIds)) {
            return redirect('/dashboard/users')->with('error', 'Tidak ada pengguna yang dipilih untuk dihapus.');
        }

        DB::transaction(function () use ($selectedIds) {
            $users = User::whereIn('id', $selectedIds)->get();
            foreach ($users as $user) {
                $user->returns()->delete();
                $user->transactions()->delete();
                $user->delete();
            }
        });

        return redirect('/dashboard/users')->with('success', count($selectedIds) . ' pengguna berhasil dihapus beserta data terkait.');
    }
}
