<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request, User $user, Post $post)
    {
        //validar
        $request->validate([
            'comentario' => 'required|max:255'
        ]);


        //almacenar
        Comentario::create([
            'comentario' => $request->comentario,
            'user_id' => Auth::user()->id,
            'post_id' => $post->id
        ]);

        //imprimir mensaje
        return back()->with('mensaje', 'Comentario realizado correctamente');
    }
}
