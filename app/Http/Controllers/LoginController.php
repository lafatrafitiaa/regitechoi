<?php

namespace App\Http\Controllers;

use App\Models\Tokenapk;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $redirectTo = '/';

    //
    function userLogin(Request $request){
        if(!isset($request->mail) || !isset($request->mdp) || empty($request->mail) || empty($request->mdp)) {
            $data['email'] = isset($request->mail) ? $request->mail : "";
            $data['errorType'] = "Empty form";
            $data['errorMessage'] = "Veuillez remplir correctement les champs.";
            $data['errorSign'] = "warning";
            return view("login", $data);
        }
        $inputMdp = md5($request->mdp);
        $user = Utilisateur::where('email', "=", $request->mail)->where('pass', '=', $inputMdp)->first();
        if($user){
            $role = $user->iduserrole;
            $idUser = $user->id;
            $mail = $user->email;
            $tel = $user->tel;
            $soc = $user->societe;
            $nom = $user->nom;
            $activite = $user->activite;
            $request->session()->put("userlogged", $user);
            $request->session()->put('id', $idUser);
            $request->session()->put('role', $role);
            $request->session()->put('mail', $mail);
            $request->session()->put('tel', $tel);
            $request->session()->put('soc', $soc);
            $request->session()->put('nom', $nom);
            $request->session()->put('activite', $activite);
            $token =  bin2hex(random_bytes(16));
            $request->session()->put("utilisateurToken", $token);

            $tokenApk = new Tokenapk();
            $tokenApk->idclients = $idUser;
            $tokenApk->token = $token;
            $tokenApk->role = $role;
            $tokenApk->save();

            if($role == 1) return redirect('/');
            elseif($role == 2) return redirect('/listeProduit');
            else return redirect('/');
        }
        else{
            $data['email'] = $request->mail;
            $data['errorType'] = "wrong pass";
            $data['errorMessage'] = "Email ou mot de passe incorect.";
            $data['errorSign'] = "danger";
            return view("login", $data);
        }
    }


    public function index(){
        return view('loginform');
    }


    public function postLogin(Request $request) {
        $request->validate([
            'mail' => 'required',
            'pass' => 'required',
        ]);
        $credentials = $request->only('mail', 'pass');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/')
                             ->withSuccess('You have Successfully loggedin');
        }
        return redirect("loginpage")->withSuccess('Oppes! You have entered invalid credentials');
    }


    public function loginApk(Request $request){
        try {
            if(!isset($request->email) || !isset($request->mdp) || empty($request->email) || empty($request->mdp)) {
                return response()->json(array("status"=> 400, "messageError"=>"Des informations importantes sont manquantes."), 400);
            }
            $mail = $request->email;
            $mdp = md5($request->mdp);

            $user['login'] = Utilisateur::where('email', $mail)
                                ->where('pass', $mdp)
                                ->get();
            $idclient = $user['login'][0]->id;
            $iduserrole = $user['login'][0]->iduserrole;
            $token = new Tokenapk();
            $token->idclients = $idclient;
            $token->token = bin2hex(random_bytes(16));
            $token->role = $iduserrole;
            $token->save();
            if (!$request->session()->has("administrateurToken")){
                $tokenSession = env('ADMINISTRATOR_KEY_CHANNEL', '00a70677-87e1-4762-ab27-f67aeb4230d1');
                $request->session()->put("administrateurToken", $tokenSession);
            }
            $channel = $request->session()->get("administrateurToken");
            $data['id'] = $idclient;
            $data['iduserrole'] = $iduserrole;
            $data['token'] = $token->token;
            $data['email'] = $user['login'][0]->email;
            $data['tel'] = $user['login'][0]->tel;
            $data['channel'] = $channel;
            return response()->json(array("data" => $data, "status" => 200), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                "status" => 500,
                "messageType" => "error",
                "messageError" => "Une erreur s'est produite, veuillez recommancer ultérieurement.",
                "messageNow" => $th->getMessage(),
                "where" => "login"
            ), 200);
        }
    }

    public function getTokenApk(Request $request){
        $idclient = $request->get('idclient');
        $user['token'] = Tokenapk::where('idclients', $idclient)
                            ->get();
        return response()->json($user['token']);
    }

    public function logoutApk(Request $request){
        // $idclient = $request->get('idclient');
        // $token = Tokenapk::where('')
    }

}
