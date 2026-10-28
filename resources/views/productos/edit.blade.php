@extends('layouts.app')

@section('title', 'Editar producto')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h1 class="fw-bold">Editar producto</h1>
            <p class="text-muted">
                Modifica la información del producto seleccionado.
            </p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                @include('productos._form', [
                    'accion' => route('productos.update', $id),
                    'metodo' => 'PUT',
                    'producto' => $producto,
                ])
            </div>
        </div>
    </div>
@endsection
