<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\Models\Client;
use App\Models\Livreur;
use App\Models\List_origins;
use Illuminate\Support\Facades\Auth;
use App\Models\DisplayByCompany;
use App\Models\CaisseVide;
use App\Models\marchandise_entree;
use App\Models\marchandise_sortie;
use App\Models\CaisseRetour;
class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $Data_Company = DB::table("companys as c")
                ->join('users as u', 'u.id', '=', 'c.iduser')
                ->select('u.name as nameuser', 'c.name', DB::raw('if(c.status =1 , "Active","Désactiver") as status'), 'c.id', 'c.created_at');

                return DataTables::of($Data_Company)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $user = auth()->user();
                        $btn = '';

                        // زر التعديل (تحقق من الصلاحية)
                        /* if ($user && $user->can('company-modifier')) { */
                            $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editCompany"
                                        data-id="' . $row->id . '"  
                                        title="Modifier company" data-bs-toggle="modal" data-bs-target="#ModalEditCompany">
                                        <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                    </a>';
                        /* } */

                        // زر الحذف (تحقق من الصلاحية)
                        /* if ($user && $user->can('company-supprimer')) { */
                           /*  $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteCompany"
                                        data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                        title="Supprimer company">
                                        <i class="mdi mdi-delete fs-14 text-danger"></i>
                                    </a>'; */
                        /* } */

                        return $btn;
                    })
                    ->rawColumns(['action']) // تجنب ترميز HTML
                    ->make(true);

            
        }
        
        $Clients = Client::all();
        $Livreurs = Livreur::all();
        $Products = List_origins::all();
        $CompanyIsActive = Company::where('status',1)->value('name');
        $ListCompany = Company::all();
        
                                        

        return view('company.index')
        ->with('company',$CompanyIsActive)
        ->with('Clients',$Clients)
        ->with('Livreurs',$Livreurs)
        ->with('Products',$Products)
        ->with('ListCompany',$ListCompany);
    }

    public function store(Request $request)
    {
        $validator=validator::make($request->all(),[
            'name'                     =>'required',
        ]);
         // Override default error messages
        $customMessages = [
            'required' => 'Le champ :attribute est requis.',
        ];

        $validator->setCustomMessages($customMessages);
        if($validator->fails())
        {
            return response()->json([
                'status'    =>400,
                'errors'    =>$validator->messages(),
            ]);
        }
        else
        {
            
            $data = $request->all();
            $data = array_map('trim', $data);
            $data['name']          = ucfirst(strtolower($request->name));
            $data['iduser']        = Auth::user()->id;
            $checkHasCompany   = Company::count();
            if($checkHasCompany == 0 ) 
            {
                $data['status'] = 1;
            }
            else if($checkHasCompany > 0)
            {
                $check = Company::where('status',1)->count();
                if($check > 0)
                {
                    Company::query()->update(['status' => 0]);
                }
            }
            $Charge = Company::create($data);
            if($Charge)
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'Compagnie créée avec succès',
                ]);
            }
            
        }
    }


    public function Update(Request $request)
    {
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'id'     => 'required|exists:companys,id', 
            'status' => 'required|in:0,1',
        ], [
            'required' => 'Le champ :attribute est requis.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        }

       
        $data = $request->only(['name', 'id', 'status']);
        $data['name'] = ucfirst(strtolower(trim($data['name'])));

        
        $company = DB::table('companys')->where('id', $data['id'])->first();

        if (!$company) {
            return response()->json([
                'status' => 404,
                'message' => 'Compagnie non trouvée.',
            ]);
        }

        if ($data['status'] == 1) {
            
            DB::table('companys')->where('status', 1)->where('id', '!=', $data['id'])->update(['status' => 0]);
        } elseif ($data['status'] == 0 && $company->status == 1) {
            return response()->json([
                'status' => 404,
                'message' => 'Au moins, une compagnie doit être active.',
            ]);
        }

        // Update the company
        DB::table('companys')->where('id', $data['id'])->update([
            'name'   => $data['name'],
            'status' => $data['status'],
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Compagnie modifiée avec succès.',
        ]);
    }

    public function SaveClientByCompany(Request $request)
    {
        $request->validate([
            'idcompany' => 'required|integer',
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'integer',
            'role' => 'required|string'
        ]);

        // Supprimer tous les anciens liens pour cette compagnie et ce rôle
        DB::table('display_with_company')
            ->where('idcompany', $request->idcompany)
            ->where('role', $request->role)
            ->delete();

        // Insérer les nouvelles liaisons (checkbox cochés)
        foreach ($request->selected_ids as $idpermission) {
            DB::table('display_with_company')->insert([
                'idcompany' => $request->idcompany,
                'idpermission' => $idpermission,
                'role' => $request->role,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Les clients ont été mis à jour avec succès.']);
    }

    public function SaveLivreurByCompany(Request $request)
    {
        
        $request->validate([
            'idcompany' => 'required|integer',
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'integer',
            'role' => 'required|string'
        ]);
        
        // Supprimer tous les anciens liens pour cette compagnie et ce rôle
        DB::table('display_with_company')
            ->where('idcompany', $request->idcompany)
            ->where('role', $request->role)
            ->delete();

        // Insérer les nouvelles liaisons (checkbox cochés)
        foreach ($request->selected_ids as $idpermission) {
            DB::table('display_with_company')->insert([
                'idcompany' => $request->idcompany,
                'idpermission' => $idpermission,
                'role' => $request->role,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Les livreurs ont été mis à jour avec succès.']);
    }

    public function SaveProductByCompany(Request $request)
    {
        $request->validate([
            'idcompany' => 'required|integer',
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'integer',
            'role' => 'required|string'
        ]);
        
        // Supprimer tous les anciens liens pour cette compagnie et ce rôle
        DB::table('display_with_company')
            ->where('idcompany', $request->idcompany)
            ->where('role', $request->role)
            ->delete();

        // Insérer les nouvelles liaisons (checkbox cochés)
        foreach ($request->selected_ids as $idpermission) {
            DB::table('display_with_company')->insert([
                'idcompany' => $request->idcompany,
                'idpermission' => $idpermission,
                'role' => $request->role,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Les produits ont été mis à jour avec succès.']);
        
    }


    public function DisplayClientBycompany(Request $request)
    {
        $CompanyIsActive = 0 ;
        if(isset($request["data"]))
        {

            $CompanyIsActive = Company::where('status',1)->value('id');
        }
        else
        {

            $CompanyIsActive = $request->idcompany;
        }
        $ClientByCompany = DB::select('select idpermission from display_with_company where idcompany = ? and role="Client" ',[$CompanyIsActive]);
        
        
        return response()->json([
            'status'      => 200,
            'DataClient'  => $ClientByCompany,
            'IdCompany'   => $CompanyIsActive, 
        ]);

    }

    public function DisplayLivreurBycompany(Request $request)
    {
        $CompanyIsActive = 0 ;
        if(isset($request["data"]))
        {

            $CompanyIsActive = Company::where('status',1)->value('id');
        }
        else
        {

            $CompanyIsActive = $request->idcompany;
        }
        $LivreurByCompany = DB::select('select idpermission from display_with_company where idcompany = ? and role="Livreur" ',[$CompanyIsActive]);
        
        return response()->json([
            'status'      => 200,
            'DataLivreur'  => $LivreurByCompany,
            'IdCompany'   => $CompanyIsActive, 
        ]);

    }

    public function DisplayProductBycompany(Request $request)
    {
        $CompanyIsActive = 0 ;
        if(isset($request["data"]))
        {

            $CompanyIsActive = Company::where('status',1)->value('id');
        }
        else
        {

            $CompanyIsActive = $request->idcompany;
        }
        $ProductByCompany = DB::select('select idpermission from display_with_company where idcompany = ? and role="Product" ',[$CompanyIsActive]);
        
        return response()->json([
            'status'      => 200,
            'DataProduct'  => $ProductByCompany,
            'IdCompany'   => $CompanyIsActive, 
        ]);

    }

    public function CheckClientCanDelete(Request $request)
    {
        $ID_Client = $request->itemID;
        $ID_Company = $request->IdCompany;

        $checks = [
            [
                'model' => \App\Models\CaisseVide::class,
                'message' => 'je ne peux pas supprimer ce client car il est en caisse vide'
            ],
            [
                'model' => \App\Models\marchandise_entree::class,
                'message' => 'je ne peux pas supprimer ce client car il est en marchandises entrée'
            ],
            [
                'model' => \App\Models\marchandise_sortie::class,
                'message' => 'je ne peux pas supprimer ce client car il est en marchandises sortie'
            ],
            [
                'model' => \App\Models\CaisseRetour::class,
                'message' => 'je ne peux pas supprimer ce client car il est en caisse retour'
            ],
        ];

        foreach ($checks as $check) {
            $count = $check['model']::where('idclient', $ID_Client)
                ->where('idcompany', $ID_Company)
                ->count();

            if ($count > 0) {
                return response()->json([
                    'status'  => 404,
                    'message' => $check['message']
                ]);
            }
        }

    }

    public function CheckLivreurCanDelete(Request $request)
    {
        $ID_Livreur = $request->itemID;
        $ID_Company = $request->IdCompany;

        $checks = [
            [
                'model' => \App\Models\CaisseVide::class,
                'message' => 'je ne peux pas supprimer ce livreur car il est en caisse vide'
            ],
            [
                'model' => \App\Models\marchandise_entree::class,
                'message' => 'je ne peux pas supprimer ce livreur car il est en marchandises entrée'
            ],
            [
                'model' => \App\Models\marchandise_sortie::class,
                'message' => 'je ne peux pas supprimer ce livreur car il est en marchandises sortie'
            ],
            [
                'model' => \App\Models\CaisseRetour::class,
                'message' => 'je ne peux pas supprimer ce livreur car il est en caisse retour'
            ],
        ];

        foreach ($checks as $check) {
            $count = $check['model']::where('idlivreur', $ID_Livreur)
                ->where('idcompany', $ID_Company)
                ->count();

            if ($count > 0) {
                return response()->json([
                    'status'  => 404,
                    'message' => $check['message']
                ]);
            }
        }
    }

    public function CheckProductCanDelete(Request $request)
    {
        $ID_Product = $request->itemID;
        $ID_Company = $request->IdCompany;

        $checks = [
            [
                'table'     => 'ligne_marchandis',
                'joinTable' => 'marchandis_entree',
                'foreignKey' => 'ligne_marchandis.id_marchandis_entree',
                'joinKey'   => 'marchandis_entree.id',
                'message'   => 'je ne peux pas supprimer ce produit car il est en marchandises entrée'
            ],
            [
                'table'     => 'ligne_marchandise_sortie',
                'joinTable' => 'marchandise_sortie',
                'foreignKey' => 'ligne_marchandise_sortie.id_marchandise_sortie',
                'joinKey'   => 'marchandise_sortie.id',
                'message'   => 'je ne peux pas supprimer ce produit car il est en marchandises sortie'
            ],
        ];
        
        $check_machandise_entre = DB::select('select count(*) as total from marchandis_entree m , ligne_marchandis l where m.id = l.id_marchandis_entree and l.idproduct = ? and m.idcompany = ?',[$ID_Product,$ID_Company]);
        
        if($check_machandise_entre[0]->total > 0)
        {
            return response()->json([
                'status'  => 404,
                'message' => 'je ne peux pas supprimer ce produit car il est en marchandises entrée'
            ]);
        }
        
        $check_machandise_sortie = DB::select('select count(*) as total from marchandise_sortie m , ligne_marchandise_sortie l where m.id = l.id_marchandise_sortie and l.idproduct = ? and m.idcompany = ?',[$ID_Product,$ID_Company]);

        if($check_machandise_sortie[0]->total > 0)
        {
            return response()->json([
                'status'  => 404,
                'message' => 'je ne peux pas supprimer ce produit car il est en marchandises sortie'
            ]);
        }
    }
    
}
