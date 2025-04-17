<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request){
        $orders= Order::whereHas('client' , function ($q) use ($request){
            return $q->where('name' ,'like' ,'%'.$request->search.'%');
        })->latest()->paginate(5);
        
        return view('dashboard.orders.index', compact('orders'));
    }

 

   
public function products($orderId)
{

    $order = Order::findOrFail($orderId);
    $products = $order->products; 
    return view('dashboard.orders._products', compact('products', 'order'));
}

public function destroy(Order $order)
{
  
    $order->delete();
    session()->flash('success', __('site.deleted_successfully'));
    return redirect()->route('dashboard.orders.index');

}


}