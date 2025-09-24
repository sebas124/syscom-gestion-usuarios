<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para consultar usurios y renderizar la vista
     */
    public function index()
    {
        // Consultar usuarios
        $usuarios = User::with('rol')->orderBy('id','desc')->get();
        return view('users.index', compact('usuarios'));
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para consultar roles y renderizar la vista
     */
    public function create()
    {
        // Consultar roles
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para guardar un nuevo usuario
     * @param Request $request - Datos enviados del formulario
     */
    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'nombre' => 'required|string',
            'correo_electronico' => 'required|email:rfc|regex:/^[\w\.-]+@([\w-]+\.)+[a-zA-Z]{2,}$/|unique:usuarios,correo_electronico',
            'id_rol' => 'required|exists:roles,id',
            'fecha_ingreso' => 'required|date|before_or_equal:today',
            'firma' => 'required|string',
        ],
        [
            'correo_electronico.required' => 'El correo es obligatorio.',
            'correo_electronico.email' => 'Debe ingresar un correo válido.',
            'correo_electronico.regex' => 'Debe tener un dominio válido (ej: .com, .net, .org).',
            'correo_electronico.unique' => 'El correo electrónico ya está registrado.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser mayor a hoy.'
        ]
    );

        // limpiar posible dataurl
        $firma = $request->input('firma');
        if (str_contains($firma, 'base64,')) {
            $firma = explode('base64,', $firma)[1];
        }

        // Armar arreglo para crear usuario
        $usuario = User::create([
            'nombre' => $request->nombre,
            'correo_electronico' => $request->correo_electronico,
            'id_rol' => $request->id_rol,
            'fecha_ingreso' => $request->fecha_ingreso,
            'firma' => $firma,
        ]);

        // Generar PDF del contrato
        $pdf = Pdf::loadView('users.contract', ['usuario' => $usuario]);
        $path = "contratos/contrato_{$usuario->id}.pdf";
        $pdf->save(public_path($path));
        $usuario->update(['contrato' => $path]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para consutar datos de usuario a editar y roles
     * @param int id - id del registro a modificar
     */
    public function edit($id)
    {
        // Consultar usuario por id
        $usuario = User::findOrFail($id);

        // Consultar roles
        $roles = Role::all();

        return view('users.edit', compact('usuario','roles'));
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para actualizar un usuario
     * @param Request $request - Datos enviados del formulario
     * @param int id - id del registro a modificar
     */
    public function update(Request $request, $id)
    {
        // Consultar usuario
        $usuario = User::findOrFail($id);

        // Validar datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo_electronico' => "required|email:rfc|regex:/^[\w\.-]+@([\w-]+\.)+[a-zA-Z]{2,}$/|unique:usuarios,correo_electronico,{$usuario->id}",
            'id_rol' => 'required|exists:roles,id',
            'fecha_ingreso' => 'required|date|before_or_equal:today'
        ],
        [
            'correo_electronico.required' => 'El correo es obligatorio.',
            'correo_electronico.email' => 'Debe ingresar un correo válido.',
            'correo_electronico.regex' => 'Debe tener un dominio válido (ej: .com, .net, .org).',
            'correo_electronico.unique' => 'El correo electrónico ya está registrado.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser mayor a hoy.'
        ]
    );

        // Actualizar
        $usuario->nombre = $request->nombre;
        $usuario->correo_electronico = $request->correo_electronico;
        $usuario->id_rol = $request->id_rol;
        $usuario->fecha_ingreso = $request->fecha_ingreso;

        $usuario->save();


        // Regenerar contrato actualizado
        $pdf = Pdf::loadView('users.contract', ['usuario' => $usuario]);

        // Opcional: borrar el archivo anterior
        if ($usuario->contrato && file_exists(public_path($usuario->contrato))) {
            unlink(public_path($usuario->contrato));
        }

        $nombreArchivo = 'contrato_' . $usuario->id . '.pdf';
        $rutaArchivo = 'contratos/' . $nombreArchivo;

        $pdf->save(public_path($rutaArchivo));

        // Actualizar ruta en BD
        $usuario->contrato = $rutaArchivo;
        $usuario->save();

        return redirect()->route('users.index')->with('success','Usuario actualizado correctamente');
    }

    /**
     * @author Sebastian Reyes
     * @version 1.0.0
     * @internal Metodo usado para eliminar un usuario (Actualizar fecha_eliminación)
     * @param int id - id del registro a modificar/eliminar
     */
    public function destroy($id)
    {
        // Consultar usuario por id
        $usuario = User::findOrFail($id);

        // Actualizar fecha de eliminación
        $usuario->fecha_eliminacion = now()->toDateString();

        // Actualizar
        $usuario->save();
    
        return redirect()->route('users.index')->with('success', 'Usuario marcado como eliminado correctamente');
    }
}
