<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'type', 'trashed'),
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'type', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->transform(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => $user->type,
                    'owner' => $user->owner,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'type' => ['required', 'max:100'],
            'cpf' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        Auth::user()->account->users()->create([
            'name' => Request::get('name'),
            'email' => Request::get('email'),
            'cpf' => Request::get('cpf'),
            'type' => Request::get('type'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
        ]);

        return Redirect::route('users')->with('success', 'User created.');
    }

    public function perfil(User $user)
    {
        return Inertia::render('Users/Perfil', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
                'cpf' => $user->cpf,
                'photo' => $user->photo_path ? Storage::url("app/".$user->photo_path) : null,
            ],
        ]);
    }

    public function update(User $user)
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'type' => ['required', 'max:100'],
            'cpf' => ['required', 'max:50'],
            'email' => ['required', 'max:100', 'email', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'image'],
        ]);

        $user->update(Request::only('name', 'type', 'cpf', 'email'));

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        }

        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}
