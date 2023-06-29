<?php

namespace App\Http\Controllers\API;
use App\Events\AnswerSubmitted;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class answersubmit extends Controller
{
    public function AnswerSubmitvalidate(Request $request)
    {
        $resp= $request->input('respuesta');
        $user= $request->input('user');
        $roomId=$request->input('roomId');
    
      
       event(new AnswerSubmitted($user,$resp,$roomId));
       return response()->json(['El usuario envio la respuesta# '  =>$resp]);
       
    }
}
