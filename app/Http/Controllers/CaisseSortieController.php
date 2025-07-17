<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Client;
use App\Models\Livreur;
use App\Models\CaisseVide;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Print_Caisse_Vides;
class CaisseSortieController extends Controller
{
    //
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
            //get company is active
            $CompanyIsActive = Company::where('status',1)->value('status');
            // get id company is status = 1
            $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');

            $Data_Caisse_Vide = DB::table('caissevides as c')
                                ->join('clients as co'  , 'co.id'  , 'c.idclient')
                                ->leftJoin('livreurs as l'  , 'l.id'   , 'c.idlivreur')
                                ->join('users as u'     , 'u.id'   , 'c.iduser')
                                ->join('companys as com', 'com.id' , 'c.idcompany')
                                ->where('com.id'       ,'='    ,$IdCompany)
                                ->select(
                                    'c.*','u.name',DB::raw('concat(co.firstname, " ", co.lastname) as client_name'),
                                    'l.cin','l.matricule','l.name as namelivreur','co.id as idcustomer','l.id as idDelivery'
                                )->orderByDesc('c.id');

            return DataTables::of($Data_Caisse_Vide)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '';

                // زر التعديل (تحقق من الصلاحية)
                /* if ($user && $user->can('company-modifier')) { */
               /*  $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editCaisseVide"
                            data-id="' . $row->id . '"
                            title="Modifier caisse de vide"
                            data-client="' . $row->idcustomer . '"
                            data-livreur="' . $row->idDelivery . '">
                            <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                        </a>'; */

                /* } */

                $btn .= '<a href="' . url("PrintCaisseVide/" . $row->id) . '"   class="btn btn-sm bg-warning-subtle me-1 "
                            data-id="' . $row->id . '"
                            title="Imprimer cette bon sortie"
                            data-client="' . $row->idcustomer . '"
                            data-livreur="' . $row->idDelivery . '"
                            onclick="setTimeout(function(){ window.location.reload(); }, 1000)">
                            <i class="mdi mdi-printer fs-14 text-warning"></i>
                        </a>';

                if($row->clotuer)
                {
                    $btn .= '<a href="#"  target="_blank" class="btn btn-sm bg-info-subtle me-1 ">
                                <i class="mdi mdi-check-decagram fs-14 text-primary"></i>
                            </a>';
                }



                if(!$row->clotuer)
                {
                    $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteCaisseVide"
                                data-id="' . $row->id . '" data-bs-toggle="tooltip"
                                title="Supprimer cette bon sortie">
                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                            </a>';
                }
                /* if ($user && $user->can('company-supprimer')) { */

                /* } */

