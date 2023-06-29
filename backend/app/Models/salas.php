<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salas extends Model
{
    use HasFactory;

    protected $fillable = [ 'nombre_sala','descripcion','cantidad_jugadores'];
    public function salas_adivinanzas()
    {
        return $this->belongsToMany(salas_adivinanzas::class);
    }

    public function puntajes()
    {
        return $this->belongsToMany(Puntajes::class);
    }
}
