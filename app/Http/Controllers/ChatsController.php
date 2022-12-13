<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Lastconversation;
use App\Models\Messages;
use App\Models\Tokenapk;
use App\Models\User;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ChatsController extends Controller
{

    public function __construct()
    {
        if (!Session::has("administrateurToken")){
            $token = env('ADMINISTRATOR_KEY_CHANNEL', '00a70677-87e1-4762-ab27-f67aeb4230d1');
            Session::put("administrateurToken", $token);
        }
    }

    public function index()
    {
        return view('Chat/chat');
    }

    public function fetchMessages(){
        return Messages::with('utilisateur')->get();
    }

    public function sendMessage(Request $request){
        try {
            //code...
            broadcast(new MessageSent('hello world', $request->session()->get('id'), $request->session()->get('utilsateurToken')))->toOthers();
            return ['status' => 'Message Sent!'];
        } catch (\Throwable $th) {
            //throw $th;
            return ['status', 'error'];
        }
    }

    /**
     * Method pour envoyer un message à l'administrateur de la part d'un client
     */
    public function saveMessage(Request $request){
        try {
            $message = new Messages();
            $message->idclientssent = $request->session()->get('id');
            $message->messages = $request->messages;
            $message->save();
            if (!$request->session()->has("administrateurToken")){
                $token = env('ADMINISTRATOR_KEY_CHANNEL', '00a70677-87e1-4762-ab27-f67aeb4230d1');
                $request->session()->put("administrateurToken", $token);
            }
            broadcast(new MessageSent($request->messages, $request->session()->get('id'), $request->session()->get("administrateurToken"), $request->session()->get("utilisateurToken")))->toOthers();
        } catch (\Throwable $th) {
            return response()->json(array(
                "status" => 500,
                "messageType" => "error",
                "messageError" => "Une erreur s'est produite, veuillez recommancer ultérieurement.",
                "messageNow" => $th->getMessage()
            ), 500);
        }
        return response()->json(array(
            "status" => 200,
            "messageType" => "success"
        ), 200);
        // return redirect()->back();
    }

    /**
     * Method pour envoyer un message à un utilisateur client, de la part d'un administrateur
     */
    public function saveMessageAdmin(Request $request){
        try {
            $message = new Messages();
            $message->idclientssent = 1;
            $message->idclientsreceive = $request->idclient;
            $utilisateurToken = $request->utilisateurToken;
            $message->messages = $request->messages;
            $result = $message->save();
            if (!empty($utilisateurToken)) {
                broadcast(new MessageSent($message->messages, $request->idclient, $utilisateurToken, $request->session()->get("administrateurToken")))->toOthers();
            }
            if($result) {
                return response()->json(array(
                    "status" => 200,
                    "messageType" => "success"
                ), 200);
            }
            else {
                return response()->json(array(
                    "status" => 500,
                    "messageType" => "error",
                    "messageError" => "Une erreur s'est produite, veuillez recommancer ultérieurement.",
                ), 500);
            }
        } catch (\Throwable $th) {
            return response()->json(array(
                "status" => 500,
                "messageType" => "error",
                "messageError" => "Une erreur s'est produite, veuillez recommancer ultérieurement.",
            ), 500);
        }


    }

    public function getMessages(Request $request){
        try {
            // dd($request->session());
            if(!$request->session()->has('userlogged')){
                return response()->json(array("status"=> 401, "messageError"=>"Veuillez d'abord vous connecter."), 401);
            }
            // $soffset = $request->get('offset');
            //$offset = 0;
            $id = $request->session()->get('userlogged')->id;
            // $id = $request->get('id');

            // $messages['total'] = DB::table('messages')
            //                         ->select(DB::raw('count(*) as total'))
            //                         ->where('idclientssent', $id)
            //                         ->orWhere('idclientsreceive', $id)
            //                         ->get();

            // //$total = $messages['total'][0];
            // $limite = 4;
            // //$total = intval($messages['total'][0]);
            // // $offset = $messages['total'] - $limite;

            // $offset = intval($messages['total'][0]->total) - $limite;

            //if(!empty($soffset)) $offset = intval($soffset);

            $messages['message'] = Messages::where('idclientssent', $id)
                                    ->orWhere('idclientsreceive', $id)
                                    ->orderBy('dateheurechat', 'asc')
                                    ->get();
                                    // ->limit($limite)
                                    // ->offset($offset)
            //return view('Template', compact('messages'));
            // var_dump($messages);
            //return json_encode($messages['message']);
            return response()->json($messages['message'], 200);
        } catch (\Throwable $th) {
            return response()->json(array("status"=> 500, "messageError"=>"Une erreur s'est produite, veuillez réessayer ultérieurement."), 500);
        }

    }

    public function getListMessage(){
        $liste['message'] = Lastconversation::get();
        return response()->json($liste['message']);
    }

    public function getMessagesAdmin(Request $request){
        $idclient = $request->get('idclient');
        // $soffset = $request->get('offset');
        // $offset = 0;

        // $messages['total'] = DB::table('messages')
        //                         ->select(DB::raw('count(*) as total'))
        //                         ->where('idclientssent', $idclient)
        //                         ->orWhere('idclientsreceive', $idclient)
        //                         ->get();

        // $limite = 4;

        // $offset = intval($messages['total'][0]->total) - $limite;

        //if(!empty($soffset)) $offset = intval($soffset);

        $messages['message'] = Messages::where('idclientssent', $idclient)
                                ->orWhere('idclientsreceive', $idclient)
                                ->orderBy('dateheurechat', 'asc')
                                ->get();

        $tokenapk = Tokenapk::where('idclients', $idclient)->first();
        $data = array();
        $data['data']['messages'] = $messages['message'];
        $data['data']['utilisateurToken'] = $tokenapk != null ? $tokenapk->token : "";
        return response()->json($data, 200);
    }

    public function getClient(Request $request){
        $idclient = $request->get('idclient');
        $client['aboutclient'] = Utilisateur::where('id', $idclient)
                                    ->get();
        return response()->json($client['aboutclient']);
    }

    public function getToken(){

    }

}
