<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.categories.index', [
            'active' => 'categories',
            'categories' => Category::latest()
                ->filter(request(['search']))
                ->paginate(7)
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
        return view('dashboard.categories.create', [
            'active' => 'data',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $validatedData = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Category::whereRaw('LOWER(nama) = ?', [strtolower($value)])->exists();
                    if ($exists) {
                        $fail('Nama Mitra Binaan sudah ada (tidak boleh sama meskipun berbeda huruf besar/kecil).');
                    }
                },
            ],
            'nomor_penanggung_jawab' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);
        
        Category::create($validatedData);

        return redirect('/dashboard/categories')->with('success', 'Mitra Binaan telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', [
            'category' => $category,
            'active' => 'data',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'nama')->ignore($category->id),
            ],
            'nomor_penanggung_jawab' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $category->update($validatedData);

        return redirect('/dashboard/categories')->with('success', 'Mitra Binaan telah diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Cek apakah kategori memiliki produk terkait
        if ($category->goods()->count() > 0) {
            return redirect('/dashboard/categories')->with('error', 'Gagal menghapus! Mitra "' . $category->nama . '" masih memiliki produk terdaftar.');
        }

        Category::destroy($category->id);

        return redirect('/dashboard/categories')->with('success', 'Mitra Binaan telah dihapus.');
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
            'selected_ids.*' => 'exists:categories,id',
        ]);

        $selectedIds = $request->input('selected_ids');
        
        if (empty($selectedIds)) {
            return redirect('/dashboard/categories')->with('error', 'Tidak ada mitra yang dipilih untuk dihapus.');
        }

        // Cek apakah ada mitra yang dipilih yang masih memiliki produk
        $categoriesWithGoods = Category::whereIn('id', $selectedIds)->has('goods')->get();

        if ($categoriesWithGoods->isNotEmpty()) {
            $names = $categoriesWithGoods->pluck('nama')->implode(', ');
            return redirect('/dashboard/categories')->with('error', 'Gagal menghapus! Mitra berikut masih memiliki produk: ' . $names);
        }

        // Lanjutkan penghapusan jika tidak ada produk terkait
        $deletedCount = Category::whereIn('id', $selectedIds)->delete();

        if ($deletedCount > 0) {
            return redirect('/dashboard/categories')->with('success', $deletedCount . ' mitra berhasil dihapus!');
        }

        return redirect('/dashboard/categories')->with('error', 'Gagal menghapus mitra yang dipilih.');
    }
}
