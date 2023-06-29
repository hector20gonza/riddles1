<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\user;
use App\Models\puntajes;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = new User();
      return response()->json($user->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new user();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password= bcrypt($request->password);
        $user->is_admin=$request->is_admin;
        $user->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $user= User::find($id);
        $user->update($request->all());
        return  $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       
        $user = user::find($request->input('id'));
        $user->delete();
        return $user;      
    }    
}
