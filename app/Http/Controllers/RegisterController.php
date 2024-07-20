<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index(){
        return view("auth.register");
    }

    public function store(Request $request)
    {
        //dd($request);
        //dd($request->get('username'));

        //modificar el request
        $request->request->add(['username'=> Str::slug($request->username)]);

        //Validacion
        $request->validate([
            'name' => 'required|max:30',
            'username'=> 'required|unique:users|min:3|max:20',
            'email'=> 'required|email|unique:users|max:60',
            'password'=> 'required|confirmed|min:6'
        ]);
        

        User::create([
            'name' => $request->name,
            'username'=> $request->username,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        //autenticar al usuario
        //Auth::attempt(['email'=> $request->email,'password'=> $request->password]);

        //otra forma de autenticar al usuario
        Auth::attempt($request->only('email','password'));

   


        //rediccionar al usuario
        $user = Auth::user();
        
        return redirect()->route('posts.index', ['user' => $user->username]);

        //return redirect()->route('posts.index');


    }



    
}
