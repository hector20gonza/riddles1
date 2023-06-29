<?php
namespace App\Http\Controllers\API;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ChatController extends Controller
{
   
    public function sendMessage(Request $request)
    {
        $user = $request->input('user');
        $text = $request->input('text');
        $roomId=$request->input('roomId');

        event(new MessageSent($user, $text, $roomId));
       
        return response()->json(['message de mierda llega al mmaguevo controlador' =>   $text]);
    }
}
