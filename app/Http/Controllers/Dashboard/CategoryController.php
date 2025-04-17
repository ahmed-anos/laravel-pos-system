<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::when($request->search, function ($q) use ($request) {
            return $q->whereTranslationLike('name', '%' . $request->search . '%');
        })->latest()->paginate(2);

        return view('dashboard.categories.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ar.name' => 'required|unique:category_translations,name',
            'en.name' => 'required|unique:category_translations,name',
        ]);

        $category = Category::create();

        $category->translateOrNew('ar')->name = $request->input('ar.name');
        $category->translateOrNew('en')->name = $request->input('en.name');

        $category->save();

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.categories.index');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'ar.name' => 'required|unique:category_translations,name,' . $category->id . ',category_id',
            'en.name' => 'required|unique:category_translations,name,' . $category->id . ',category_id',
        ]);

        $category->translateOrNew('ar')->name = $request->input('ar.name');
        $category->translateOrNew('en')->name = $request->input('en.name');

        $category->save();

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.categories.index');
    }
}
