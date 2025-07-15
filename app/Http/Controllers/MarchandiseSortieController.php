<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livreur;
use App\Models\Company;
use App\Models\Client;
use App\Models\CaisseVide;
use App\Models\List_origins;
use App\Models\TmpLigneMarchandiseSortie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\marchandise_sortie;
use App\Models\ligne_marchandise_sortie;
use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Print_Marchandise_Sortie;
use App\Models\Info;
class MarchandiseSortieController extends Controller
{
    public function index(Request $request)
    {
        $checkHasCompany = Company::count();
        if ($checkHasCompany == 0) {
            return view("Error.index")->withErrors("tu n'as pas de compagnie");
        }

        $checkHasClient = Client::count();
        if ($checkHasClient == 0) {
            return view("Error.index")->withErrors("tu n'as pas de client");
        }

        $checkHasLivreur = Livreur::count();
        if ($checkHasLivreur == 0) {
            return view("Error.index")->withErrors("tu n'as pas de livreur");
        }

        $checkHasList_origins = List_origins::count();
        if ($checkHasList_origins == 0) {
            return view("Error.index")->withErrors("tu n'as pas de produit");
        }

        /* $checkHasCaisseVide = CaisseVide::count();
        if ($checkHasCaisseVide == 0) {
            return view("Error.index")->withErrors("tu n'as pas de caisse de vide");
        } */

        $CompanyIsActive = Company::where('status', 1)->value('status');
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

        $checkHasCaisseVides = DB::table('caissevides as c')
            ->join('companys as co', 'co.id', '=', 'c.idcompany')
            ->where('c.idcompany', $IdCompany)
            ->count();

        if ($checkHasCaisseVides == 0) {
            return view("Error.index")->withErrors("tu n'as pas de caisses de vides");
        }

        $CompanyIsActive = Company::where('status', 1)->value('name');
        //$Clients = Client::all();
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

        $List_origins = DB::table('companys as c')
            ->join('display_with_company as d', 'd.idcompany', '=', 'c.id')
            ->join('list_origins as l', 'l.id', '=', 'd.idpermission')
            ->where('c.status', 1)
            ->where('d.role', 'Product')
            ->select('l.name', 'l.id')
            ->get();

        if ($request->ajax()) {
            $CompanyIsActive = Company::where('status', 1)->value('status');
            $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

            $Data = DB::table('marchandise_sortie as m')
                ->join('clients as c', 'm.idclient', '=', 'c.id')
                ->leftJoin('livreurs as l', 'm.idlivreur', '=', 'l.id')
                ->join('users as u', 'm.iduser', '=', 'u.id')
                ->join('companys as co', 'm.idcompany', '=', 'co.id')
                ->leftJoin(DB::raw('(
                    SELECT id_marchandise_sortie, COUNT(*) as total_lignes 
                    FROM ligne_marchandise_sortie 
                    GROUP BY id_marchandise_sortie
                ) as lm'), 'm.id', '=', 'lm.id_marchandise_sortie')
                ->where('co.id', $IdCompany)
                ->select(
                    'm.number_box',
                    'm.cumul',
                    'm.type',
                    DB::raw('CONCAT(c.firstname, " ", c.lastname) as clients'),
                    'l.name',
                    'l.matricule',
                    'm.created_at',
                    'u.name as created',
                    'm.id',
                    'm.clotuer',
                    DB::raw('IFNULL(lm.total_lignes, 0) as total_lignes')
                )
                ->orderBy('m.id', 'desc')
                ->get();


            return DataTables::of($Data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';

                    $btn .= '<a href="' . url("ViewListMarchandiseSortie/" . $row->id) . '"  target="_blank" class="btn btn-sm bg-primary-subtle me-1"
                                data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                title="visualiser">
                                <i class="mdi mdi-eye fs-14 text-primary"></i>
                            </a>';
                    if ($row->total_lignes > 0) 
                    {
                        $btn .= '<a href="' . url("PrintMarchandiseSortie/" . $row->id) . '"   class="btn btn-sm bg-warning-subtle me-1 " 
                                data-id="' . $row->id . '"  
                                title="Imprimer cette bon marchandise sortie"
                                onclick="setTimeout(function(){ window.location.reload(); }, 1000)">
                                <i class="mdi mdi-printer fs-14 text-warning"></i>
                            </a>';
                    }
                    

                    if ($row->clotuer) {
                        $btn .= '<a href="#" class="btn btn-sm bg-info-subtle me-1 ">
                                    <i class="mdi mdi-check-decagram fs-14 text-primary"></i>
                                </a>';
                    }
                    if(!$row->clotuer)
                    {
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteMarchandiseSortie"
                                data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                title="Supprimer cette bon sortie">
                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                            </a>';
                    }
                    

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('Marchandise_Sortie.index')
            ->with('company', $CompanyIsActive)
            ->with('Clients', $Clients)
            ->with('List_origins', $List_origins)
            ->with('Livreurs', $Livreurs);
    }

    public function SaveprintBonMarchandiseSortie(Request $request)
    {
        //get company is active
        $CompanyIsActive = Company::where('status',1)->value('status');
        // get id company is status = 1 
        $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');

        $Print_Marchandise_Sortie = Print_Marchandise_Sortie::create([
            'number_bon'       => $request->number_bon,
            'idmarchandise_sortie'     =>null,
            'idcompany'        => $IdCompany,
        ]);
        return redirect('Setting');
    }
    public function GetTmpMarchandiseSortieByUser(Request $request)
    {
        if($request->ajax())
        {
            //get company is active
            $CompanyIsActive = Company::where('status',1)->value('status');
            // get id company is status = 1 
            $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');

            $data = DB::table('tmp_ligne_marchandise_sortie as t')
                ->join('list_origins as l', 't.idproduct', '=', 'l.id')
                ->join('users as u', 't.iduser', '=', 'u.id')
                ->join('clients as c', 't.idclient', '=', 'c.id')
                ->join('companys as co', 't.idcompany', '=', 'co.id')
                ->join('livreurs as liv', 't.idlivreur', '=', 'liv.id')
                ->select(
                    'c.id',
                    'liv.cin',
                    'liv.matricule',
                    'liv.name as livreur',
                    'l.name',
                    DB::raw("CONCAT(c.firstname, ' ', c.lastname) as name_client"),
                    DB::raw("SUM(t.quantity) as total_quantity"),
                    DB::raw("MIN(t.id) as idtmp")
                )
                ->where('t.iduser'  ,Auth::user()->id)
                ->where('t.idclient',$request->idclient)
                ->where('co.id'     ,$IdCompany)
                ->groupBy(
                    'c.id', 
                    'liv.cin', 
                    'liv.matricule', 
                    'liv.name', 
                    'l.name', 
                    DB::raw("CONCAT(c.firstname, ' ', c.lastname)")
                )
                ->orderBy('c.id')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '';

                    // زر التعديل
                    $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 edit_tmp_machandise_sortie"
                                data-id="' . $row->idtmp . '"  
                                title="Modifier ligne marchandise sortie">
                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                            </a>';

                    // زر الحذف
                    $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle delete_tmp_machandise_sortie"
                                data-id="' . $row->idtmp . '"  
                                title="Supprimer ligne marchandise sortie">
                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                            </a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function TrashTmpMarchandiseSortieByProduct(Request $request)
    {
        $TmpLigneMarchandiseSortie = TmpLigneMarchandiseSortie::find($request->id);
        
        if ($TmpLigneMarchandiseSortie) {
            $TmpLigneMarchandiseSortie->delete();
            
            return response()->json([
                'status'  => 200,
                'message' => 'Opération réussie avec succès !',
                'idclient'=> $TmpLigneMarchandiseSortie->idclient, 
            ]);
        }

        return response()->json([
            'status'  => 404,
            'message' => 'Élément non trouvé !'
        ]);

        

    }

    public function storeTmpMarchandiseSortie(Request $request)
    {
        

        $validator = Validator::make($request->all(), [
            'quantity' => 'required',
            'idclient' => 'required',
            'idlivreur' => 'required',
            'idproduct' => 'required',
        ], 
        
        [
            'required' => 'Le champ :attribute est requis.'
        ], 

        [
            'quantity'   => 'nombre ',
            'idclient'   => 'client',
            'idlivreur'  => 'livreur',
            'idproduct'  => 'produit',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->idclient == 0) {
                $validator->errors()->add('idclient', 'Le champ client est invalide.');
            }
            
            if ($request->idlivreur == 0) {
                $validator->errors()->add('idlivreur', 'Le champ livreur est invalide.');
            }
            if ($request->idproduct == 0) {
                $validator->errors()->add('idproduct', 'Le champ produit est invalide.');
            }
        });
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        // set request in varibale data

        $data = $request->all();
        //get company is active
        $CompanyIsActive = Company::where('status', 1)->value('status');

        // get id company is status = 1
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');
        $data['iduser'] = Auth::user()->id;
        $data['idcompany'] = $IdCompany;
      
        $TmpLigneMarchandiseSortie = TmpLigneMarchandiseSortie::create([
            'quantity'       => $data['quantity'],
            'idproduct'      => $data['idproduct'],
            'iduser'         => $data['iduser'],
            'idclient'       => $data['idclient'],
            'idcompany'      => $data['idcompany'],
            'idlivreur'      => $data['idlivreur'],
        ]);

        if ($TmpLigneMarchandiseSortie) 
        {
            return response()->json([
                'status'  => 200,
                'message' => 'Information créée avec succès'
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
    

    public function UpdateTmpMarchandiseQuantityURL(Request $request)
    {
        $TmpLigneMarchandiseSortie = TmpLigneMarchandiseSortie::find($request->id);
        
        if ($TmpLigneMarchandiseSortie) {
            // update tmp
            TmpLigneMarchandiseSortie::where('id',$request->id)->update([
                'quantity'    => $request->total_quantity,
            ]);
            
            return response()->json([
                'status'  => 200,
                'message' => 'Opération réussie avec succès !',
                'idclient'=> $TmpLigneMarchandiseSortie->idclient, 
            ]);
        }

        return response()->json([
            'status'  => 404,
            'message' => 'Élément non trouvé !'
        ]);
    }

    public function StoreMarchandiseSortie(Request $request)
    {
        

        // Count how many marchandis_sortie exist
        $marchandise_sortie = DB::table('marchandise_sortie as m')
        ->join('companys as co', 'co.id', 'm.idcompany')
        ->where('co.status', 1)
        ->count();
       
        // get last cumul for this client
        $Lastmarchandise_sortie = DB::table('marchandise_sortie as m')
        ->join('companys as co', 'co.id', 'm.idcompany')
        ->where('co.status', 1)
        ->where('idclient', $request->idclient)
        ->orderBy('m.created_at', 'desc')
        ->select('m.cumul')
        ->first();

        $lastCumul = $Lastmarchandise_sortie ? $Lastmarchandise_sortie->cumul : 0;

        // get active company id
        $CompanyIsActive = Company::where('status', 1)->value('status');
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

        $data['iduser'] = Auth::user()->id;
        $data['idcompany'] = $IdCompany;
        $data['idclient'] = $request->idclient;
        $data['etranger'] = $request->etranger;
        // get temporary marchandises
        $dataTmpMarchandise = TmpLigneMarchandiseSortie::where('idclient', $data['idclient'])
        ->where('iduser', $data['iduser'])
        ->where('idcompany', $data['idcompany'])
        ->get();

        // create new marchandis_entree
        $marchandise_Sortie = marchandise_sortie::create([
            'number_box' => $dataTmpMarchandise->sum('quantity'),
            'cumul'      => $lastCumul + $dataTmpMarchandise->sum('quantity'),
            'clotuer'    => false,
            'etranger'   => $data['etranger'],
            'idclient'   => $dataTmpMarchandise[0]["idclient"],
            'idlivreur'  => $dataTmpMarchandise[0]["idlivreur"],
            'iduser'     => $data['iduser'],
            'idcompany'  => $data['idcompany'],
        ]);

        // loop to insert lignes
        foreach ($dataTmpMarchandise as $value) {
        $ligne_marchandise_sortie = ligne_marchandise_sortie::create([
            'quantity'             => $value->quantity,
            'idproduct'            => $value->idproduct,
            'id_marchandise_sortie' => $marchandise_Sortie->id,
        ]);
        }

        // clean temp data
        if ($ligne_marchandise_sortie) {
            TmpLigneMarchandiseSortie::where('idclient', $data['idclient'])
                ->where('iduser', $data['iduser'])
                ->where('idcompany', $data['idcompany'])
                ->delete();

            return response()->json([
                'status' => 200,
            ]);
        }


    }


    public function ViewListMarchandiseSortie($id)
    {
        $Data = DB::table('marchandise_sortie as m')
        ->join('ligne_marchandise_sortie as l'     , 'l.id_marchandise_sortie' , '=' , 'm.id'      )
        ->join('list_origins     as list'  , 'list.id'            , '=' ,'l.idproduct')
        ->join('clients          as c'     , 'c.id'               , '=' ,'m.idclient' )
        ->join('livreurs         as liv'   , 'm.idlivreur'        , '=' , 'liv.id'    )
        ->select(
            'c.id',
            'liv.cin',
            'liv.matricule',
            'liv.name as livreur',
            'list.name',
            DB::raw("CONCAT(c.firstname, ' ', c.lastname) as name_client"),
            DB::raw("SUM(l.quantity) as total_quantity"),
            DB::raw("MIN(l.id) as idligne")
        )
        ->where('m.id',$id)
        ->groupBy(
            'c.id', 
            'liv.cin', 
            'liv.matricule', 
            'liv.name', 
            'list.name', 
            DB::raw("CONCAT(c.firstname, ' ', c.lastname)")
        )
        ->orderBy('c.id')
        ->get();
        //dd($Data);
        $CompanyIsActive = Company::where('status',1)->value('name');
        $IdClient         = marchandise_sortie::where('id',$id)->value('idclient');
        $Clients          = Client::where('id',$IdClient)->first();
        return view('Marchandise_Sortie.list')
        ->with('Data',$Data)
        ->with('id',$id)
        ->with('Clients',$Clients)
        ->with('company',$CompanyIsActive);
        

    }

    public function PrintMarchandiseSortie($id)
    {
         $Check_Info = Info::count();
        if($Check_Info == 0)
        {
            return redirect('MarchandisSortie')->with('ErrorsInfos','Veuillez insérer les informations de l\'entreprise');
            
        }
        $Check_Print_Marchandise_Sortie = Print_Marchandise_Sortie::count();
        if($Check_Print_Marchandise_Sortie == 0)
        {
            return redirect('MarchandisSortie')->with('ErrorsNumberStartBon','Veuillez choisir le numéro que vous souhaitez strat bon caisse vide');
        }
        // Get the active company status
        $CompanyIsActive = Company::where('status', 1)->value('status');
        
        // Get the company ID where status = 1
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');
        
        // Check if a record already exists for this company and caissevide ID
        $existingRecord = DB::table('print_marchandise_sortie')
            ->where('idcompany', $IdCompany)
            ->where('idmarchandise_sortie', $id)
            ->first();
            
        if (!$existingRecord) {
            // Get last number_bon for the company, or default to 0 if none found
            $lastRecord = DB::table('print_marchandise_sortie')
                ->where('idcompany', $IdCompany)
                ->orderByDesc('id')
                ->first();
               
            if($lastRecord->number_bon && $lastRecord->idmarchandise_sortie == null)
            {
                $newNumberBon = 1;
                Print_Marchandise_Sortie::where('id',$lastRecord->id)->update([
                    'idmarchandise_sortie' =>$id
                ]);
            }
            else
            {
                $newNumberBon = $lastRecord ? $lastRecord->number_bon + 1 : 1;
                    Print_Marchandise_Sortie::create([
                    'number_bon' => $newNumberBon,
                    'print_marchandise_sortie' => $id,
                    'idcompany' => $IdCompany
                ]);
            }
        }
        try {
            $Data = DB::table('marchandise_sortie as m')
                ->join('ligne_marchandise_sortie as l', 'l.id_marchandise_sortie', '=', 'm.id')
                ->join('list_origins as list', 'list.id', '=', 'l.idproduct')
                ->join('clients as c', 'c.id', '=', 'm.idclient')
                ->leftjoin('livreurs as liv', 'm.idlivreur', '=', 'liv.id')
                ->select(
                    'c.id',
                    'liv.cin',
                    'liv.matricule',
                    'liv.name as livreur',
                    'list.name',
                    'm.etranger',
                    DB::raw("CONCAT(c.firstname, ' ', c.lastname) as name_client"),
                    DB::raw("SUM(l.quantity) as total_quantity"),
                    DB::raw("MIN(l.id) as idligne"),
                    DB::raw("(SELECT cumul FROM marchandise_sortie WHERE id = $id) as cumul")
                )
                ->where('m.id', $id)
                ->groupBy(
                    'c.id',
                    'liv.cin',
                    'liv.matricule',
                    'liv.name',
                    'list.name',
                    DB::raw("CONCAT(c.firstname, ' ', c.lastname)")
                )
                ->orderBy('c.id')
                ->get();
                
            if(is_null($Data[0]->livreur))
            {
                
                $data = DB::table("marchandise_sortie as m")
                ->join('ventes as v'        ,'v.id'     ,'='    ,'m.idvente')
                ->join('clients as cl'      ,'cl.id'    ,'='    ,'v.achteur')
                ->join('clients as clv'     ,'clv.id'   ,'='    ,'v.vendeur')
                ->join('list_origins as l'  ,'l.id'     ,'='    ,'v.idproduct')
                ->select(DB::raw('concat(clv.firstname," ",clv.lastname) as vendeur'),DB::raw('concat(cl.firstname," ",cl.lastname) as achteur'),
                'l.name as product','m.number_box','m.created_at')
                ->where('m.id',$id)
                ->get();
            }
                    
            if($Data)
            {
                $marchandise_sortie = marchandise_sortie::find($id);
                if($marchandise_sortie)
                {
                    $marchandise_sortie->update([
                        'clotuer'   => true,
                    ]);
                }
            }

            $userPrintBon = Auth::user()->name;
            $DatePrintBon = Carbon::now();
            $DatePrintBon->format('d-m-Y');
            $TimePrintBon = $DatePrintBon->format('H:i');
            
            $Extract_number_bon = DB::table('print_marchandise_sortie')
                ->where('idcompany', $IdCompany)
                ->where('idmarchandise_sortie', $id)
                ->orderByDesc('id')
                ->value('number_bon');
             
             $Client = DB::table('marchandise_sortie as m')
                                ->join('clients as co'  , 'co.id'  , 'm.idclient')
                                ->where('m.id',$id)
                                ->select('co.firstname', 'co.lastname', DB::raw('DATE(m.created_at) as created_date'))
                                ->first();

            $Info = Info::all();
            if(isset($Data[0]->livreur))
            {
                $html = view('PrintSortieMarchandise.index', compact('Data','userPrintBon','DatePrintBon','TimePrintBon', 'Extract_number_bon','Client','Info'))->render();
            }
            else
            {
                $Vendeur =  $data[0]->vendeur;
                $Achteur =  $data[0]->achteur;
                
                 $title = "BON SORTIE DE MARCHANDISE";
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
            return $mpdf->Output("Bon_Sorite_Marchandise_$id.pdf", 'I'); // I = عرض | D = تحميل | F = تخزين

        } catch (\Exception $e) 
        {
            return dd($e->getMessage());
        }
    }


    public function destroy(Request $request) 
    {
        

        try 
        {
            $id = $request->id; 
            // Get the row to be deleted
            $Sortie = DB::table('marchandise_sortie')->where('id', $id)->first();

            if (!$Sortie) {
                return response()->json(['message' => 'Sortie not found'], 404);
            }

            // Save client and company IDs before deleting
            $idclient = $Sortie->idclient;
            $idcompany = $Sortie->idcompany;

            DB::table('ligne_marchandise_sortie')->where('id_marchandise_sortie',$id)->delete();
            // Delete the Sortie
            DB::table('marchandise_sortie')->where('id', $id)->delete();

            // Recalculate cumul for all remaining rows of the same client and company
            $rows = DB::table('marchandise_sortie')
                ->where('idclient', $idclient)
                ->where('idcompany', $idcompany)
                ->orderBy('id') // Or use 'created_at' if preferred
                ->get();

            $cumul = 0;

            foreach ($rows as $row) {
                $cumul += $row->number_box;

                DB::table('marchandise_sortie')
                    ->where('id', $row->id)
                    ->update(['cumul' => $cumul]);
            }

            return response()->json(['status' => 200, 'message' => 'Suppression avec succès']);        
            
        } 
        catch (\Exception $e) 
        {
                return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


}
