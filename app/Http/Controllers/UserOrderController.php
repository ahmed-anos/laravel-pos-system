<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    // public function index(Request $request){

    //     $orders=UserOrder::where('user_id',auth()->user()->id)->paginate(5);
    //     return view('dashboard.user_orders.index' ,compact('orders'));
    // }
    public function index(Request $request)
{
    if(auth()->user()->hasRole('owner')) {
        $orders = UserOrder::paginate(5);
    } else {
        $orders = UserOrder::where('user_id', auth()->user()->id)->paginate(5);
    }

    return view('dashboard.user_orders.index', compact('orders'));
}


    public function create(){
        $products=Product::all();
        $order=UserOrder::where('user_id' ,Auth::user()->id)->latest()->first();
        // dd($order);
        return view('dashboard.user_orders.create', compact('order' ,'products'));
    }

    public function store(Request $request){
        $request->validate([
            'order_number'=>'required|string',
            'products'=>'required'
        ],[
            'order_number'=>'رقم الاوردر مطلوب',
            'products'=>'من فضلك حدد منتجات الاوردر'
        ]);
        $price=0;
        foreach($request->products as $product){
            $pr=Product::where('id',$product)->first();
            $price+=$pr->sale_price;
        }
        
        UserOrder::create([
            'user_id'=>auth()->user()->id,
            'order_number'=>$request->order_number,
            'table_number'=>$request->table_number,
            'products'=>$request->products,
            'total_price'=>$price
        ]);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.user_orders.index');
    }



    public function destroy($id)
    {
        $order = UserOrder::findOrFail($id);

        if ($order->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->delete();

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.user_orders.index');
    }

    public function edit($id)
    {
        $order = UserOrder::findOrFail($id);

        if ($order->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $products = Product::all();

        return view('dashboard.user_orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, $id)
    {
        $order = UserOrder::findOrFail($id);

        if ($order->user_id !== auth()->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'order_number' => 'required|string',
            'products' => 'required'
        ], [
            'order_number.required' => 'رقم الاوردر مطلوب',
            'products.required' => 'من فضلك حدد منتجات الاوردر'
        ]);

        $order->update([
            'order_number' => $request->order_number,
            'table_number' => $request->table_number,
            'products' => $request->products
        ]);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.user_orders.index');
    }
}
