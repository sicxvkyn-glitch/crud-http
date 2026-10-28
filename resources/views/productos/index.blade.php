@extends('layouts.app')

@section('title', 'Gestión de productos')

@section('content')
    <div class="container py-4">

        {{-- Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold">Gestión de productos</h1>
                <p class="text-muted mb-0">
                    Administra tu catálogo de productos.
                </p>
            </div>

            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                + Nuevo producto
            </a>
        </div>

        {{-- Mensaje de confirmación --}}
        @if (session('ok'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('ok') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Buscador y filtros --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('productos.index') }}" method="GET">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="buscar" class="form-label">
                                Buscar por nombre
                            </label>
                            <input type="text" id="buscar" name="buscar" class="form-control"
                                placeholder="Ej. Laptop" value="{{ $buscar }}">
                        </div>

                        <div class="col-md-4">
                            <label for="categoria" class="form-label">
                                Categoría
                            </label>
                            <select id="categoria" name="categoria" class="form-select">
                                <option value="">Todas las categorías</option>

                                @foreach (['Computadoras', 'Accesorios', 'Muebles'] as $opcion)
                                    <option value="{{ $opcion }}" @selected($categoria === $opcion)>
                                        {{ $opcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-dark w-100">
                                Filtrar
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de productos --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($productos as $id => $producto)
                            <tr>
                                <td>{{ $id }}</td>

                                <td class="fw-semibold">
                                    {{ $producto['nombre'] }}
                                </td>

                                <td>
                                    <span class="badge text-bg-secondary">
                                        {{ $producto['categoria'] }}
                                    </span>
                                </td>

                                <td>
                                    ${{ number_format($producto['precio'], 2) }}
                                </td>

                                <td class="text-center">
                                    {{-- Botón para editar --}}
                                    <a href="{{ route('productos.edit', $id) }}" class="btn btn-sm btn-outline-primary">
                                        Editar
                                    </a>

                                    {{-- Botón para abrir el modal --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalEliminar{{ $id }}">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No se encontraron productos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- Total de productos encontrados --}}
        <p class="text-muted mt-3">
            Productos encontrados: {{ $totalProductos }}
        </p>

        {{-- Paginación visual --}}
        @if ($totalPaginas > 1)
            <nav aria-label="Paginación de productos" class="mt-3">
                <ul class="pagination justify-content-center">

                    {{-- Botón anterior --}}
                    <li class="page-item {{ $pagina <= 1 ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => max(1, $pagina - 1)]) }}"
                            @if ($pagina <= 1) aria-disabled="true" tabindex="-1" @endif>
                            Anterior
                        </a>
                    </li>

                    {{-- Números de página --}}
                    @for ($i = 1; $i <= $totalPaginas; $i++)
                        <li class="page-item {{ $pagina === $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                                @if ($pagina === $i) aria-current="page" @endif>
                                {{ $i }}
                            </a>
                        </li>
                    @endfor

                    {{-- Botón siguiente --}}
                    <li class="page-item {{ $pagina >= $totalPaginas ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ request()->fullUrlWithQuery(['page' => min($totalPaginas, $pagina + 1)]) }}"
                            @if ($pagina >= $totalPaginas) aria-disabled="true" tabindex="-1" @endif>
                            Siguiente
                        </a>
                    </li>

                </ul>
            </nav>
        @endif

        {{-- Modales de confirmación --}}
        @foreach ($productos as $id => $producto)
            <div class="modal fade" id="modalEliminar{{ $id }}" tabindex="-1"
                aria-labelledby="tituloModal{{ $id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="tituloModal{{ $id }}">
                                Confirmar eliminación
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body">
                            ¿Seguro que deseas eliminar el producto
                            <strong>{{ $producto['nombre'] }}</strong>?
                            Esta acción no se puede deshacer.
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <form action="{{ route('productos.destroy', $id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">
                                    Sí, eliminar
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
