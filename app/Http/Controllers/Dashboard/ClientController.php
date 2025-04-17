<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Pest\Support\View;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients=Client::when($request->search ,function ($q) use ($request){
            return $q->where('name' ,'like' ,'%'.$request->search.'%')
            ->orWhere('phone' ,'like' ,'%'.$request->search.'%')
            ->orWhere('address' ,'like' ,'%'.$request->search.'%');
        })->latest()->paginate(5);
        return view('dashboard.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
       $request->validate([
            'name'=>'required',
            'phone.0'=>'required',
            'address'=>'required'
       ]);

       $client=Client::create($request->all());
       session()->flash('success', __('site.added_successfully'));
       return redirect()->route('dashboard.clients.index');
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('dashboard.clients.edit' ,compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        // return $request;
        $request->validate([
            'name'=>'required',
            'phone.0'=>'required',
            'address'=>'required'
       ]);

       $client->update($request->all());
       session()->flash('success', __('site.updated_successfully'));
       return redirect()->route('dashboard.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        session()->flash('success', __('site.deleted_successfully'));
       return redirect()->route('dashboard.clients.index');
    }
}
