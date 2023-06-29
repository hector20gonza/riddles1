<?php

namespace App\Http\Controllers\API;
use App\Events\AdivinaXsala;
use App\Events\JoinRoom1;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Broadcast;

class GameController extends Controller
{
    public function joinGameRoom(Request $request)
        {
            $user= $request->input('user');
            $roomId=$request->input('roomId');
            broadcast(new JoinRoom1($user, $roomId));
        }
    
        public function adivinanzasPorSala(Request $request)
        {
           $salaId=$request->input('roomId');
          
           $adivinanzas = DB::table('adivinanzas')
           ->join('sala_adivinanzas', 'adivinanzas.id', '=', 'sala_adivinanzas.adivinanza_id')
           ->select('adivinanzas.id','adivinanzas.pregunta','adivinanzas.respuesta')
           ->where('sala_adivinanzas.sala_id', '=', $salaId)
           ->get();
           
           if ($adivinanzas->isEmpty()) {
               return response()->json(['error' => 'No se encontraron adivinanzas para la sala especificada'], 404);
           } else {
   
               event(new AdivinaXsala($adivinanzas, $salaId));
              
               
           }
        }

    
}
