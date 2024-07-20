<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Laravel\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        $imagen = $request->file('file');

        $nombreImagen = Str::uuid(). '.' . $imagen->extension();

        //esto si sirve, dice que es de la version 3

        $manager = new ImageManager(new Driver());

        $imagenServidor=$manager->read($imagen);

        $imagenServidor->cover(1000, 1000);

        //esto es de la version 2, no sirve o saber que onda 

        // $imagenServidor= Image::make($imagen);

        // $imagenServidor->fit(1000, 1000);

        $imagenPath = public_path('uploads'). '/' . $nombreImagen;
        $imagenServidor->save($imagenPath);


        return response()->json(['imagen' => $nombreImagen]);
        
    }
}
