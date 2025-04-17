<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class UserController extends Controller
{



    public function index(Request $request)
    {

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(2);
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'permissions' => 'required|min:1'
        ]);

        if ($request->image != 'default.png') {
            $upload = $request->file('image');
            $image = Image::read($upload)
                ->resize(300, 200);
            $image_name = Str::random() . '.' . $upload->getClientOriginalExtension();
            $image->save(public_path('uploads/user_images/' . $image_name));
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $image_name ?? 'default.png'
        ]);
        $user->syncPermissions($request->permissions);

        session()->flash('success', __(key: 'site.updated_successfully'));

        return redirect()->route('dashboard.users.index');
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'permissions' => 'required|min:1'
        ]);

        if ($request->has('image')) {
            $upload = $request->file('image');
            $image = Image::read($upload)
                ->resize(300, 200);
            $image_name = Str::random() . '.' . $upload->getClientOriginalExtension();
            $image->save(public_path('uploads/user_images/' . $image_name));
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'image' => $image_name ?? 'default.png'
        ]);
        $user->addRole('admin');
        $user->givePermissions($request->permissions);
        session()->flash('success', __(key: 'site.added_successfully'));

        return redirect()->route('dashboard.users.index');
    }

    public function destroy(User $user)
    {
        if ($user->image != 'default.png') {
            Storage::disk('public_uploads')->delete('user_images/' . $user->image);
        }
        $user->delete();
        session()->flash('success', __(key: 'site.deleted_successfully'));

        return redirect()->route('dashboard.users.index');
    }
}
