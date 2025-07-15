<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Client;
use App\Models\Livreur;
use App\Models\CaisseRetour;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use App\Models\Print_Caisse_Retour;
use App\Models\Info;
use Carbon\Carbon;
class RetourCaisseController extends Controller
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
            //get company is active
            $CompanyIsActive = Company::where('status',1)->value('status');
            // get id company is status = 1 
            $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');

            $Data_Caisse_Vide = DB::table('caisse_retour as c')
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
                /* $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editCaisseRetour" 
                            data-id="' . $row->id . '"  
                            title="Modifier caisse de vide" 
                            data-client="' . $row->idcustomer . '" 
                            data-livreur="' . $row->idDelivery . '">
                            <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                        </a>'; */
    
                /* } */

                $btn .= '<a href="' . url("PrintCaisseRetour/" . $row->id) . '"   class="btn btn-sm bg-warning-subtle me-1 " 
                            data-id="' . $row->id . '"  
                            title="Imprimer cette bon caisse retout" 
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
                    $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteCaisseRetour"
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
        return view("CaisseRetour.index")
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
        $Lastcaisse_retour = DB::table('caisse_retour as c')
        ->join('companys as co', 'co.id', 'c.idcompany')
        ->where('co.status', 1)
        ->where('idclient', $request->idclient)
        ->orderBy('c.created_at', 'desc')
        ->select('c.cumul')
        ->first();

        $lastCumul = $Lastcaisse_retour ? $Lastcaisse_retour->cumul : 0;

        // set request in variable
        $data = $request->all();

        // get active company id
        $IdCompany = Company::where('status', 1)->value('id');
        $data['iduser'] = Auth::user()->id;
        $data['idcompany'] = $IdCompany;
        $data['etranger'] = $request->etranger;

        // store CaisseRetours
        $CaisseRetour = CaisseRetour::create([
            'number_box' => $data['number_box'],
            'cumul'      => $lastCumul + $data['number_box'], // فقط تجمع آخر cumul + number_box
            'clotuer'    => false,
            'etranger'   => $data['etranger'],
            'idclient'   => $data['idclient'],
            'idlivreur'  => $data['idlivreur'],
            'iduser'     => $data['iduser'],
            'idcompany'  => $data['idcompany'],
        ]);

        if ($CaisseRetour) {
            return response()->json([
                'status'  => 200,
                'message' => 'Caisse de retour créée avec succès'
            ]);
        } else {
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
       
        $CaisseRetour = CaisseRetour::find($request->id);
        if (!$CaisseRetour) {
            return response()->json([
                'status' => 404,
                'message' => 'Caisse retour non trouvé'
            ]);
        }

        // حفظ البيانات الجديدة
        $CaisseRetour->update([
            'number_box' => $request->number_box,
            'idclient'   => $request->idclient,
            'idlivreur'  => $request->idlivreur,
        ]);

        // تحديث جميع Cumul للزبون القديم إذا تغير idclient
        if ($CaisseRetour->wasChanged('idclient')) {
            // جلب جميع العمليات للزبون القديم
            $OldClientOperations = CaisseRetour::where('idclient', $CaisseRetour->getOriginal('idclient'))
                                            ->where('idcompany', $CaisseRetour->idcompany)
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
        $NewClientOperations = CaisseRetour::where('idclient', $request->idclient)
                                        ->where('idcompany', $CaisseRetour->idcompany)
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
            $id = $request->id; // ID CaisseRetour
            
            $caisse = DB::table('caisse_retour')->where('id', $id)->first();
        
            if (!$caisse) {
                return response()->json(['status' => 404, 'message' => 'Caisse Vide non trouvée']);
            }
        
            $idclient = $caisse->idclient;
            $idcompany = $caisse->idcompany;
        
            // حذف السجل
            DB::table('caisse_retour')->where('id', $id)->delete();
        
            // إعادة حساب الكوميل
            $total_cumul = DB::table('caisse_retour')
                            ->where('idclient', $idclient)
                            ->where('idcompany', $idcompany)
                            ->sum('number_box'); // جمع عدد الصناديق
        
            // تحديث الكوميل في جدول CaisseRetours
            DB::table('caisse_retour')
                ->where('idclient', $idclient)
                ->where('idcompany', $idcompany)
                ->update(['cumul' => $total_cumul]);
        
            return response()->json(['status' => 200, 'message' => 'Suppression avec succès']);
        
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
        
        
        
    }


    public function printBon($id)
    {
        $Check_Info = Info::count();
        if($Check_Info == 0)
        {
            return redirect('caisseretour')->with('ErrorsInfos','Veuillez insérer les informations de l\'entreprise');
            
        }
        $Check_Print_Caisse_Retour = Print_Caisse_Retour::count();
        if($Check_Print_Caisse_Retour == 0)
        {
            return redirect('caisseretour')->with('ErrorsNumberStartBon','Veuillez choisir le numéro que vous souhaitez strat bon caisse vide');
        }
        // Get the active company status
        $CompanyIsActive = Company::where('status', 1)->value('status');
        
        // Get the company ID where status = 1
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');
        
        // Check if a record already exists for this company and caissevide ID
        $existingRecord = DB::table('print_caisse_retour')
            ->where('idcompany', $IdCompany)
            ->where('idcaisseretour', $id)
            ->first();
           
        if (!$existingRecord) {
            // Get last number_bon for the company, or default to 0 if none found
            $lastRecord = DB::table('print_caisse_retour')
                ->where('idcompany', $IdCompany)
                ->orderByDesc('id')
                ->first();
                 
            if($lastRecord->number_bon && $lastRecord->idcaisseretour == null)
            {
                $newNumberBon = 1;
                
                Print_Caisse_Retour::where('id',$lastRecord->id)->update([
                    'idcaisseretour' =>$id
                ]);
                
            }
            else
            {
                $newNumberBon = $lastRecord ? $lastRecord->number_bon + 1 : 1;
                    Print_Caisse_Retour::create([
                    'number_bon' => $newNumberBon,
                    'idcaisseretour' => $id,
                    'idcompany' => $IdCompany
                ]);
            }
        }
        
        try {
            $data = DB::table('caisse_retour as c')
                        ->select('c.number_box','c.cumul',DB::raw("CONCAT(cl.firstname, ' ', cl.lastname) as custommer"),
                            'l.name as name_livreur','l.cin as cin_livreur','l.matricule','c.etranger')
                        ->join('clients as cl', 'c.idclient', '=', 'cl.id')
                        ->leftjoin('livreurs as l', 'c.idlivreur', '=', 'l.id')
                        ->join('users as u', 'c.iduser', '=', 'u.id')
                        ->where('c.id',$id)
                        ->get();
            if(is_null($data[0]->name_livreur))
            {
                $data = DB::table("caisse_retour as c")
                ->join('ventes as v'        ,'v.id'     ,'='    ,'c.idvente')
                ->join('clients as cl'      ,'cl.id'    ,'='    ,'v.achteur')
                ->join('clients as clv'     ,'clv.id'   ,'='    ,'v.vendeur')
                ->join('list_origins as l'  ,'l.id'     ,'='    ,'v.idproduct')
                ->select(DB::raw('concat(clv.firstname," ",clv.lastname) as vendeur'),DB::raw('concat(cl.firstname," ",cl.lastname) as achteur'),
                'l.name as product','c.number_box','c.created_at')
                ->where('c.id',$id)
                ->get();
            }
            if($data)
            {
                $CaisseRetour = CaisseRetour::find($id);
                if($CaisseRetour)
                {
                    $CaisseRetour->update([
                        'clotuer'   => true,
                    ]);
                }
            }
            $DatePrintBon = Carbon::now();
            $DatePrintBon->format('d-m-Y');
            $TimePrintBon = $DatePrintBon->format('H:i');
            $Extract_number_bon = DB::table('print_caisse_retour')
                ->where('idcompany', $IdCompany)
                ->where('idcaisseretour', $id)
                ->orderByDesc('id')
                ->value('number_bon');

             $Client = DB::table('caisse_retour as c')
                                ->join('clients as co'  , 'co.id'  , 'c.idclient')
                                ->where('c.id',$id)
                                ->select('co.firstname', 'co.lastname', DB::raw('DATE(c.created_at) as created_date'))
                                ->first();
            $Info = Info::all();
            if(isset($data[0]->name_livreur))
            {
                $html = view('PrintCaisseRetour.index', compact('data', 'Extract_number_bon','Client','Info','DatePrintBon','TimePrintBon'))->render();
            }
            else
            {
                $Vendeur =  $data[0]->vendeur;
                $Achteur =  $data[0]->achteur;
                 $title = "BON RETOUR CAISSES VIDES ";
                $html = view('Vente.PrintVente',compact('title','data','Achteur','Vendeur','Info','Client','DatePrintBon','TimePrintBon','Extract_number_bon'))->render();
            }
            

            
            $mpdf = new Mpdf([
                'default_font' => 'Amiri', // خاص بالعربية
                'format' => 'A4',
                'mode' => 'utf-8',
                'margin_top' => 10,
                'margin_bottom' => 10,
            ]);

            $mpdf->WriteHTML($html);
            return $mpdf->Output("Bon_Sortie_$id.pdf", 'I'); // I = عرض | D = تحميل | F = تخزين

        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }

    public function SaveprintBonCaisseRetour(Request $request)
    {
        //get company is active
        $CompanyIsActive = Company::where('status',1)->value('status');
        // get id company is status = 1 
        $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');

        $Print_Caisse_Retour = Print_Caisse_Retour::create([
            'number_bon'       => $request->number_bon,
            'idcaisseretour'     =>null,
            'idcompany'        => $IdCompany,
        ]);
        return redirect('Setting');
    }
}
