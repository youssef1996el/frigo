<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Charge;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ChargeController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) 
        {
            //get company is active
            $CompanyIsActive = Company::where('status',1)->value('status');
            // get id company is status = 1 
            $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');

            $charges = DB::table('charges as c')
            ->join('users as u'     , 'u.id'   , 'c.iduser')
            ->join('companys as com', 'com.id' , 'c.idcompany')
            ->where('com.id'       ,'='    ,$IdCompany)
            ->select('c.*','u.name','com.name as name_company')
            ->OrderBy('c.id','desc');
            
           return DataTables::of($charges)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '';

                // زر التعديل (تحقق من الصلاحية)
                /* if ($user && $user->can('company-modifier')) { */
                $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 EditCharge" 
                            data-id="' . $row->id . '"  
                            title="Modifier caisse de vide" 
                           >
                            <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                        </a>';
    
                /* } */


                /* $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteCharge"
                                data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                title="Supprimer cette bon sortie">
                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                            </a>'; */

               

               
                /* if ($user && $user->can('company-supprimer')) { */
                    
                /* } */

                return $btn;
            })
            ->rawColumns(['action']) // تجنب ترميز HTML
            ->make(true);
        }
        $CompanyIsActive = Company::where('status',1)->value('name');
        $users = User::all();
        $companies = Company::all();
        return view('Charge.index')->with('company',$CompanyIsActive);
    }


    public function update(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'libelle' => 'required',
            
        ],
        [
            'required' => 'Le champ :attribute est requis.'
        ],
        [
            'libelle' => 'nombre',
           
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        $Charge = Charge::find($request->id);
        if (!$Charge) {
            return response()->json([
                'status' => 404,
                'message' => 'Charge non trouvé'
            ]);
        }

        // حفظ البيانات الجديدة
        $Charge->update([
            'libelle' => $request->libelle,
        ]);

        return response()->json([
            'status'  => 200,
            'message' => 'Mise à jour réussie'
        ]);
    }

    public function store(Request $request)
    {
         
        $validator = Validator::make($request->all(), [
            'libelle' => 'required',
            
        ],
        [
            'required' => 'Le champ :attribute est requis.'
        ],
        [
            'libelle' => 'libelle',
           
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
        $data = $request->all();
       
        $IdCompany = Company::where('status', 1)->value('id');
        $data['iduser'] = Auth::user()->id;
        $data['idcompany'] = $IdCompany;
        
        $Charge = Charge::create([
            'libelle' => $data['libelle'],
            'iduser'     => $data['iduser'],
            'idcompany'  => $data['idcompany'],
        ]);

        if ($Charge) 
        {
            return response()->json([
                'status'  => 200,
                'message' => 'Charge créée avec succès'
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

    public function Destroy(Request $request)
    {
        try {
            $id = $request->id; // ID 
            
            DB::table('charges')->where('id', $id)->delete();
            return response()->json(['status' => 200, 'message' => 'Suppression avec succès']);
        
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }
}
