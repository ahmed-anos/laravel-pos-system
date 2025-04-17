<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Client $client)
    {
        $categories=Category::with('products')->get();
        $orders =$client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.create' ,compact('client' ,'categories' ,'orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request ,Client $client)
    {

        
        $request->validate([
            'products'=>'required|array',
        ]); 

        
        $data = $request->all();
        $total_price = str_replace(',', '', $request->total_price); 

        $products=$data['products'];
   
        $order = Order::create([
            'client_id' => $client->id,
            'total_price' => $total_price
        ]);

        foreach ($products as $product_id => $quantity) {
            $product = Product::findOrFail($product_id); 
            
            $order->products()->attach($product_id, ['quantity' => $quantity['quantity']]);
           
            if($product->stock == 0 ){
                $product->stock = 0;
            }else{
                $product->update([
                    'stock'=>$product->stock - $quantity['quantity']
                ]);
            }
        }

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
    

    }

   

    /**
     * Show the form for editing the specified resource.
     */


    public function edit(Client $client ,Order $order)
    {
        $categories= Category::all();
        $orders =$client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.edit' , compact('categories' ,'order' ,'client' ,'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
 

  
    public function update(Request $request, Client $client, Order $order)
    {
        $request->validate([
            'products' => 'required|array',
        ]);
    
        $data = $request->all();
        $products = $data['products'];
    
        $total_price = 0;
        foreach ($products as $product_id => $quantity) {
            $product = Product::findOrFail($product_id);
            $total_price += $product->sale_price * $quantity['quantity'];
        }
    
        $order->update([
            'total_price' => $total_price
        ]);
    
        foreach ($order->products as $product) {
            $product->update([
                'stock' => $product->stock + $product->pivot->quantity
            ]);
        }
    
        $order->products()->detach();
    
        foreach ($products as $product_id => $quantity) {
            $product = Product::findOrFail($product_id);
    
            $order->products()->attach($product_id, ['quantity' => $quantity['quantity']]);
    
            if ($product->stock == 0) {
                $product->stock = 0;
            } else {
                $product->update([
                    'stock' => $product->stock - $quantity['quantity']
                ]);
            }
        }
    
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');
    }
 
  
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client ,Order $order)
    {
        //
    }
}
