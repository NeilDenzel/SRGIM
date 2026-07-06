<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($buscar = $request->buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        $productos = $query->orderBy('nombre')->get();

        return view('catalogos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('catalogos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto_ruta'] = $request->file('foto')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('catalogos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();
        return view('catalogos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($producto->foto_ruta) {
                Storage::disk('public')->delete($producto->foto_ruta);
            }
            $data['foto_ruta'] = $request->file('foto')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('catalogos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->foto_ruta) {
            Storage::disk('public')->delete($producto->foto_ruta);
        }
        $producto->delete();
        return redirect()->route('catalogos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
