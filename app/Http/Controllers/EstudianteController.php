<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Log;

class EstudianteController extends Controller
{
    // Obtener todos los estudiantes
    public function index()
    {
        return Estudiante::with('paralelo')->get();
    }

    // Guardar un nuevo estudiante
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'cedula' => 'required|string|unique:estudiantes,cedula',
            'correo' => 'required|email|unique:estudiantes,correo',
            'paralelo_id' => 'required|exists:paralelos,id'
        ]);

        $estudiante = Estudiante::create($request->all());

        Log::info('Estudiante creado', ['id' => $estudiante->id]);

        return response()->json([
            'mensaje' => 'Estudiante creado exitosamente',
            'estudiante' => $estudiante
        ], 201);
    }

    // Mostrar un estudiante
    public function show($id)
    {
        $estudiante = Estudiante::with('paralelo')->find($id);

        if (!$estudiante) {
            return response()->json(['mensaje' => 'Estudiante no encontrado'], 404);
        }

        return $estudiante;
    }

    // Actualizar estudiante
    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::find($id);

        if (!$estudiante) {
            return response()->json(['mensaje' => 'Estudiante no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'cedula' => 'sometimes|required|string|unique:estudiantes,cedula,' . $id,
            'correo' => 'sometimes|required|email|unique:estudiantes,correo,' . $id,
            'paralelo_id' => 'sometimes|required|exists:paralelos,id'
        ]);

        $estudiante->update($request->all());

        Log::info('Estudiante actualizado', ['id' => $estudiante->id]);

        return response()->json(['mensaje' => 'Estudiante actualizado exitosamente', 'estudiante' => $estudiante]);
    }

    // Eliminar estudiante
    public function destroy($id)
    {
        $estudiante = Estudiante::find($id);

        if (!$estudiante) {
            return response()->json(['mensaje' => 'Estudiante no encontrado'], 404);
        }

        $estudiante->delete();

        Log::info('Estudiante eliminado', ['id' => $id]);

        return response()->json(['mensaje' => 'Estudiante eliminado exitosamente']);
    }
}
