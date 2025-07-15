<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage;
use App\Models\Contracts;
use App\Models\CaisseVide;
use Illuminate\Support\Str;
class ClientController extends Controller
{
    public function index(Request $request)
    {
        $checkHasCompany = Company::count();
        if( $checkHasCompany == 0)
        {
            return view("Error.index")->withErrors('tu n\'as pas de compagnie ');
        }
        if($request->ajax()) 
        {
            //get company is active
            $CompanyIsActive = Company::where('status',1)->value('status');
            // get id company is status = 1 
            $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');


           /*  $DataClient = DB::table('clients as c')
                ->join('companys as co','c.idcompany','=','co.id')
                ->join('display_with_company as d','d.idpermission','=','c.id')
                ->join('users as u','u.id','=','c.iduser')
                ->select('c.*','u.name as username')
                ->where('d.role','Client')
                ->where('co.id','=',$IdCompany); */
                $DataClient = DB::table('companys as co')
                ->join('display_with_company as d', 'd.idcompany', '=', 'co.id')
                ->join('clients as c', 'c.id', '=', 'd.idpermission')
                ->join('users as u', 'u.id', '=', 'c.iduser')
                ->select('c.*', 'u.name as username')
                ->where('d.role', 'Client')
                ->where('co.id', $IdCompany)
                ->get();

            return DataTables::of($DataClient)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '';

                // زر التعديل (تحقق من الصلاحية)
                /* if ($user && $user->can('company-modifier')) { */
                    $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editClient"
                                data-id="' . $row->id . '"  
                                title="Modifier client" data-bs-toggle="modal" data-bs-target="#ModalEditClient">
                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                            </a>';

                    $btn .= '<a href="#" class="btn btn-sm bg-warning-subtle me-1 DisplayContract"
                                data-id="' . $row->id . '"   data-company="'.$row->idcompany.'"
                                title="Afficher le contrat ou télécharger un nouveau contrat" >
                                <i class="mdi mdi-id-card fs-14 text-primary"></i>
                            </a>';

                    $btn .= '<a href="' . url("FicheClient/" . $row->id) . '" class="btn btn-sm bg-info-subtle me-1"  target="_blank"  "
                                data-id="' . $row->id . '"  
                                title="Afficher le fiche client">
                                <i class="mdi mdi-list-box fs-14 text-primary"></i>
                            </a>';
                /* } */

                // زر الحذف (تحقق من الصلاحية)
                /* if ($user && $user->can('company-supprimer')) { */
                   /*  $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteCompany"
                                data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                title="Supprimer client">
                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                            </a>'; */
                /* } */

                return $btn;
            })
            ->rawColumns(['action']) // تجنب ترميز HTML
            ->make(true);

            
        }
        // Companys is active 
        $CompanyIsActive = Company::where('status',1)->value('name');
        return view("Client.index")
        ->with('company',$CompanyIsActive);
    }

    public function store(Request $request)
    {
        

        // الحصول على الشركة النشطة
        $CompanyIsActive = Company::where('status', 1)->value('status');
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

        // التحقق مما إذا كان العميل مسجلاً بالفعل في الشركة
        $CheckHasClientInThisCompany = Client::where('idcompany', $IdCompany)
            ->where('firstname', $request->firstname)
            ->where('lastname', $request->lastname)
            ->count();

        if ($CheckHasClientInThisCompany != 0) {
            return response()->json([
                'status' => 404,
                'message' => 'Ce client est déjà créé dans cette compagnie'
            ]);
        }

        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'cin' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'photo_cin.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

         // Override default error messages
        $customMessages = [
            'required' => 'Le champ :attribute est requis.',
        ];
        // Define attribute names in French
        $customAttributes = [
            'firstname'         => 'nom ',
            'lastname'          => 'prénom',
            'cin'               => 'CIN',
            'phone'             => 'téléphone',
            'address'           => 'adresse',
            'photo_cin'         => 'photo c.i.n',
        ];
        $validator->setCustomMessages($customMessages);
        $validator->setAttributeNames($customAttributes);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        // تجهيز البيانات للإدراج في قاعدة البيانات
        $data = $request->all();
        $data['iduser'] = Auth::id();
        $data['idcompany'] = $IdCompany;

        // إنشاء العميل في قاعدة البيانات
        $Client = Client::create($data);

        if ($Client) {
            $storagePath = public_path('image_client/cin_photo');
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0777, true, true);
            }

            $photoPaths = [];

            // رفع صور CIN المتعددة
            if ($request->hasFile('photo_cin')) {
                foreach ($request->file('photo_cin') as $file) {
                    $filename = $Client->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($storagePath, $filename);
                    $photoPaths[] = 'image_client/cin_photo/' . $filename;
                }
            }

            // تحديث حقل الصور في قاعدة البيانات
            $Client->update(['image_cin' => json_encode($photoPaths)]);

            return response()->json([
                'status' => 200,
                'message' => 'Client créée avec succès',
                'photos' => $photoPaths
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Quelque chose ne va pas'
            ]);
        }
    }

    public function Update(Request $request)
    {
        $data = $request->all();
        $data = array_map('trim', $data);
        $validator=validator::make($request->all(),[
            'firstname'                     =>'required',
            'lastname'                      =>'required',
            'cin'                           =>'required',
            'phone'                         =>'required',
            'address'                       =>'required',
        ]);
            // Override default error messages
        $customMessages = [
            'required' => 'Le champ :attribute est requis.',
        ];
        // Define attribute names in French
        $customAttributes = [
            'firstname' => 'nom',
            'lastname'  => 'prénom',
            'cin'       => 'CIN',
            'phone'     => 'téléphone',
            'address'   => 'adresse',
        ];

        $validator->setCustomMessages($customMessages);
        $validator->setAttributeNames($customAttributes);
        if($validator->fails())
        {
            return response()->json([
                'status'    =>400,
                'errors'    =>$validator->messages(),
            ]);
        }
        else
        {
            $Client = Client::where('id', $data['id'])->update([
                'firstname' => $data['firstname'],
                'lastname'  => $data['lastname'],
                'cin'       => $data['cin'],
                'phone'     => $data['phone'],
                'address'   => $data['address'],
            ]);
            

            if($Client)
            {
                return response()->json([
                    'status'    => 200,
                    'message'   => 'Client modifiée avec succès'
                ]);
            }
            else
            {
                return response()->json([
                    'status'     => 500,
                    'message'    => 'Quelque chose ne va pas'
                ]);
                
            }
        }
    }


    public function DisplayContract(Request $request)
    {
        $CompanyIsActive = Company::where('status', 1)->value('status');

        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

        $DataContracts = Contracts::where('idclient'   ,$request->id)
                                  ->where('idcompany'  ,$IdCompany)
                                  ->select('iamge_contract')
                                  ->get();

         $contracts = Contracts::where('idclient', $request->id)
        ->where('idcompany', $IdCompany)
        ->get();

        return response()->json($contracts);

        /* if($DataContracts->isNotEmpty())
        {
            return response()->json([
                'status'      => 200,
                'Data'        => $DataContracts,
            ]);
        }
        else
        {
            return response()->json([
                'status'      => 404,
                'message'     => 'ce client n\'a pas encore de contrat',
            ]);
        } */
    }

    public function DisplayCIN(Request $request)
    {
        $Image_Cin_Client = Client::where('id',$request->id)->value('image_cin');
        
        if (!empty($Image_Cin_Client)) 
        {
            return response()->json([
                'status'     => 200,
                'Data_Image' => json_decode($Image_Cin_Client), // فك تشفير الـ JSON
            ]);
        } 
        else 
        {
            return response()->json([
                'status'  => 400,
                'message' => 'Ce client n\'a pas de photo',
            ]);
        }
    }

     public function getByCompanie($id,Request $request)
    {
        $CompanyIsActive = Company::where('status', 1)->value('status');

        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

        $idCompa = $request->idCompa ?? $IdCompany;

        $idclient = $id ?? $request->idclient ;


        $Comapnie = DB::select("select id from companys where id = ?",[$idCompa]);

        $CaisseVide = DB::select('select sum(number_box) as caisseSortie from caissevides where idclient=? and idcompany =?',[$idclient,$idCompa]);
        $CaisseRetour = DB::select('select  sum(number_box) as caisseRetour from caisse_retour where idclient = ? and idcompany = ?',[$idclient,$idCompa]);
        $reste = (int)($CaisseVide[0]->caisseSortie) - (int)($CaisseRetour[0]->caisseRetour);

        $CaisseEntreMarchandise = DB::select('select sum(number_box) as totalEntree from marchandis_entree where idclient = ? and idcompany = ?',[$idclient,$Comapnie[0]->id]);
        $CaisseSortieMarchandise = DB::select('select sum(number_box) as totalsortie from marchandise_sortie where idclient = ? and idcompany = ?',[$idclient,$Comapnie[0]->id]);
        $resteMarchandise = (int)($CaisseEntreMarchandise[0]->totalEntree) - (int)($CaisseSortieMarchandise[0]->totalsortie);

        $clients = DB::select('select concat(c.firstname," ",c.lastname) as client from clients c where id = ?',[$idclient]);
        $CaisseVidee = DB::select('select date(created_at) as dateCaisseVide,sum(number_box) as caisseVide from caissevides where idclient = ? and idcompany =?
        group by date(created_at)  order by date(created_at)',[$idclient,$idCompa]);

        $MarchandiseEntree = DB::select('select pro.name,sum(m.number_box) as qteentree,date(l.created_at) as date
        from marchandis_entree m ,ligne_marchandis l , list_origins as pro
        where m.id = l.id_marchandis_entree 
        and l.idproduct = pro.id
        
        and m.idclient = ? and m.idcompany = ? group by pro.name,date(l.created_at)  order by date(l.created_at)',[$idclient,$idCompa]);

        $MarchandiseSortiee = DB::select('select sum(l.quantity) as qte,pro.name,date(l.created_at) as date
        from marchandise_sortie m ,ligne_marchandise_sortie l , list_origins as pro
        where m.id = l.id_marchandise_sortie 
        and l.idproduct = pro.id 
        and m.idclient = ? and m.idcompany = ? group by pro.name,date(l.created_at)  order by date(l.created_at)',[$idclient,$idCompa]);

        $caisseRetourr = DB::select('select date(created_at) as dateCaisseRetour,sum(number_box) as caisseRetour from caisse_retour  where idclient = ? and idcompany  =?
        group by date(created_at) order by date(created_at)',[$idclient,$idCompa]);

        $uniqueProductsEntree = array_unique(array_column($MarchandiseEntree, 'name'));
        $uniqueProductsSortie = array_unique(array_column($MarchandiseSortiee, 'name'));

        $ColSpanEntree = DB::select('select pro.name,l.quantity,date(l.created_at) as date
        from marchandis_entree m ,ligne_marchandis l , list_origins as pro
        where m.id = l.id_marchandis_entree 
        and l.idproduct = pro.id 
        and m.idclient= ? and m.idcompany = ? group by pro.name',[$idclient,$idCompa]);

        $NumberColSpanEntre = count($ColSpanEntree);

        $ColSpanSortiee = DB::select('select l.quantity,pro.name,date(l.created_at) as date
        from marchandise_sortie m ,ligne_marchandise_sortie l , list_origins as pro
        where m.id = l.id_marchandise_sortie 
        and l.idproduct = pro.id 
        and m.idclient = ? and m.idcompany = ? group by pro.name' ,[$idclient,$idCompa]);

        $NumberColSpanSortie = count($ColSpanSortiee);

        usort($uniqueProductsSortie, function ($a, $b) {
            return strcmp($a[0], $b[0]);
        });

        // Output the sorted array



        usort($uniqueProductsEntree, function ($a, $b) {
            return strcmp($a[0], $b[0]);
        });


        // Group data by date for easy iteration in the Blade file
        $groupedData = [];

        foreach ($CaisseVidee as $caisse) {
            $date = $caisse->dateCaisseVide;
            $groupedData[$date] = [
                'caisseVide' => $caisse->caisseVide,
                'marchandiseEntree' => [],
                'marchandiseSortie' => [],
                'caisseRetour' => 0,
            ];
        }
        
        foreach ($MarchandiseEntree as $entree) {
            $date = $entree->date;
            $groupedData[$date]['marchandiseEntree'][$entree->name] = $entree->qteentree;
        }

        foreach ($MarchandiseSortiee as $sortie) {
            $date = $sortie->date;
            $groupedData[$date]['marchandiseSortie'][$sortie->name] = $sortie->qte;
        }

        foreach ($caisseRetourr as $retour) {
            $date = $retour->dateCaisseRetour;
            $groupedData[$date]['caisseRetour'] = $retour->caisseRetour;
        }

        ksort($groupedData);
        // Calculate totals for the footer
        $totalss = [
            'caisseVide' => 0,
            'entree' => [],
            'sortie' => [],
            'caisseRetour' => 0,
        ];
        
        foreach ($groupedData as $dateData) 
        {
            $totalss['caisseVide'] += $dateData['caisseVide'] ?? 0;

            

            foreach ($uniqueProductsEntree as $product) {
                $totalss['entree'][$product] = ($totalss['entree'][$product] ?? 0) + ($dateData['marchandiseEntree'][$product] ?? 0);
            }

            foreach ($uniqueProductsSortie as $product) {
                $totalss['sortie'][$product] = ($totalss['sortie'][$product] ?? 0) + ($dateData['marchandiseSortie'][$product] ?? 0);
            }

            $totalss['caisseRetour'] += $dateData['caisseRetour'] ?? 0;

        }
        $AllCompangie = DB::select('select  *from companys');

        $CompanyIsActive = Company::where('status',1)->value('name');
        return view('Client.FicheClient')
        ->with('AllCompangie',$AllCompangie)

        ->with('company',$CompanyIsActive)
        ///////////////
        ->with('CaisseVidee',$CaisseVidee)
        ->with('MarchandiseEntree',$MarchandiseEntree)
        ->with('MarchandiseSortiee',$MarchandiseSortiee)
        ->with('caisseRetourr',$caisseRetourr)
        ->with('uniqueProductsEntree',$uniqueProductsEntree)
        ->with('uniqueProductsSortie',$uniqueProductsSortie)
        ->with('NumberColSpanEntre',$NumberColSpanEntre)
        ->with('NumberColSpanSortie',$NumberColSpanSortie)
        ->with('groupedData',$groupedData)
        ->with('totalss',$totalss)
        ->with('clients',$clients[0])
        ->with('reste' ,$reste)
        ->with('resteMarchandise' ,$resteMarchandise)
        ->with('idclient',$request->id);
    }


    public function GetNomberCaisseByClient(Request $request)
    {
        $Nombre_Caisse_Vide         = DB::table('caissevides as caisse')
                                    ->join('companys as com','com.id','=','caisse.idcompany')
                                    ->where('com.status',1)
                                    ->where('caisse.idclient',$request->id)
                                    ->sum(DB::raw('IFNULL(caisse.number_box, 0)'));
        
        $Nombre_Marchandise_Entree  = DB::table('marchandis_entree as marchEntre')
                                    ->join('companys as com','com.id','=','marchEntre.idcompany')
                                    ->where('com.status',1)
                                    ->where('marchEntre.idclient',$request->id)
                                    ->sum(DB::raw('IFNULL(marchEntre.number_box, 0)'));

        $Nombre_Marchandise_Sortie  = DB::table('marchandise_sortie as marchSortie')
                                    ->join('companys as com','com.id','=','marchSortie.idcompany')
                                    ->where('com.status',1)
                                    ->where('marchSortie.idclient',$request->id)
                                    ->sum(DB::raw('IFNULL(marchSortie.number_box, 0)'));

        $Nombre_Caisse_Retour         = DB::table('caisse_retour as caisse')
                                    ->join('companys as com','com.id','=','caisse.idcompany')
                                    ->where('com.status',1)
                                    ->where('caisse.idclient',$request->id)
                                    ->sum(DB::raw('IFNULL(caisse.number_box, 0)'));
        
        return response()->json([
           'status'                     => 200,
           'Nombre_Caisse_Vide'         => $Nombre_Caisse_Vide,
           'Nombre_Marchandise_Entree'  => $Nombre_Marchandise_Entree,
           'Nombre_Marchandise_Sortie'  => $Nombre_Marchandise_Sortie,
           'Nombre_Caisse_Retour'       => $Nombre_Caisse_Retour,
        ]);
    }


    public function upload(Request $request)
    {
       

        $request->validate([
            'contract_file' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'idclient' => 'required|exists:clients,id',
            'idcompany' => 'required',
        ]);

        $file = $request->file('contract_file');
        $idclient = $request->input('idclient');
        $idcompany = $request->input('idcompany');

        $client = Client::findOrFail($idclient);

        // Base filename (without extension)
        $baseName = "{$client->firstname}_{$client->lastname}_{$idclient}_{$idcompany}";
        $extension = $file->getClientOriginalExtension();

        $folderPath = public_path('contracts');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        // Ensure unique filename
        $filename = $baseName . '.' . $extension;
        $counter = 2;
        while (File::exists($folderPath . '/' . $filename)) {
            $filename = $baseName . "($counter)." . $extension;
            $counter++;
        }

        // Move file to folder
        $file->move($folderPath, $filename);

        // Save correct filename in DB (with (2), (3) if needed)
        Contracts::create([
            'iamge_contract' => 'contracts/' . $filename,
            'idclient'       => $idclient,
            'iduser'         => auth()->id(),
            'idcompany'      => $idcompany,
        ]);

        return back()->with('success', 'Contract uploaded successfully.');

    }
}
