<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adivinanzas extends Model
{
    use HasFactory;
    protected $fillable=['pregunta','respuesta'];
    public function salas_adivinanzas()
    {
        return $this->belongsToMany(salas_adivinanzas::class);
    }
}
