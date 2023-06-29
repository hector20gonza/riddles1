<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class puntajes extends Model
{
    use HasFactory;
    protected $fillable=['usuario_id','sala_id','puntos'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function sala()
    {
        return $this->belongsToMany(salas::class);
    }
}