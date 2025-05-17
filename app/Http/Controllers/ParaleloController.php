<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paralelo;
use Illuminate\Support\Facades\Log;

class ParaleloController extends Controller
{
    // Obtener todos los registros
    public function index()
    {
        return Paralelo::all();
    }

    // Guardar un nuevo paralelo
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:paralelos'
        ]);

        $paralelo = Paralelo::create($request->all());

        Log::info('Paralelo creado', ['id' => $paralelo->id]);

        return response()->json([
            'mensaje' => 'Paralelo creado exitosamente',
            'paralelo' => $paralelo
        ], 201);
    }

    // Actualizar un paralelo existente
    public function update(Request $request, $id)
    {
        $paralelo = Paralelo::find($id);

        if (!$paralelo) {
            Log::warning('Intento de actualización fallida: paralelo no encontrado', ['id' => $id]);
            return response()->json(['mensaje' => 'Paralelo no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:100|unique:paralelos,nombre,' . $id
        ]);

        $paralelo->update($request->all());

        Log::info('Paralelo actualizado', ['id' => $paralelo->id]);

        return response()->json([
            'mensaje' => 'Paralelo actualizado exitosamente',
            'paralelo' => $paralelo
        ], 200);
    }

    // Eliminar un paralelo existente
    public function destroy($id)
    {
        $paralelo = Paralelo::find($id);

        if (!$paralelo) {
            Log::warning('Intento de eliminación fallida: paralelo no encontrado', ['id' => $id]);
            return response()->json(['mensaje' => 'Paralelo no encontrado'], 404);
        }

        $paralelo->delete();

        Log::info('Paralelo eliminado', ['id' => $id]);

        return response()->json(['mensaje' => 'Paralelo eliminado exitosamente'], 200);
    }
}
