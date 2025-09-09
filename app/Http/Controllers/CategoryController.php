<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class CategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Auth::user()->categories()->latest()->paginate(10);

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'success' => session('success')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Auth::user()->categories()->create($request->validated());

        //return redirect()->back()->with('success', 'Catégorie crée');
        return redirect(route('categories.index'))->with('success', 'Catégorie crée');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return Inertia::render('Categories/Edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);
        $category->update($request->validated());

        //return redirect()->back()->with('success', 'Catégorie mis à jour');
        return redirect(route('categories.index'))->with('success', 'Catégorie mis à jour');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        //return redirect()->back()->with('success', 'Catégorie supprimée');
        return redirect(route('categories.index'))->with('success', 'Catégorie supprimée');
    }
}
