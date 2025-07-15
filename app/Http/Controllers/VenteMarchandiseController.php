<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Client;
use App\Models\Livreur;
use App\Models\marchandise_sortie;
use App\Models\CaisseRetour;
use App\Models\marchandise_entree;
use App\Models\CaisseVide;
use App\Models\ligne_marchandise_entree;
use App\Models\ligne_marchandise_sortie;
use App\Models\Vente;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class VenteMarchandiseController extends Controller
{
    public function index(Request $request)
    {
        $checkHasCompany = Company::count();
        if( $checkHasCompany == 0)
        {
            return view("Error.index")->withErrors('tu n\'as pas de compagnie ');
        }
        $checkHasClient = Client::count();
        if( $checkHasClient == 0)
        {
            return view("Error.index")->withErrors('tu n\'as pas de client ');
        }
        $checkHasLivreur = Livreur::count();
        if( $checkHasLivreur == 0)
        {
            return view("Error.index")->withErrors('tu n\'as pas de livreur ');
        }

        if($request->ajax())
        {

        }
        if($request->ajax())
        {
             //get company is active
            $CompanyIsActive = Company::where('status',1)->value('status');
            // get id company is status = 1 
            $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');

            $Data_vente = DB::table('ventes as v')
            ->join('clients as acheteur','acheteur.id','=','v.achteur')
            ->join('clients as vendeur','vendeur.id','=','v.vendeur')
            ->join('companys as com','com.id','=','v.idcompany')
            ->join('users as u','u.id','=','v.iduser')
            ->join('livreurs as l','l.id','=','v.idlivreur')
            ->where('v.idcompany',$IdCompany)
            ->select('v.number_box',
            DB::raw('concat(acheteur.firstname," ",acheteur.lastname) as acheteur') ,
            DB::raw('concat(vendeur.firstname," ",vendeur.lastname) as vendeur'),
            'l.name as name_livreur','com.name as name_company','u.name as user_created','v.created_at','v.id')
            ->OrderBy('v.id','desc');
            return DataTables::of($Data_vente)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '';

                // 
                /* if ($user && $user->can('company-modifier')) { */
                $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editCaisseVide" 
                                data-id="' . $row->id . '"   title="Modifier caisse de vide" >
                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                        </a>';
    
                /* } */

                $btn .= '<a href="' . url("PrintCaisseVide/" . $row->id) . '"  target="_blank" class="btn btn-sm bg-warning-subtle me-1 " 
                            data-id="' . $row->id . '"
                            onclick="setTimeout(function(){ window.location.reload(); }, 1000)">
                            <i class="mdi mdi-printer fs-14 text-warning"></i>
                        </a>';

               

                
                /* if ($user && $user->can('company-supprimer')) { */
                    $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteCaisseVide"
                                data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                title="Supprimer cette bon sortie">
                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                            </a>';
                /* } */

                return $btn;
            })
            ->rawColumns(['action']) // تجنب ترميز HTML
            ->make(true);
        }
        $CompanyIsActive = Company::where('status',1)->value('name');
        $Clients         = DB::table('clients as cl')
        ->join('companys as c','c.id','=','cl.idcompany')
        ->join('display_with_company as d','d.idpermission','=','cl.id')
        ->where('c.status',1)
        ->where('d.role','Client')
        ->select('cl.firstname','cl.lastname','cl.id')
        ->get();     
        
        $Livreurs        = DB::table('livreurs as l')
        ->join('companys as c','c.id','=','l.idcompany')
        ->join('display_with_company as d','d.idpermission','=','l.id')
        ->where('c.status',1)
        ->where('d.role','Livreur')
        ->select('l.name','l.id')
        ->get();      

        return view('Vente.index')
        ->with('company',$CompanyIsActive)
        ->with('Clients',$Clients)
        ->with('Livreurs',$Livreurs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number_box' => 'required',
            'achteur' => 'required',
            'vendeur' => 'required',
            
        ], 
        
        [
            'required' => 'Le champ :attribute est requis.'
        ], 

        [
            'number_box' => 'nombre',
            'achteur'   => 'acheteur',
            'vendeur'  => 'vendeur',
        ]);

        
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        
        
        // vendeur sortie marchandises 
        $LastMarchandiseSortie = DB::table('marchandise_sortie as m')
        ->join('companys as co', 'co.id', 'm.idcompany')
        ->where('co.status', 1)
        ->where('idclient', $request->vendeur)
        ->orderBy('m.created_at', 'desc')
        ->select('m.cumul')
        ->first();

        $lastCumul = $LastMarchandiseSortie ? $LastMarchandiseSortie->cumul : 0;

        // set request in variable
        $data = $request->all();
        $IdCompany = Company::where('status', 1)->value('id');
        $data['iduser'] = Auth::user()->id;
        $data['idcompany'] = $IdCompany;
        

       $Vente = Vente::create([
            "number_box"    =>$data['number_box'],
            "achteur"       =>$data['achteur'],
            "vendeur"       =>$data['vendeur'],
            "idproduct"     =>$data['idproduct'],
            "idcompany"     =>$data['idcompany'],
            "iduser"        =>$data['iduser'],
        ]);
       
        // store marchandise sortie
        $marchandise_sortie = marchandise_sortie::create([
            'number_box' => $data['number_box'],
            'cumul'      => $lastCumul + $data['number_box'],
            'etranger'   => null,
            'clotuer'    => false,
            'type'       => "vente",
            'idclient'   => $data['vendeur'],
            'idlivreur'  => null,
            'iduser'     => $data['iduser'],
            'idcompany'  => $data['idcompany'],
            'idvente'    => $Vente->id,
        ]);
        $ligne_marchandise_sortie = ligne_marchandise_sortie::create([
           "quantity"                    =>$data['number_box'],
           "Etranger"                    =>null,
           "id_marchandise_sortie"       =>$marchandise_sortie->id,
           "idproduct"                   =>$data['idproduct'],
        ]);
        
        // vendeur caisse retour
        $LastCaisseRetour = DB::table('caisse_retour as c')
        ->join('companys as co', 'co.id', 'c.idcompany')
        ->where('co.status', 1)
        ->where('idclient', $request->vendeur)
        ->orderBy('c.created_at', 'desc')
        ->select('c.cumul')
        ->first();

        $lastCumul = $LastCaisseRetour ? $LastCaisseRetour->cumul : 0;

        $CaisseRetour = CaisseRetour::create([
            'number_box' => $data['number_box'],
            'cumul'      => $lastCumul + $data['number_box'], // فقط تجمع آخر cumul + number_box
            'etranger'   => null,
            'clotuer'    => false,
            'type'       => "vente",
            'idclient'   => $data['vendeur'],
            'idlivreur'  => null,
            'iduser'     => $data['iduser'],
            'idcompany'  => $data['idcompany'],
            'idvente'    => $Vente->id,
        ]);

        // acheteur marchandise entree

        $LastMarchandiseEntree = DB::table('marchandis_entree as m')
        ->join('companys as co', 'co.id', 'm.idcompany')
        ->where('co.status', 1)
        ->where('idclient', $data['achteur'])
        ->orderBy('m.created_at', 'desc')
        ->select('m.cumul')
        ->first();

        $lastCumul = $LastMarchandiseEntree ? $LastMarchandiseEntree->cumul : 0;
        
        $marchandise_entree = marchandise_entree::create([
            'number_box' => $data['number_box'],
            'cumul'      => $lastCumul + $data['number_box'],
            'clotuer'    => false,
            'etranger'   => null,
            'type'       => "vente",
            'idclient'   => $data['achteur'],
            'idlivreur'  => null,
            'iduser'     => $data['iduser'],
            'idcompany'  => $data['idcompany'],
            'idvente'    => $Vente->id,
        ]);
        $ligne_marchandise_entree = ligne_marchandise_entree::create([
           "quantity"                    =>$data['number_box'],
           "Etranger"                    =>null,
           "id_marchandis_entree"        =>$marchandise_entree->id,
           "idproduct"                   =>$data['idproduct'],
        ]);


        $LastCaisseVide = DB::table('caissevides as c')
        ->join('companys as co', 'co.id', 'c.idcompany')
        ->where('co.status', 1)
        ->where('idclient', $data['achteur'])
        ->orderBy('c.created_at', 'desc')
        ->select('c.cumul')
        ->first();

        $lastCumul = $LastCaisseVide ? $LastCaisseVide->cumul : 0;

        $CaisseVide = CaisseVide::create([
            'number_box' => $data['number_box'],
            'cumul'      => $lastCumul + $data['number_box'], // فقط تجمع آخر cumul + number_box
            'etranger'   => null,
            'clotuer'    => false,
            'type'       => "vente",
            'idclient'   => $data['achteur'],
            'idlivreur'  => null,
            'iduser'     => $data['iduser'],
            'idcompany'  => $data['idcompany'],
            'idvente'    => $Vente->id,
        ]);

        // insert in table vente
        

        if ($Vente) 
        {
            return response()->json([
                'status'  => 200,
                'message' => 'Vente de marchandises créée avec succès'
            ]);
        } 
        else 
        {
            return response()->json([
                'status'  => 500,
                'message' => 'Quelque chose ne va pas'
            ]);
        }
    }
}
