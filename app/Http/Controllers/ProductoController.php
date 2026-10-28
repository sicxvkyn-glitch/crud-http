<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    private function obtenerProductos(): array
    {
        return session('productos', [
            1 => [
                'nombre' => 'Laptop',
                'categoria' => 'Computadoras',
                'precio' => 12500,
            ],
            2 => [
                'nombre' => 'Mouse',
                'categoria' => 'Accesorios',
                'precio' => 350,
            ],
            3 => [
                'nombre' => 'Escritorio',
                'categoria' => 'Muebles',
                'precio' => 2800,
            ],
            4 => [
                'nombre' => 'Silla',
                'categoria' => 'Muebles',
                'precio' => 1500,
            ],
        ]);
    }

    public function index(Request $request)
    {
        $productos = $this->obtenerProductos();

        $buscar = $request->query('buscar', '');
        $categoria = $request->query('categoria', '');

        $productos = array_filter(
            $productos,
            function ($producto) use ($buscar, $categoria) {
                $coincideNombre = stripos(
                    $producto['nombre'],
                    $buscar
                ) !== false;

                $coincideCategoria = $categoria === ''
                    || $producto['categoria'] === $categoria;

                return $coincideNombre && $coincideCategoria;
            }
        );

        return view('productos.index', [
            'productos' => $productos,
            'buscar' => $buscar,
            'categoria' => $categoria,
        ]);
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'categoria' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $productos = $this->obtenerProductos();

        $id = empty($productos)
            ? 1
            : max(array_keys($productos)) + 1;

        $productos[$id] = $datos;

        session(['productos' => $productos]);

        return redirect()
            ->route('productos.index')
            ->with('ok', 'Producto registrado correctamente.');
    }

    public function edit(string $id)
    {
        $productos = $this->obtenerProductos();

        abort_unless(isset($productos[$id]), 404);

        return view('productos.edit', [
            'producto' => $productos[$id],
            'id' => $id,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $productos = $this->obtenerProductos();

        abort_unless(isset($productos[$id]), 404);

        $datos = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'categoria' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $productos[$id] = $datos;

        session(['productos' => $productos]);

        return redirect()
            ->route('productos.index')
            ->with('ok', 'Producto actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $productos = $this->obtenerProductos();

        abort_unless(isset($productos[$id]), 404);

        unset($productos[$id]);

        session(['productos' => $productos]);

        return redirect()
            ->route('productos.index')
            ->with('ok', 'Producto eliminado correctamente.');
    }
}
