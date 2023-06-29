<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sala_adivinanzas extends Model
{
    use HasFactory;
    protected $fillable=['sala_id','adivinanza_id'];

    public function salas()
    {
        return $this->belongsToMany(salas::class);
    }

    public function adivinanzas()
    {
        return $this->belongsToMany(adivinanzas::class);
    }

}
