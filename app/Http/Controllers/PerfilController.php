<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }


    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {

        $request->request->add(['username'=> Str::slug($request->username)]);

        $request->validate([
            'username' => ['required','unique:users,username,'.Auth::user()->id,'min:3','max:20','not_in:twitter,editar-perfil,'],
        ]);

        if($request->imagen){
            $imagen = $request->file('imagen');

            $nombreImagen = Str::uuid(). '.' . $imagen->extension();
    
    
            $manager = new ImageManager(new Driver());
    
            $imagenServidor=$manager->read($imagen);
    
            $imagenServidor->resize(1000, 1000);
    
    
            $imagenPath = public_path('perfiles'). '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        //guardar cambios
        $usuario = User::find(Auth::user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? Auth::user()->imagen ?? null;
        $usuario->save();

        //redireccionar
        return redirect()->route('posts.index', $usuario->username);


    }

}
