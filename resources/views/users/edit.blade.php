@extends('layouts.app')

@section('content')
    <h1>Editar Usuario</h1>

    <form method="POST" action="{{ route('users.update', $usuario->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label>Correo Electrónico</label>
            <input type="email" name="correo_electronico" class="form-control" value="{{ old('correo_electronico', $usuario->correo_electronico) }}" required>
            @error('correo_electronico')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
        </div>

        <div class="mb-3">
            <label>Cargo</label>
            <select name="id_rol" class="form-control" required>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}" @if($usuario->id_rol == $r->id) selected @endif>
                        {{ $r->nombre_cargo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Fecha de Ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" max="{{ now()->toDateString() }}" value="{{ old('fecha_ingreso', $usuario->fecha_ingreso) }}" required>
        </div>

        <button class="btn btn-success" type="submit">Actualizar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection
