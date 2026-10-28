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

    // Mostrar productos, buscar, filtrar y paginar.
    public function index(Request $request)
    {
        $productos = $this->obtenerProductos();

        $buscar = $request->query('buscar', '');
        $categoria = $request->query('categoria', '');

        // Aplicar búsqueda y filtro por categoría.
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

        // Configurar la paginación.
        $porPagina = 3;
        $totalProductos = count($productos);

        $totalPaginas = max(
            1,
            (int) ceil($totalProductos / $porPagina)
        );

        $pagina = max(
            1,
            (int) $request->query('page', 1)
        );

        // Mantener la página dentro del rango disponible.
        $pagina = min($pagina, $totalPaginas);

        // Obtener únicamente los productos de la página actual.
        $productosPagina = array_slice(
            $productos,
            ($pagina - 1) * $porPagina,
            $porPagina,
            true
        );

        return view('productos.index', [
            'productos' => $productosPagina,
            'buscar' => $buscar,
            'categoria' => $categoria,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'totalProductos' => $totalProductos,
        ]);
    }

    // Mostrar formulario de registro.
    public function create()
    {
        return view('productos.create');
    }

    // Registrar un producto (POST).
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

    // Mostrar formulario de edición.
    public function edit(string $id)
    {
        $productos = $this->obtenerProductos();

        abort_unless(isset($productos[$id]), 404);

        return view('productos.edit', [
            'producto' => $productos[$id],
            'id' => $id,
        ]);
    }

    // Actualizar un producto (PUT).
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

    // Eliminar un producto (DELETE).
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
