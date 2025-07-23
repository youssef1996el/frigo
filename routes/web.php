<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\CaisseSortieController;
use App\Http\Controllers\PrintBonSoriteController;
use App\Http\Controllers\ListOriginController;
use App\Http\Controllers\MarchandiseEntreeController;
use App\Http\Controllers\MarchandiseSortieController;
use App\Http\Controllers\RetourCaisseController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\StituationController;
use App\Http\Controllers\VenteMarchandiseController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\FrigoController;
use App\Http\Controllers\FermeController;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ComptabiliteController;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::group(['middleware' => ['web','auth']], function ()
{
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'products' => ProductController::class,
    ]);
    Route::post('createuser',[UserController::class,'store'])->name('createuser');



    /****************************************************** Company ********************************************/

    Route::get('company'                  ,[CompanyController::class,'index'                      ]);
    Route::post('AddCompany'              ,[CompanyController::class,'store'                      ]);
    Route::post('UpdateCompany'           ,[CompanyController::class,'Update'                     ]);
    Route::post('SaveClientByCompany'     ,[CompanyController::class,'SaveClientByCompany'        ]);
    Route::post('SaveLivreurByCompany'    ,[CompanyController::class,'SaveLivreurByCompany'       ]);
    Route::post('SaveProductByCompany'    ,[CompanyController::class,'SaveProductByCompany'       ]);
    Route::get('DisplayClientBycompany'   ,[CompanyController::class,'DisplayClientBycompany'     ]);
    Route::get('DisplayLivreurBycompany'  ,[CompanyController::class,'DisplayLivreurBycompany'    ]);
    Route::get('DisplayProductBycompany'  ,[CompanyController::class,'DisplayProductBycompany'    ]);
    Route::get('CheckClientCanDelete'     ,[CompanyController::class,'CheckClientCanDelete'       ]);
    Route::get('CheckLivreurCanDelete'    ,[CompanyController::class,'CheckLivreurCanDelete'      ]);
    Route::get('CheckProductCanDelete'    ,[CompanyController::class,'CheckProductCanDelete'      ]);
    /****************************************************** End Company ********************************************/

    /****************************************************** Client ********************************************/

    Route::get('client'                  ,[ClientController::class,'index'                      ]);
    Route::post('AddClient'              ,[ClientController::class,'store'                      ]);
    Route::post('UpdateClient'           ,[ClientController::class,'Update'                     ]);
    Route::get('DisplayContract'         ,[ClientController::class,'DisplayContract'            ]);
    Route::get('DisplayCIN'              ,[ClientController::class,'DisplayCIN'                 ]);
    Route::get('FicheClient/{id}'        ,[ClientController::class,'getByCompanie'              ]);
    Route::get('getByCompanie/{id}'      ,[ClientController::class,'getByCompanie'              ]);
    Route::get('GetNomberCaisseByClient' ,[ClientController::class,'GetNomberCaisseByClient'    ]);
    Route::post('/contracts/upload'      , [ClientController::class, 'upload'])->name('contracts.upload');

    /****************************************************** End Client ********************************************/



    /****************************************************** livreur ********************************************/

    Route::get('livreur'                  ,[LivreurController::class,'index'                      ]);
    Route::post('Addlivreur'              ,[LivreurController::class,'store'                      ]);
    Route::post('UpdateLivreurs'          ,[LivreurController::class,'Update'                     ]);
    Route::post('deleteLivreur'           ,[LivreurController::class,'delete'                     ]);
    
    /****************************************************** End livreurs ****************************************/


    /****************************************************** Caisse Sortie ********************************************/
    Route::get('caissesortie'                 ,[CaisseSortieController::class,'index'             ]);
    Route::post('AddCaisseVides'              ,[CaisseSortieController::class,'store'             ]);
    Route::post('UpdateCaisseVide'            ,[CaisseSortieController::class,'update'            ]);
    Route::post('DeleteCaisseVide'            ,[CaisseSortieController::class,'destroy'           ]);
    Route::post('SaveprintBonCaisseVide'      ,[CaisseSortieController::class,'SaveprintBonCaisseVide']);


        /****************************************************** Print Caisse Sortie ******************************/
            Route::get('PrintCaisseVide/{id}',[PrintBonSoriteController::class,'printBon']);
        /****************************************************** End PrintCaisse Sortie ****************************/

    /****************************************************** End Caisse Sortie *****************************************/


    /******************************************************* list origins *********************************************/
    Route::get('ListOrigin'                     ,[ListOriginController::class,'index'              ]);
    Route::post('AddProduct'                    ,[ListOriginController::class,'store'              ]);
    Route::post('UpdateProduct'                 ,[ListOriginController::class,'update'             ]);
    Route::post('DeleteProduct'                 ,[ListOriginController::class,'Delete'             ]);
    /*************************************************** End Marchandise entrée ****************************************/


    /***************************************************  Marchandise entrée *******************************************/
    Route::get('MarchandisEntre'                    ,[MarchandiseEntreeController::class,'index'                                ]);
    Route::post('AddProduitInTmpMarchandiseEntree'  ,[MarchandiseEntreeController::class,'storeTmpMarchandiseEntree'            ]);
    Route::get('GetDataTmpMarchandiseEntree'        ,[MarchandiseEntreeController::class,'GetTmpMarchandiseEntreeByUser'        ]);
    Route::get('getNombreBoxByClient'               ,[MarchandiseEntreeController::class,'getNombreBoxByClient'                 ]);
    Route::post('TrashTmpMarchandiseEntreByProduct' ,[MarchandiseEntreeController::class,'TrashTmpMarchandiseEntreByProduct'    ]);
    Route::post('UpdateTmpMarchandiseQuantityURL'   ,[MarchandiseEntreeController::class,'UpdateTmpMarchandiseQuantityURL'      ]);
    Route::post('StoreMarchandiseEntree'            ,[MarchandiseEntreeController::class,'StoreMarchandiseEntree'               ]);
    Route::get('ViewListMarchandiseEntree/{id}'     ,[MarchandiseEntreeController::class,'ViewListMarchandiseEntree'            ]);
    Route::get('PrintMarchandiseEntree/{id}'        ,[MarchandiseEntreeController::class,'PrintMarchandiseEntree'               ]);
    Route::post('DeleteMarchandiseEntree'           ,[MarchandiseEntreeController::class,'destroy'                              ]);
    Route::post('SaveprintBonMarchandiseEntree'     ,[MarchandiseEntreeController::class,'SaveprintBonMarchandiseEntree'        ]);

    /***************************************************  End Marchandise entrée ***************************************/

    /***************************************************  Marchandise sortie *******************************************/
    Route::get('MarchandisSortie'                   ,[MarchandiseSortieController::class,'index'                                ]);
    Route::post('AddProduitInTmpMarchandiseSortie'  ,[MarchandiseSortieController::class,'storeTmpMarchandiseSortie'            ]);
    Route::get('GetDataTmpMarchandiseSortie'        ,[MarchandiseSortieController::class,'GetTmpMarchandiseSortieByUser'        ]);
    Route::get('getNombreBoxByClient'               ,[MarchandiseSortieController::class,'getNombreBoxByClient'                 ]);
    Route::post('TrashTmpMarchandiseSortieByProduct',[MarchandiseSortieController::class,'TrashTmpMarchandiseSortieByProduct'   ]);
    Route::post('UpdateTmpMarchandiseQuantityURL'   ,[MarchandiseSortieController::class,'UpdateTmpMarchandiseQuantityURL'      ]);
    Route::post('StoreMarchandiseSortie'            ,[MarchandiseSortieController::class,'StoreMarchandiseSortie'               ]);
    Route::get('ViewListMarchandiseSortie/{id}'     ,[MarchandiseSortieController::class,'ViewListMarchandiseSortie'            ]);
    Route::get('PrintMarchandiseSortie/{id}'        ,[MarchandiseSortieController::class,'PrintMarchandiseSortie'               ]);
    Route::post('DeleteMarchandiseSortie'           ,[MarchandiseSortieController::class,'destroy'                              ]);
    Route::post('SaveprintBonMarchandiseSortie'     ,[MarchandiseSortieController::class,'SaveprintBonMarchandiseSortie'        ]);
    /***************************************************  End Marchandise sortie ***************************************/


    /****************************************************** Caisse retour ********************************************/
    Route::get('caisseretour'                       ,[RetourCaisseController::class,'index'                                     ]);
    Route::post('AddCaisseRetour'                   ,[RetourCaisseController::class,'store'                                     ]);
    Route::post('UpdateCaisseRetour'                ,[RetourCaisseController::class,'update'                                    ]);
    Route::post('DeleteCaisseRetour'                ,[RetourCaisseController::class,'destroy'                                   ]);
    Route::post('SaveprintBonCaisseRetour'          ,[RetourCaisseController::class,'SaveprintBonCaisseRetour'                                   ]);

    /****************************************************** Print Caisse Sortie ******************************/
            Route::get('PrintCaisseRetour/{id}',[RetourCaisseController::class,'printBon']);
    /****************************************************** End PrintCaisse Sortie ****************************/


    Route::get('Setting',function()
    {
        $CompanyIsActive = Company::where('status',1)->value('name');
        return view('Setting.index')->with('company',$CompanyIsActive);
    });
    /************************************************** Start information ******************************************************/
    Route::get('Info'                                   ,[InfoController::class,'index'                         ]);
    Route::post('AddInformation'                        ,[InfoController::class,'store'                         ]);
    Route::post('UpdateInformation'                     ,[InfoController::class,'Update'                        ]);
    Route::post('DeleteInformation'                     ,[InfoController::class,'Destory'                       ]);
    /************************************************** End information  ******************************************************/

    Route::get('Stitaution'                             ,[StituationController::class,'index'                   ]);
    Route::get('Stituation_SortieCaisseVide'            ,[StituationController::class,'SortieCaisseVide'        ]);
    Route::get('Stituation_MarchandiseEntree'           ,[StituationController::class,'MarchandiseEntree'       ]);
    Route::get('Stituation_MarchandiseSortie'           ,[StituationController::class,'MarchandiseSortie'       ]);
    Route::get('Stituation_SortieCaisseRetour'          ,[StituationController::class,'SortieCaisseRetour'      ]);
    Route::get('Stituation_bilangenrale'                ,[StituationController::class,'bilangenrale'            ]);
    Route::get('showClientSituation'                    ,[StituationController::class,'showClientSituation'     ]);

    /************************************************** Start vente **********************************************************/
    Route::get('Vente'                                  ,[VenteMarchandiseController::class,'index'             ]);
    Route::post('AddVente'                              ,[VenteMarchandiseController::class,'store'             ]);
    /************************************************** End vente **********************************************************/

    /************************************************** Start Charge **********************************************************/
    Route::get('Charge'                                 ,[ChargeController::class,'index'                       ]);
    Route::post('update'                                ,[ChargeController::class,'update'                      ]);
    Route::post('storeCharge'                           ,[ChargeController::class,'store'                       ]);
    Route::post('Destroy'                               ,[ChargeController::class,'Destroy'                     ]);
    /************************************************** End Charge ************************************************************/

    /************************************************** Start Frigo **********************************************************/
    Route::get('Frigo'                                  ,[FrigoController::class,'index'                        ]);
    Route::post('storeFrigo'                            ,[FrigoController::class,'store'                        ]);
    Route::get('/export-frigo'                          ,[FrigoController::class, 'export'])->name('frigo.export');
    Route::get('DeleteFrigo/{id}'                       ,[FrigoController::class,'DeleteFrigo'                  ]);
    /************************************************** End Frigo ************************************************************/

    /************************************************** Start Ferme **********************************************************/
    Route::get('Ferme'                                  ,[FermeController::class,'index'                        ]);
    Route::post('storeFerme'                            ,[FermeController::class,'store'                        ]);
    Route::get('/export-ferme'                          ,[FermeController::class, 'export'])->name('ferme.export');
    Route::get('DeleteFerme/{id}'                       ,[FermeController::class,'DeleteFerme'                  ]);
    /************************************************** End Ferme ************************************************************/

    /*************************************************** Start bons  *********************************************************/
    Route::get('Bons'                                   ,function(){
        $CompanyIsActive = Company::where('status',1)->value('name');
        return view('Bons.index')->with('company',$CompanyIsActive);
    });

    
    /*************************************************** End bons  *********************************************************/

    /**************************************************** Comptabilité *****************************************************/
    Route::get('Comptabilite'                           ,[ComptabiliteController::class,'index'                 ]);
    Route::post('AddComptabilite'                       ,[ComptabiliteController::class,'store'                 ]);
    Route::post('UpdateComptabilite'                    ,[ComptabiliteController::class,'Update'                ]);
    /**************************************************** End Comptabilite *************************************************/

    
    
});