                return $btn;
            })
            ->rawColumns(['action']) // تجنب ترميز HTML
            ->make(true);

        }
        $CompanyIsActive = Company::where('status',1)->value('name');
       $Clients = DB::table('companys as c')
        ->join('display_with_company as d', 'd.idcompany', '=', 'c.id')
        ->join('clients as cl', 'cl.id', '=', 'd.idpermission')
        ->where('c.status', 1)
        ->where('d.role', 'Client')
        ->select('cl.firstname', 'cl.lastname', 'cl.id')
        ->get();

        $Livreurs = DB::table('companys as c')
        ->join('display_with_company as d', 'd.idcompany', '=', 'c.id')
        ->join('livreurs as l', 'l.id', '=', 'd.idpermission')
        ->where('c.status', 1)
        ->where('d.role', 'Livreur')
        ->select('l.name', 'l.id')
        ->get();    //Livreur::all();


        return view("CaisseSortie.index")
        ->with('company',$CompanyIsActive)
        ->with('Clients',$Clients)
        ->with('Livreurs',$Livreurs);
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'number_box' => 'required',
            'idclient' => 'required',
            'idlivreur' => 'required',

        ],

        [
            'required' => 'Le champ :attribute est requis.'
        ],

        [
            'number_box' => 'nombre',
            'idclient'   => 'client',
            'idlivreur'  => 'livreur',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->idclient == 0) {
                $validator->errors()->add('idclient', 'Le champ client est invalide.');
            }

            if ($request->idlivreur == 0) {
                $validator->errors()->add('idlivreur', 'Le champ livreur est invalide.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }



        // get last cumul for this client
        $LastCaisseVide = DB::table('caissevides as c')
        ->join('companys as co', 'co.id', 'c.idcompany')
        ->where('co.status', 1)
        ->where('idclient', $request->idclient)
        ->orderBy('c.created_at', 'desc')
        ->select('c.cumul')
        ->first();

        $lastCumul = $LastCaisseVide ? $LastCaisseVide->cumul : 0;

        // set request in variable
        $data = $request->all();

        // get active company id
        $IdCompany = Company::where('status', 1)->value('id');
        $data['iduser'] = Auth::user()->id;
        $data['idcompany'] = $IdCompany;

        // store caissevides
        $CaisseVide = CaisseVide::create([
            'number_box' => $data['number_box'],
            'cumul'      => $lastCumul + $data['number_box'], // فقط تجمع آخر cumul + number_box
            'etranger'   => $data['etranger'],
            'clotuer'    => false,
            'idclient'   => $data['idclient'],
            'idlivreur'  => $data['idlivreur'],
            'iduser'     => $data['iduser'],
            'idcompany'  => $data['idcompany'],
            'idclient_tmp'=> $data['idclient'],
        ]);

        if ($CaisseVide)
        {
            return response()->json([
                'status'  => 200,
                'message' => 'Caisse de vide créée avec succès'
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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number_box' => 'required',
            'idclient'   => 'required',
            'idlivreur'  => 'required',
        ],
        [
            'required' => 'Le champ :attribute est requis.'
        ],
        [
            'number_box' => 'nombre',
            'idclient'   => 'client',
            'idlivreur'  => 'livreur',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $CaisseVide = CaisseVide::find($request->id);
        if (!$CaisseVide) {
            return response()->json([
                'status' => 404,
                'message' => 'CaisseVide non trouvé'
            ]);
        }

        // حفظ البيانات الجديدة
        $CaisseVide->update([
            'number_box' => $request->number_box,
            'idclient'   => $request->idclient,
            'idlivreur'  => $request->idlivreur,
        ]);

        // تحديث جميع Cumul للزبون القديم إذا تغير idclient
        if ($CaisseVide->wasChanged('idclient')) {
            // جلب جميع العمليات للزبون القديم
            $OldClientOperations = CaisseVide::where('idclient', $CaisseVide->getOriginal('idclient'))
                                            ->where('idcompany', $CaisseVide->idcompany)
                                            ->orderBy('id')
                                            ->get();

            $LastCumul = 0;
            foreach ($OldClientOperations as $operation) {
                $operation->cumul = $LastCumul + $operation->number_box;
                $operation->save();
                $LastCumul = $operation->cumul;
            }
        }

        // تحديث جميع Cumul للزبون الجديد
        $NewClientOperations = CaisseVide::where('idclient', $request->idclient)
                                        ->where('idcompany', $CaisseVide->idcompany)
                                        ->orderBy('id')
                                        ->get();

        $LastCumul = 0;
        foreach ($NewClientOperations as $operation) {
            $operation->cumul = $LastCumul + $operation->number_box;
            $operation->save();
            $LastCumul = $operation->cumul;
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Mise à jour réussie et Cumul recalculé'
        ]);
    }

    public function destroy(Request $request)
    {

        try {
            $id = $request->id; // ID caissevide

            $caisse = DB::table('caissevides')->where('id', $id)->first();

            if (!$caisse) {
                return response()->json(['status' => 404, 'message' => 'Caisse Vide non trouvée']);
            }

            $idclient = $caisse->idclient;
            $idcompany = $caisse->idcompany;

            // حذف السجل
            DB::table('caissevides')->where('id', $id)->delete();

            // إعادة حساب الكوميل
            $total_cumul = DB::table('caissevides')
                            ->where('idclient', $idclient)
                            ->where('idcompany', $idcompany)
                            ->sum('number_box'); // جمع عدد الصناديق

            // تحديث الكوميل في جدول caissevides
            DB::table('caissevides')
                ->where('idclient', $idclient)
                ->where('idcompany', $idcompany)
                ->update(['cumul' => $total_cumul]);

            return response()->json(['status' => 200, 'message' => 'Suppression avec succès']);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }



    }



    public function SaveprintBonCaisseVide(Request $request)
    {
        //get company is active
        $CompanyIsActive = Company::where('status',1)->value('status');
        // get id company is status = 1
        $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');

        $Print_Caisse_Vides = Print_Caisse_Vides::create([
            'number_bon'       => $request->number_bon,
            'idcaissevide'     =>null,
            'idcompany'        => $IdCompany,
        ]);
        return redirect('Setting');
    }


}
