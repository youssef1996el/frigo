<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\List_origins;
use Illuminate\Support\Facades\Validator;
use App\Models\ligne_marchandise_entree;
use App\Models\ligne_marchandise_sortie;
use App\Models\Vente;

class ListOriginController extends Controller
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

            $data = DB::table('list_origins as l')
            ->join('users as u', 'l.iduser', '=', 'u.id')
            ->join('companys as c', 'l.idcompany', '=', 'c.id')
            ->join('display_with_company as d','d.idpermission','=','l.id')
            ->select('l.id', 'l.name', 'u.name as username', 'l.created_at')
            ->where('c.id','=',$IdCompany)
            ->where('d.role','Product')
            ->orderBy('l.id', 'desc');
        
           

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '';

                // زر التعديل (تحقق من الصلاحية)
                /* if ($user && $user->can('company-modifier')) { */
                    $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editProduct"
                                data-id="' . $row->id . '"  
                                title="Modifier client" data-bs-toggle="modal" data-bs-target="#ModalEditClient">
                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                            </a>';

                   
                /* } */

                // زر الحذف (تحقق من الصلاحية)
                /* if ($user && $user->can('company-supprimer')) { */
                   /*  $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteProduct"
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
        return view("List_origins.index")
        ->with('company',$CompanyIsActive);
    }


    public function store(Request $request)
    {
        
        $CompanyIsActive = Company::where('status', 1)->value('status');
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');

        
        $CheckHasProducttInThisCompany = List_origins::where('idcompany', $IdCompany)
            ->where('name', $request->name)
            ->count();

        if ($CheckHasProducttInThisCompany != 0) {
            return response()->json([
                'status' => 404,
                'message' => 'Ce produit est déjà créé dans cette compagnie'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            
        ]);
        $customMessages = [
            'required' => 'Le champ :attribute est requis.',
        ];
        $customAttributes = [
            'name'         => 'titre ',
            
        ];

        $validator->setCustomMessages($customMessages);
        $validator->setAttributeNames($customAttributes);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        $data = $request->all();
        $data['iduser'] = Auth::id();
        $data['idcompany'] = $IdCompany;

        $List_origins = List_origins::create($data);

        if ($List_origins) {
           

            return response()->json([
                'status' => 200,
                'message' => 'Produit créée avec succès',
                
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Quelque chose ne va pas'
            ]);
        }
    }


    public function update(Request $request)
    {
        

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        $customMessages = [
            'required' => 'Le champ :attribute est requis.',
        ];
        $customAttributes = [
            'name' => 'titre ',
        ];

        $validator->setCustomMessages($customMessages);
        $validator->setAttributeNames($customAttributes);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        $List_origins = List_origins::find($request->id);

        if ($List_origins) 
        {
            $List_origins->name = $request->name;
            $List_origins->update();

            return response()->json([
                'status' => 200,
                'message' => 'Produit modifié avec succès',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Produit non trouvé'
            ]);
        }
    }

    public function Delete(Request $request)
    {
        // check if product in entre

        $count_entree = DB::table('ligne_marchandis')->where('idproduct',$request->id)->count();
        if($count_entree >=1)
        {
            return response()->json([
                'status'      => 404,
                'message'     => 'Ce produit est déjà utilisé dans une entrée de marchandise'
            ]);
        }

         // check if product in sortie

        $count_sortie = DB::table('ligne_marchandise_sortie')->where('idproduct',$request->id)->count();
        if($count_sortie >=1)
        {
            return response()->json([
                'status'      => 404,
                'message'     => 'Ce produit est déjà utilisé dans une sortie de marchandise'
            ]);
        }


         // check if product in vente

        $count_vente = DB::table('ventes')->where('idproduct',$request->id)->count();
        if($count_vente >=1)
        {
            return response()->json([
                'status'      => 404,
                'message'     => 'Ce produit est déjà utilisé dans une vente de marchandise'
            ]);
        }

        // delete product

        $Delete_Product = List_origins::where('id',$request->id)->delete();
        if($Delete_Product)
        {
            return response()->json([
                'status'      => 200,
                'message'     => 'Le produit a été supprimé avec succès.'
            ]);
        }

    }

}
