<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function __invoke()
    {
        //Obtener a quienes seguimos
        $ids=Auth::user()->followings->pluck('id')->toArray();

        $posts=Post::whereIn('user_id',$ids)->latest()->paginate(20);


        return view('home', [
            'posts' => $posts
        ]);  
    }

}
