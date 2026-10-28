@extends('layouts.app')

@section('title', 'Registrar producto')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <h1 class="fw-bold">Registrar producto</h1>
            <p class="text-muted">Completa los datos del nuevo producto.</p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                @include('productos._form', [
                    'accion' => route('productos.store'),
                    'metodo' => 'POST',
                    'producto' => [],
                ])
            </div>
        </div>
    </div>
@endsection
