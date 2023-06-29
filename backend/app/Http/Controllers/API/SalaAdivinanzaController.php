<?php

namespace App\Http\Controllers\API;
use App\Events\AdivinaXsala;
use App\Http\Controllers\Controller;
use App\Models\sala_adivinanzas;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\DB;
class SalaAdivinanzaController extends Controller
{
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

          //  event(new AdivinaXsala($adivinanzas, $salaId));
           
            return response()->json($adivinanzas->all());
        }
     }
    
    public function store(Request $request)
    {
       $adivinanzas = new sala_adivinanzas();
       $salaId = $request->input('salaId');
       $adivinanzasSeleccionadas = $request->input('adivinanzasSeleccionadas');
        
   
           // Crear nuevos registros para las adivinanzas seleccionadas
           foreach ($adivinanzasSeleccionadas as $adivinanzaId) {
            $adivinanzas::create([
                   'sala_id' => $salaId,
                   'adivinanza_id' => $adivinanzaId,
               ]);
           }
   
           return response()->json(['mensaje' => 'Adivinanzas guardadas correctamente'], 200);
       
    }

    public function destroy(Request $request)
    {
        $id=$request->input('id');
        $salaId=$request->input('sala_id');
        $idregistro = DB::table('sala_adivinanzas')
        ->select('id')
        ->where('sala_id','=',$salaId)
        ->where('adivinanza_id','=',$id)
        ->first(); // Cambiar 'get()' por 'first()' para obtener un solo registro

       if ($idregistro) {
        $adivinanza = sala_adivinanzas::find($idregistro->id); // Acceder al atributo 'id' del objeto $idregistro
        $adivinanza->delete();
    
        return $adivinanza;
     } else {
        return response()->json(['mensaje' => 'Error No se encuentra algun registro que eliminar'], 500);
      }
      }
}
