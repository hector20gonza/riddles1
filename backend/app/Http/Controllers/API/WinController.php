<?php

namespace App\Http\Controllers\api;

use App\Events\winneroom;
use App\Events\winnerroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\puntajes;
use Illuminate\Support\Facades\Cache;

class WinController extends Controller
{
    public function WinnerGameRoom(Request $request)
    {
        $usuarios = $request->input('usuarios');
        $roomId = $request->input('roomId');
    
      
     // Número esperado de usuarios en la sala (puede ser configurable)
        $totalEsperado = intval($request->input('totaluser'));
        // Obtener la clave de caché para almacenar los datos de la sala
        $cacheKey = "sala_$roomId";
    
        // Obtener los datos almacenados en caché (si existen)
        $salaData = Cache::get($cacheKey, []);
    
        // Agregar los datos del usuario actual a la sala
        array_push($salaData, $usuarios);
    
        // Guardar los datos de la sala actualizados en caché
        Cache::put($cacheKey, $salaData,30);
    
        // Obtener la cantidad total de usuarios en la sala
        $totalUsuarios = count($salaData);
    
        // Verificar si se han recibido todos los datos de los usuarios
        if ($totalUsuarios === $totalEsperado) {
            // Limpiar los datos de la sala almacenados en caché
            Cache::forget($cacheKey);
    
            // Calcular al ganador
            $ganadores = [];
            $maxPuntaje = 0;
            $minTiempo = PHP_INT_MAX;
    
            foreach ($salaData as $usuarioArray) {
                $usuario = $usuarioArray[0];
    
                $puntajeTotal = 0;
                $tiempoRespuesta = 0;
    
                foreach ($usuario['respuestas'] as $respuesta) {
                    $puntajeTotal += $respuesta['puntos'];
                    $tiempoRespuesta += $respuesta['tiempoRespuesta'];
                }
    
                if ($puntajeTotal > $maxPuntaje) {
                    $ganadores = [$usuario]; // Nuevo máximo puntaje, reiniciar el arreglo de ganadores
                    $maxPuntaje = $puntajeTotal;
                    $minTiempo = $tiempoRespuesta;
                } elseif ($puntajeTotal === $maxPuntaje && $tiempoRespuesta < $minTiempo) {
                    $ganadores = [$usuario]; // Mismo máximo puntaje pero menor tiempo de respuesta, reiniciar el arreglo de ganadores
                    $minTiempo = $tiempoRespuesta;
                } elseif ($puntajeTotal === $maxPuntaje && $tiempoRespuesta === $minTiempo) {
                    $ganadores[] = $usuario; // Mismo máximo puntaje y mismo tiempo de respuesta, agregar al arreglo de ganadores
                }
            }
    
            if (!empty($ganadores)) {
                $ganadorID = $ganadores[0]['id']; // Obtener el ID del primer ganador
                $nombreGanador = $ganadores[0]['name']; // Usar la clave correcta para el nombre del primer ganador
    
                // Guardar los puntos del usuario ganador
                $puntaje = new puntajes();
                $puntaje->usuario_id = $ganadorID;
                $puntaje->sala_id = $roomId;
                $puntaje->puntos = $maxPuntaje;
                $puntaje->save();
            } else {
                $nombreGanador = null; // No se encontró un ganador válido
            }
    
            // Devolver la respuesta con el ganador
            event(new winneroom($nombreGanador, $roomId));
        }
    
        // Enviar una respuesta provisional indicando que se ha recibido el array del usuario
        return response()->json(['message' => 'Array recibido correctamente']);
    }
    


}
