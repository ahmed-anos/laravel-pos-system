<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories=Category::all();
        $products=Product::when($request->search ,function($q) use ($request){
            return $q->whereTranslationLike('name', '%' . $request->search . '%');
        })->when($request->category_id ,function($q) use($request){
            return $q->where('category_id' ,$request->category_id);
        })->latest()->paginate(5);
        return view('dashboard.products.index' ,compact('products' ,'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories=Category::all();
        return view('dashboard.products.create' ,compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        $request->validate([
            'ar.name' => 'required|unique:category_translations,name',
            'en.name' => 'required|unique:category_translations,name',
            'category_id'=>'required',
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'stock'=>'required',
            'ar.name' => 'required|unique:product_translations,name',
            'en.name' => 'required|unique:product_translations,name',
        ]);

        if ($request->has('image')) {
            $upload = $request->file('image');
            $image = Image::read($upload)
                ->resize(300, 200);
            $image_name = Str::random() . '.' . $upload->getClientOriginalExtension();
            $image->save(public_path('uploads/product_images/' . $image_name));
        }
        $product = Product::create([
            'category_id'=>$request->category_id,
            'purchase_price'=>$request->purchase_price,
            'sale_price'=>$request->sale_price,
            'stock'=>$request->stock,
            'image' => $image_name ?? 'default.png'
            
        ]);

        $product->translateOrNew('ar')->name = $request->input('ar.name');
        $product->translateOrNew('en')->name = $request->input('en.name');
        $product->translateOrNew('ar')->description  = $request->input('ar.description');
        $product->translateOrNew('en')->description  = $request->input('en.description');

        $product->save();

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories=Category::all();
        return view('dashboard.products.edit' ,['product'=>$product ,'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     */
  

    public function update(Request $request, Product $product)
{
    $request->validate([
        'category_id' => 'required',
        'purchase_price' => 'required',
        'sale_price' => 'required',
        'stock' => 'required',

        'ar.name' => 'required|unique:category_translations,name,' . $product->id . ',product_id',
        'en.name' => 'required|unique:category_translations,name,' . $product->id . ',product_id',

        'ar.name' => 'required|unique:product_translations,name,' . $product->id . ',product_id',
        'en.name' => 'required|unique:product_translations,name,' . $product->id . ',product_id',
    ]);

    if ($request->hasFile('image')) {
        $upload = $request->file('image');
        $image = Image::read($upload)->resize(300, 200);
        $image_name = Str::random() . '.' . $upload->getClientOriginalExtension();
        $image->save(public_path('uploads/product_images/' . $image_name));
    } else {
        $image_name = $product->image; 
    }

    $product->update([
        'category_id' => $request->category_id,
        'purchase_price' => $request->purchase_price,
        'sale_price' => $request->sale_price,
        'stock' => $request->stock,
        'image' => $image_name,
    ]);

    $product->translateOrNew('ar')->name = $request->input('ar.name');
    $product->translateOrNew('en')->name = $request->input('en.name');
    $product->translateOrNew('ar')->description = $request->input('ar.description');
    $product->translateOrNew('en')->description = $request->input('en.description');

    $product->save();

    session()->flash('success', __('site.updated_successfully'));
    return redirect()->route('dashboard.products.index');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image != 'default.png') {
            Storage::disk('public_uploads')->delete('product_images/' . $product->image);
        }
        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');
    }
}
