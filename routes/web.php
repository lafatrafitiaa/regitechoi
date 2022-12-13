<?php

use App\Http\Controllers\ChatsController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RendezvousController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SiteController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Middleware\AdministrateurAuthorisation;
use App\Http\Middleware\ApiAccessCheck;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('Accueil');
// });
Route::get('/', [SiteController::class, 'index'])->name('/');
Route::get('/Produit-Cat00{idCategorie}', [SiteController::class, 'categorie'])->name('/categorie');
Route::get('/Produit-{idModele}', [SiteController::class, 'modele'])->name('/categ');

Route::get('/Produit/devis-{idProduit}', [SiteController::class, 'devis'])->name('devisProduit');
Route::get('/Produit/devis{idProduit}', [SiteController::class, 'devisGf'])->name('/devisGf');

Route::get('/Produit/devis/i00{idProduit}', [SiteController::class, 'devisInfo'])->name('/devisInfo');
Route::post('/devis-Produit/00{idProduit}', [SiteController::class, 'sendDevisGf'])->name('sendDevisGf');


Route::get('/cart{idProduit}', [SiteController::class, 'cart'])->name('addCart');
Route::get('/cart', [SiteController::class, 'etatCart'])->name('etatCart');
Route::get('/panier/00{idProduit}', [SiteController::class, 'deleteFromCart'])->name('removeFromCart');
Route::get('/produit', [SiteController::class, 'deleteCart'])->name('deleteCart');
Route::get('/panier/modifier-0{idProduit}', [SiteController::class, 'editFromCart'])->name('editCart');
Route::post('/panier/00-{idProduit}', [SiteController::class, 'modifPanier'])->name('modifPanier');


Route::post('/devis-client/010{idProduit}', [SiteController::class, 'devisClient'])->name('devisTemp');
Route::post('/devis-informatique/010{idProduit}', [SiteController::class, 'sendinfo'])->name('/sendInfo');

Route::get("/autreDevis", [SiteController::class, 'goOtherDevis']);
Route::post("/sendAutreDevis", [SiteController::class, 'otherDevis']);

Route::post('/espace-client', [SiteController::class, 'checkPass'])->name('checkPass');
Route::get('/vos-renseignements', [SiteController::class, 'sinscrire'])->name('sinscrire');

Route::post('/registre', [SiteController::class, 'inscription'])->name('inscription');

Route::get('/Produit{idProduit}', [SiteController::class, 'detailProduit'])->name('detailProduit');


Route::get('/espace-client', [SiteController::class, 'login'])->name('espace-client');
Route::post('/recherche', [SiteController::class, 'recherche'])->name('/recherche');

Route::post('/commande', [SiteController::class, 'validCommande'])->name('/validerCommande');

Route::get('/Regitech-contact', [SiteController::class, 'contact'])->name('contact');



Route::post("/mail", [SiteController::class, 'userLogin'])->name('mail');

Route::post("/login" ,[LoginController::class, 'userLogin']);
Route::get("/logout", function(Request $request){
    if($request->session()->has('id')){
        session()->pull('id');
    }
    $request->session()->pull('userlogged');
    $request->session()->pull('utilisateurToken');
    return redirect('/espace-client');
});


Route::middleware([AdministrateurAuthorisation::class])->group(function () {
    Route::get("/crudAjout", [CrudController::class, 'goAjoutProduit']);
    Route::post("/exeCrudAjout", [CrudController::class, 'exeAjoutProduit']);
    Route::get("/listeProduit", [CrudController::class, 'listes']);
    Route::get("/testbe", [CrudController::class, 'goTest']);
    Route::get("/crudProduit-{idProduit}", [CrudController::class, 'detailProduitCrud']);
    Route::get("/deleteProduit-{id}", [CrudController::class, 'deleteProduit']);
    Route::get("/crudModifProduit-{idProduit}", [CrudController::class, 'goModifProduit']);
    Route::post("/exeModifProduit-{idProduit}", [CrudController::class, 'exeModifProduit']);
});

Route::middleware([ApiAccessCheck::class])->group(function () {

    Route::post("api/v1/ajoutMessage", [ChatsController::class, 'saveMessage']);
    Route::get("api/v1/testpusher", [ChatsController::class, 'index']);
    Route::get("api/v1/sendMessage", [ChatsController::class, 'sendMessage']);

    Route::get("api/v1/message", [ChatsController::class, 'getMessages']);
    Route::get("api/v1/listeMessage", [ChatsController::class, 'getListMessage']);
    Route::get("api/v1/messageAdmin", [ChatsController::class, 'getMessagesAdmin']);
    Route::post("api/v1/ajoutMessageAdmin", [ChatsController::class, 'saveMessageAdmin']);
    Route::get("api/v1/getclient", [ChatsController::class, 'getClient']);



    Route::get("api/v1/notif/commande", [NotificationController::class, 'getNotificationSpeficique']);
    Route::get("api/v1/notif/produit", [NotificationController::class, 'getProduitDetail']);
    Route::post("api/v1/notif/valider", [NotificationController::class, 'validateProduit']);
    Route::post("api/v1/notif/annuler", [NotificationController::class, 'cancelProduit']);
    Route::post("api/v1/notif/confirmerDevis", [NotificationController::class, 'confirmerDevis']);

    Route::get('api/v1/rdv/notif', [RendezvousController::class, 'getNotifRdv']);
    Route::get("api/v1/rdv/allRdv", [RendezvousController::class, 'getAllRdv']);
    Route::get("api/v1/rdv/rendezvous", [RendezvousController::class, 'rendezvousDetail']);
    Route::get("api/v1/rdv/rdvDate", [RendezvousController::class, 'rendezvousParDate']);
    Route::post("api/v1/rdv/actionBtn", [RendezvousController::class, 'action']);
    Route::get("api/v1/rdv/controleduplicata", [RendezvousController::class, 'getdoublerdv']);
    Route::post("api/v1/rdv/report", [RendezvousController::class, 'reportRdv']);

    Route::get("api/v1/statProduitsprix", [StatistiqueController::class, 'prixProduit']);
    Route::get("api/v1/statCommandeproduit", [StatistiqueController::class, 'commandeProduit']);
});


Route::get("/formulaireRendezvous", [RendezvousController::class, 'formulaireRendezvous']);
Route::post("/ajoutRdv", [RendezvousController::class, 'ajoutRdv']);

Route::post("/pusher/auth", [ChatsController::class, 'getToken']);


Route::post("log/login", [LoginController::class, 'loginApk']);
Route::get("log/token", [LoginController::class, 'getTokenApk']);


//Route::get("/loginpage", [LoginController::class, 'index']);



// Route::group(['middleware' => ['web']], function(Request $request){
//     $role = $request->session()->get('role');
//     if($role != 2) return redirect('/');

// });
