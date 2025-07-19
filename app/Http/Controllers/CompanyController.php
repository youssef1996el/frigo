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
use App\Models\CaisseVide;
use App\Models\marchandise_entree;
use App\Models\marchandise_sortie;
use App\Models\CaisseRetour;


use Illuminate\Support\Facades\Auth;
use App\Models\DisplayByCompany;
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
            'ajouter' => 'nullable|array',
            'ajouter.*' => 'integer',
            'supprimer' => 'nullable|array',
            'supprimer.*' => 'integer',
            'role' => 'required|string'
        ]);


        if (!empty($request->ajouter)) {
            foreach ($request->ajouter as $idpermission) {

                $existsSameRole = DB::table('display_with_company')
                    ->where('idcompany', $request->idcompany)
                    ->where('idpermission', $idpermission)
                    ->where('role', $request->role)
                    ->exists();

                if (!$existsSameRole) {

                    DB::table('display_with_company')->insert([
                        'idcompany' => $request->idcompany,
                        'idpermission' => $idpermission,
                        'role' => $request->role,
                    ]);
                    // update all column idclient in table action
                    $CaisseVide = CaisseVide::where('idclient_tmp','=',$idpermission)->update([
                        'idclient' => $idpermission,
                    ]);


                    $marchandise_entree = marchandise_entree::where('idclient_tmp','=',$idpermission)->update([
                        'idclient' => $idpermission,
                    ]);

                    $marchandise_sortie = marchandise_sortie::where('idclient_tmp','=',$idpermission)->update([
                        'idclient' => $idpermission,
                    ]);

                    $CaisseRetour = CaisseRetour::where('idclient_tmp','=',$idpermission)->update([
                        'idclient' => $idpermission,
                    ]);


                }

            }
        }


        if (!empty($request->supprimer)) {
            DB::table('display_with_company')
                ->where('idcompany', $request->idcompany)
                ->whereIn('idpermission', $request->supprimer)
                ->where('role', $request->role)
                ->delete();
                // update all column idclient in table action
                $CaisseVide = CaisseVide::where('idclient','=',$request->supprimer)->update([
                    'idclient' => null,
                ]);

                $marchandise_entree = marchandise_entree::where('idclient','=',$request->supprimer)->update([
                    'idclient' => null,
                ]);

                $marchandise_sortie = marchandise_sortie::where('idclient','=',$request->supprimer)->update([
                    'idclient' => null,
                ]);

                $CaisseRetour = CaisseRetour::where('idclient','=',$request->supprimer)->update([
                    'idclient' => null,
                ]);


        }

        return response()->json(['success' => true]);


    }

    public function SaveLivreurByCompany(Request $request)
    {

        $request->validate([
            'idcompany' => 'required|integer',
            'ajouter' => 'nullable|array',
            'ajouter.*' => 'integer',
            'supprimer' => 'nullable|array',
            'supprimer.*' => 'integer',
            'role' => 'required|string'
        ]);


        if (!empty($request->ajouter)) {
            foreach ($request->ajouter as $idpermission) {

                $existsSameRole = DB::table('display_with_company')
                    ->where('idcompany', $request->idcompany)
                    ->where('idpermission', $idpermission)
                    ->where('role', $request->role)
                    ->exists();

                if (!$existsSameRole) {

                    DB::table('display_with_company')->insert([
                        'idcompany' => $request->idcompany,
                        'idpermission' => $idpermission,
                        'role' => $request->role,
                    ]);
                }

            }
        }


        if (!empty($request->supprimer)) {
            DB::table('display_with_company')
                ->where('idcompany', $request->idcompany)
                ->whereIn('idpermission', $request->supprimer)
                ->where('role', $request->role)
                ->delete();
        }

        return response()->json(['success' => true]);
    }

    public function SaveProductByCompany(Request $request)
    {
        $request->validate([
            'idcompany' => 'required|integer',
            'ajouter' => 'nullable|array',
            'ajouter.*' => 'integer',
            'supprimer' => 'nullable|array',
            'supprimer.*' => 'integer',
            'role' => 'required|string'
        ]);


        if (!empty($request->ajouter)) {
            foreach ($request->ajouter as $idpermission) {

                $existsSameRole = DB::table('display_with_company')
                    ->where('idcompany', $request->idcompany)
                    ->where('idpermission', $idpermission)
                    ->where('role', $request->role)
                    ->exists();

                if (!$existsSameRole) {

                    DB::table('display_with_company')->insert([
                        'idcompany' => $request->idcompany,
                        'idpermission' => $idpermission,
                        'role' => $request->role,
                    ]);
                }

            }
        }


        if (!empty($request->supprimer)) {
            DB::table('display_with_company')
                ->where('idcompany', $request->idcompany)
                ->whereIn('idpermission', $request->supprimer)
                ->where('role', $request->role)
                ->delete();
        }

        return response()->json(['success' => true]);
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

}
