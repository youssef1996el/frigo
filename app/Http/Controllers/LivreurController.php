<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Livreur;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage;
class LivreurController extends Controller
{
    //

    public function index(request $request)
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


            $DataLiveurs = DB::table('livreurs as l')
                ->join('companys as co','l.idcompany','=','co.id')
                ->join('users as u','u.id','=','l.iduser')
                ->join('display_with_company as d','d.idpermission','=','l.id')
                ->select('l.*','u.name as username')
                ->where('d.role','Livreur')
                ->where('co.id','=',$IdCompany);

            return DataTables::of($DataLiveurs)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '';

                // زر التعديل (تحقق من الصلاحية)
                /* if ($user && $user->can('company-modifier')) { */
                    $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editLivreur"
                                data-id="' . $row->id . '"  
                                title="Modifier company" data-bs-toggle="modal" data-bs-target="#ModalEditClient">
                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                            </a>';
                /* } */

                // زر الحذف (تحقق من الصلاحية)
                /* if ($user && $user->can('company-supprimer')) { */
                    /* $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteLivreur"
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
        $CompanyIsActive = Company::where('status',1)->value('name');
        return view("Livreur.index")
        ->with('company',$CompanyIsActive);
    }


    public function store(Request $request)
    {
        

        //get company is active
        $CompanyIsActive = Company::where('status', 1)->value('status');

        // get id company is status = 1
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

        // set in variable $data all request and add iduser and idcompany
        $data = $request->except('image_cin'); // استبعاد الصور قبل الإنشاء
        $data['iduser'] = Auth::user()->id;
        $data['idcompany'] = $IdCompany;

        // check livreur has in this company
        $CheckHasClientInThisCompany = Livreur::where('idcompany', $IdCompany)
            ->where('name', $data['name'])
            ->where('cin', $data['cin'])
            ->count();

        if ($CheckHasClientInThisCompany != 0) {
            return response()->json([
                'status' => 404,
                'message' => 'Ce livreur est déjà créé dans cette compagnie'
            ]);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'matricule' => 'required',
            'cin' => 'required',
            'phone' => 'required',
            'image_cin.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'required' => 'Le champ :attribute est requis.'
        ], [
            'name' => 'nom complet',
            'cin' => 'CIN',
            'phone' => 'téléphone',
            'matricule' => 'matricule',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        // إنشاء السجل
        $Livreur = Livreur::create($data);

        if ($Livreur) {
            $storagePath = public_path('image_livreur/cin_photo');
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0777, true, true);
            }
            $photoPaths = [];

            // رفع صور CIN المتعددة
            if ($request->hasFile('image_cin')) {
                foreach ($request->file('image_cin') as $file) {
                    $filename = $Livreur->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($storagePath, $filename);
                    $photoPaths[] = 'image_livreur/cin_photo/' . $filename;
                }
                // تحديث حقل الصور
                $Livreur->update(['image_cin' => json_encode($photoPaths)]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Livreur créée avec succès',
                'photos' => $photoPaths
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Quelque chose ne va pas'
        ]);
    }

    public function Update(Request $request)
    {
        $data = $request->all();
        
        //get company is active
        $CompanyIsActive = Company::where('status', 1)->value('status');

        // get id company is status = 1
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

        // set in variable $data all request and add iduser and idcompany
        $data = $request->except('image_cin'); // استبعاد الصور قبل الإنشاء
        $data['iduser'] = Auth::user()->id;
        $data['idcompany'] = $IdCompany;

        

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'matricule' => 'required',
            'cin' => 'required',
            'phone' => 'required',
            'image_cin.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],

        
        [
            'required' => 'Le champ :attribute est requis.'
        ], 
        
        
        [
            'name' => 'nom complet',
            'cin' => 'CIN',
            'phone' => 'téléphone',
            'matricule' => 'matricule',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $Livreur = Livreur::where('id', $data['id'])->first();

        // تحديث البيانات الأساسية
        $Livreur->update([
            'name'      => $data['name'],
            'cin'       => $data['cin'],
            'matricule' => $data['matricule'],
            'phone'     => $data['phone'],
        ]);
        
        // التحقق إذا كان هناك صور جديدة
        if ($request->hasFile('image_cin')) {
            $storagePath = public_path('image_livreur/cin_photo');

            // حذف الصور القديمة فقط الخاصة بهذا Livreur
            if (!empty($Livreur->image_cin)) {
                $oldPhotos = json_decode($Livreur->image_cin);
                foreach ($oldPhotos as $oldPhoto) {
                    $filename = basename($oldPhoto);
                    $firstCharacter = explode('_', $filename)[0]; // استخراج أول جزء من الاسم (ID)

                    if ($firstCharacter == $Livreur->id && File::exists(public_path($oldPhoto))) {
                        File::delete(public_path($oldPhoto));
                    }
                }
            }

            $photoPaths = [];

            // رفع الصور الجديدة
            foreach ($request->file('image_cin') as $file) {
                $filename = $Livreur->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                if (!File::exists($storagePath)) {
                    File::makeDirectory($storagePath, 0777, true, true);
                }

                $file->move($storagePath, $filename);
                $photoPaths[] = 'image_livreur/cin_photo/' . $filename;
            }

            // تحديث الصور في قاعدة البيانات
            $Livreur->update([
                'image_cin' => json_encode($photoPaths)
            ]);
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Livreur mis à jour avec succès'
        ]);



    }

    public function delete(Request $request)
    {
        // check if product in caisse vide

        $caisse_vide = DB::table('caissevides')->where('idlivreur',$request->id)->count();
        if($caisse_vide >=1)
        {
            return response()->json([
                'status'      => 404,
                'message'     => 'Ce livreur est déjà utilisé dans une caisse vide'
            ]);
        }

         // check if product in caisse retour

        $caisse_retour = DB::table('caisse_retour')->where('idlivreur',$request->id)->count();
        if($caisse_retour >=1)
        {
            return response()->json([
                'status'      => 404,
                'message'     => 'Ce livreur est déjà utilisé dans une caisse retour'
            ]);
        }


         // check if product in marchandis_entree

        $marchandis_entree = DB::table('marchandis_entree')->where('idlivreur',$request->id)->count();
        if($marchandis_entree >=1)
        {
            return response()->json([
                'status'      => 404,
                'message'     => 'Ce livreur est déjà utilisé dans une entrée de marchandise'
            ]);
        }

         // check if product in marchandis_entree

        $marchandise_sortie = DB::table('marchandise_sortie')->where('idlivreur',$request->id)->count();
        if($marchandise_sortie >=1)
        {
            return response()->json([
                'status'      => 404,
                'message'     => 'Ce livreur est déjà utilisé dans une sortie de marchandise'
            ]);
        }

        // delete product

        $Delete_Livreur = Livreur::where('id',$request->id)->delete();
        if($Delete_Livreur)
        {
            return response()->json([
                'status'      => 200,
                'message'     => 'Le livreur a été supprimé avec succès.'
            ]);
        }
    }
}
