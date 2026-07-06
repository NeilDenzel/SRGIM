<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return Categoria::orderBy('nombre')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nombre' => 'required|string|max:255|unique:categorias']);
        return Categoria::create($data);
    }
}
