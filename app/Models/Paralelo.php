<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Estudiante;

class Paralelo extends Model
{
    use HasFactory;

    // Activar la función para poder agregar registros
    protected $fillable = ['nombre'];

    // Activar la función que me permita relacionar con las otras tablas
    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}
