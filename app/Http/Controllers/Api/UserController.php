<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users with optional search and pagination
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = User::query()->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Data users berhasil diambil',
            'data' => [
                'users' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ]
            ]
        ], 200);
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|max:255',
            'username' => 'required|unique:users|min:3|max:255',
            'role' => 'required|in:ADMIN,KASIR,MANAJER',
            'password' => 'required|min:6',
        ]);

        // Hash password akan otomatis di-handle oleh User model mutator
        $user = User::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'role' => $user->role,
                    'created_at' => $user->created_at,
                ]
            ]
        ], 201);
    }

    /**
     * Display the specified user
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diambil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'role' => $user->role,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ]
        ], 200);
    }

    /**
     * Update the specified user in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        // Mencegah user mengubah akunnya sendiri menjadi role lain
        if ($user->id === $request->user()->id && $request->filled('role')) {
            if ($request->role !== $user->role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat mengubah role akun Anda sendiri',
                ], 403);
            }
        }

        $rules = [
            'nama' => 'required|max:255',
            'role' => 'required|in:ADMIN,KASIR,MANAJER',
        ];

        // Validasi username hanya jika berubah
        if ($request->username != $user->username) {
            $rules['username'] = 'required|unique:users|min:3|max:255';
        }

        // Password optional saat update
        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        $validatedData = $request->validate($rules);

        // Hapus password dari data yang akan diupdate jika tidak diisi
        if (!$request->filled('password')) {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diubah',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'role' => $user->role,
                    'updated_at' => $user->updated_at,
                ]
            ]
        ], 200);
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        // Mencegah user menghapus akunnya sendiri
        if ($user->id === $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri',
            ], 403);
        }

        DB::transaction(function () use ($user) {
            // Hapus data terkait
            $user->returns()->delete();
            $user->transactions()->delete();
            $user->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'User beserta data terkait berhasil dihapus',
        ], 200);
    }

    /**
     * Bulk delete multiple users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:users,id',
        ]);

        $selectedIds = $request->input('ids');
        
        // Pastikan user yang sedang login tidak ada dalam daftar
        if (in_array($request->user()->id, $selectedIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri',
            ], 403);
        }

        DB::transaction(function () use ($selectedIds) {
            $users = User::whereIn('id', $selectedIds)->get();
            foreach ($users as $user) {
                $user->returns()->delete();
                $user->transactions()->delete();
                $user->delete();
            }
        });

        return response()->json([
            'success' => true,
            'message' => count($selectedIds) . ' user berhasil dihapus beserta data terkait',
        ], 200);
    }

    /**
     * Get all users without pagination
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        $search = $request->input('search');

        $query = User::query()->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        $users = $query->get(['id', 'nama', 'username', 'role', 'created_at']);

        return response()->json([
            'success' => true,
            'message' => 'Data users berhasil diambil',
            'data' => [
                'users' => $users,
                'total' => $users->count(),
            ]
        ], 200);
    }
}
