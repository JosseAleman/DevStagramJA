@extends('layouts.app')

@section('titulo')
    {{$post->titulo}}

@endsection

@section('contenido')
    <div class="container mx-auto md:flex">
        <div class="md:w-1/2">
            <img src="{{ asset('uploads'). '/' . $post->imagen }}" 
            alt="Imagen del post {{ $post->titulo }}">

            <div class="p-3 flex items-center gap-4">

                @auth

                    <livewire:like-post :post="$post"/>
     
                @endauth
            </div>

            <div>
                <p class="font-bold">
                    {{ $post->user->username }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $post->created_at->diffForHumans() }}
                </p>

                <p class="mt-5">
                    {{ $post->descripcion }}
                </p>
            </div>

            @auth
                @if($post->user_id === auth()->user()->id)
                    <form action="{{ route('posts.destroy', $post )}}" method="POST">
                        @method('DELETE') 
                        {{-- Laravel nativamente no permite otro metodo que no sea post y get, asi que se usa el sppofing) --}}
                        @csrf
                        <input type="submit"
                        value="Eliminar Publicación"
                        class="bg-red-500 hover:bg-red-600 text-white p-3 rounded mt-4 cursor-pointer font-bold"
                        >
                    </form>
                @endif
                
            @endauth
        </div>

        <div class="md:w-1/2 p-5">
            <div class="shadow bg-white p-5 mb-5">

                @auth
                    <p class="text-xl font-bold text-center mb-4">Agrega un Nuevo Comentario</p>

                    @if (@session('mensaje'))
                        <div class="bg-green-500 p-2 rounded-lg mb-6 text-white text-center uppercase font-bold">
                            {{ session('mensaje')}}
                        </div>
                        
                        
                    @endif

                    <form action="{{ route('comentarios.store', ['post'=>$post, 'user'=>$user]) }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label for="comentario" class="mb-2 block uppercase text-gray-500 font-bold">
                                Añade un comentario
                            </label>
                            <textarea id="comentario" name="comentario" placeholder="Agrega un comentario" 
                            class="border p-3 w-full rounded-lg @error('comentario')
                                border-red-500
                            @enderror" 
                            ></textarea>
        
                            @error('comentario')
                                <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <input type="submit" value="Comentar" class="bg-sky-600 hover:bg-sky-700 
                        transition-colors cursor-pointer uppercase font-bold w-full text-white rounded-lg p-3">
                    </form>
                @endauth

                <div class="bg-white mb-5 max-h-96 overflow-y-scroll mt-10">
                    @if ($post->comentarios->count())
                        @foreach ($post->comentarios as $comentario)
                            <div class="p-5 border-gray-300 border-b">
                                <a href="{{ route('posts.index', $comentario->user) }}" class="font-bold">{{ $comentario->user->username }}</a>
                                <p>{{ $comentario->comentario }}</p>
                                <p class="text-sm text-gray-500">{{ $comentario->created_at->diffForHumans() }}</p>
                            </div>

                        @endforeach
                    @else
                        <p class="text-center p-10">No hay comentarios aún</p>
                    @endif
                    
                </div>

            </div>

        </div>

    </div>

@endsection