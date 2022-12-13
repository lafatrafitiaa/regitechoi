<?php

namespace App\Http\Middleware;

use App\Models\Tokenapk;
use Closure;
use Illuminate\Http\Request;

class ApiAccessCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if(!$request->session()->has('id')){
        //     return response()->json(array("status"=> 401, "messageError"=>"Veuillez d'abord vous connecter."), 401);
        // }
        // try {
        //     $bearer = null;
        //     // dd($request->header());
        //     if ($request->session()->has("utilisateurToken") && $request->session()->has("userlogged")){
        //         return $next($request);
        //     } else if ($request->hasHeader("HTTP_AUTHORIZATION")){
        //         $bearer = str_replace("Bearer ", "", $request->header("HTTP_AUTHORIZATION")[0]);
        //     } else if ($request->hasHeader("authorization")){
        //         $bearer = str_replace("Bearer ", "", $request->header("authorization")[0]);
        //     } else if ($request->hasHeader("Authorization")){
        //         $bearer = str_replace("Bearer ", "", $request->header("Authorization")[0]);
        //     } else {
        //         return response()->json(array("status"=> 400, "messageError"=>"Des informations importantes sont manquantes."), 400);
        //     }
        //     // dd($bearer);
        //     if ($bearer !== null) {
        //         $tokenapk = Tokenapk::where('token', $bearer)->get();
        //         // dd($tokenapk);
        //         // echo $tokenapk;
        //         if(count($tokenapk) == 0){
        //             return response()->json(array("status"=> 401, "messageError"=>"Veuillez d'abord vous connecter."), 401);
        //         }
        //         return $next($request);
        //     } else {
        //         return response()->json(array("status"=> 400, "messageError"=>"Des informations importantes sont manquantes."), 400);
        //     }
        // } catch (\Throwable $th) {
        //     return response()->json(array(
        //         "status" => 500,
        //         "messageType" => "error",
        //         "messageError" => "Une erreur s'est produite, veuillez recommancer ultérieurement.",
        //         "messageNow" => $th->getMessage(),
        //         "where" => "ApiCheck"
        //     ), 500);
        // }
        return $next($request);

    }
}
