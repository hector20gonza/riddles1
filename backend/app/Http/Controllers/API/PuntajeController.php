<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\salas;
use App\Models\puntajes;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class PuntajeController extends Controller
{

   public function puntajes(Request $request){
       $id= $request->input('users_id');
       $puntajes = puntajes::where('usuario_id', $id)->sum('puntos');
       return $puntajes;
   }
}
