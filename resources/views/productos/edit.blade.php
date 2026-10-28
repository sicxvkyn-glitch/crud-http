@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h1 class="fw-bold">Editar producto</h1>
            <p class="text-muted">Modifica la información del producto seleccionado.</p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('productos.update', $id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del producto</label>
                        <input type="text" id="nombre" name="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre', $producto['nombre']) }}" required>

                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select id="categoria" name="categoria" class="form-select @error('categoria') is-invalid @enderror"
                            required>
                            @foreach (['Computadoras', 'Accesorios', 'Muebles'] as $opcion)
                                <option value="{{ $opcion }}" @selected(old('categoria', $producto['categoria']) === $opcion)>
                                    {{ $opcion }}
                                </option>
                            @endforeach
                        </select>

                        @error('categoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" id="precio" name="precio"
                            class="form-control @error('precio') is-invalid @enderror"
                            value="{{ old('precio', $producto['precio']) }}" min="0" step="0.01" required>

                        @error('precio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Actualizar producto
                        </button>

                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
