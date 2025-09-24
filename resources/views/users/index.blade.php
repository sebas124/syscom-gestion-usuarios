@extends('layouts.app')

@section('content')
    <h1>Syscom - Gestión de Usuarios</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3"> <i class="fa-solid fa-user-plus"></i> Registrar Usuario</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Cargo</th>
                    <th>Fecha Ingreso</th>
                    <th>Días Trabajados</th>
                    <th>Contrato</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->nombre }}</td>
                        <td>{{ $user->correo_electronico }}</td>
                        <td>{{ $user->rol->nombre_cargo ?? '-' }}</td>
                        <td>{{ $user->fecha_ingreso }}</td>
                        <td>{{ $user->daysWorked() }}</td>
                        <td class="d-flex justify-content-center">
                            @if($user->contrato)
                                <a href="{{ asset($user->contrato) }}" target="_blank" class="btn btn-sm btn-info" title="Ver Contrato">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('users.edit',$user->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            @if($user->fecha_eliminacion == null)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
