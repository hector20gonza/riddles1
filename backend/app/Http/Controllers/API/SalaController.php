<?php

namespace App\Http\Controllers\API;
use App\Events\UsuarioUnidoSala;
use App\Http\Controllers\Controller;
use App\Models\Salas;
use App\Models\User;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sala = new Salas();
        return response()->json($sala->all());
    }
    
    public function store(Request $request)
    {
         $sala = new salas();
         $sala->nombre_sala=$request->nombre_sala;
         $sala->descripcion=$request->descripcion;
         $sala->cantidad_jugadores=$request->cantidad_jugadores;
         $sala->save();
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
       $id = $request->input('id');
       $sala = salas::find($id);
       $sala->nombre_sala=$request->nombre_sala;
       $sala->descripcion=$request->descripcion;
       $sala->cantidad_jugadores=$request->cantidad_jugadores;
       $sala->save();
       return $sala;
     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id=$request->input('id');
        $sala = salas::find($id);
        $sala->delete();
        return $sala;
    }
}
