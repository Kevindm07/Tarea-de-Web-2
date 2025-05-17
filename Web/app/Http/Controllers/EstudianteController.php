<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Estudiante::with('paralelo')->get();
        $resultado = $student->map(function ($est){
            return [
                'id' => $est->id,
                'name' => $est->name,
                'ci' => $est->ci,
                'correo' => $est->correo,
                'paralelo' => $est->paralelo->nombre ?? null,
            ];
        });
        return response()->json(['resultado'=> $resultado],201);
    }

    public function store(Request $request)
    {
        Log::info('Intentando crear estudiantes:', $request->all());
        $request->validate([
        'name' => 'required|string',
        'ci' => 'required|string|unique:estudiantes,ci',
        'correo' => 'required|email|unique:estudiantes,correo',
        'paralelos_id' => 'required|exists:paralelos,id',
        ]);

        $student= Estudiante::create($request->all());
        Log::info('Estudiante creado con el ID:'. $student->id);
        
        return response()->json([
            'message' => 'Estudiante creado exitosamente',
            'student' => $student
        ],201);
    }

    public function show($id){

        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $estudiante = Estudiante::with('paralelo')->find($id);
        if(!$estudiante){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json([
                'message' => 'Estudiante no encontrado'
            ],420);
        }
        Log::info('Estudiante encontrado con el ID:', ['estudiante' => $estudiante]);
        return response()->json([
            'id' => $estudiante->id,
            'name' => $estudiante->name,
            'ci' => $estudiante->ci,
            'correo' => $estudiante->correo,
            'paralelo' => $estudiante->paralelo->nombre ?? null,
        ]);
    }
   
    public function update(Request $request, $id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $student = Estudiante::find($id);

        if(!$student){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Estudiante no encontrado'], 420);
        }

        $request->validate([
        'name' => 'sometimes|required|string|max:100',
        'ci' => 'sometimes|required|string|max:10|unique:estudiantes,ci,' . $id,
        'correo' => 'sometimes|required|email',
        'paralelos_id' => 'sometimes|required|exists:paralelos,id',
        ]);

        $student->update($request->all());
        Log::info('Datos actualizado:', ['student' => $student]);
        return response()->json([
            'message' => 'Estudiante actualizado correctamente', 
            'student' => $student
        ], 201);
    }

   
    public function destroy($id)
    {
        Log::info('Iniciando solicitud con el id:', ['id' => $id]);
        $student = Estudiante::find($id);

        if(!$student){
            Log::info('Datos no encontrado:', ['id' => $id]);
            return response()->json(['message'=> 'Estudiante no encontrado'],420);
        }

        $student->delete();
        Log::info('Datos eliminado:', ['student' => $student]);

        return response()->json(['message' => 'Estudiante eliminado correctamente'], 201);
    }
}
