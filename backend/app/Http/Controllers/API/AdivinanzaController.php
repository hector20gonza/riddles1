<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\adivinanzas;
use Illuminate\Http\Request;

class AdivinanzaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adivinanza = new adivinanzas();
        return response()->json($adivinanza->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $adivinanza = new adivinanzas();
        $adivinanza->pregunta=$request->pregunta;
        $adivinanza->respuesta=$request->respuesta;
        $adivinanza->save();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
       $id = $request->input('id');
       $adivinanza= adivinanzas::find($id);
       $adivinanza->pregunta=$request->pregunta;
       $adivinanza->respuesta=$request->respuesta;
       $adivinanza->save();
       return $adivinanza;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id=$request->input('id');
        $adivinanza = adivinanzas::find($id);
        $adivinanza->delete();
    
        return  $adivinanza;
    }
}
