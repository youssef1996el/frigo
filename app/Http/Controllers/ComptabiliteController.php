<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\comptablitie;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ComptabiliteController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $Data_Company = DB::table("comptabilite as c")
                ->join('users as u', 'u.id', '=', 'c.iduser')
                ->select('u.name as nameuser', 'c.name', DB::raw('if(c.status =1 , "Active","Désactiver") as status'), 'c.id', 'c.created_at');

                return DataTables::of($Data_Company)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $user = auth()->user();
                        $btn = '';

                        // زر التعديل (تحقق من الصلاحية)
                        /* if ($user && $user->can('company-modifier')) { */
                            $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editComptabilite"
                                        data-id="' . $row->id . '"  
                                        title="Modifier company" data-bs-toggle="modal" data-bs-target="#ModalEditComptabilite">
                                        <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                    </a>';
                        /* } */

                        // زر الحذف (تحقق من الصلاحية)
                        /* if ($user && $user->can('company-supprimer')) { */
                           
                        /* } */

                        return $btn;
                    })
                    ->rawColumns(['action']) // تجنب ترميز HTML
                    ->make(true);

            
        }
        

        $CompanyIsActive = Company::where('status',1)->value('name');
        $ListCompany = Company::all();
        
                                        

        return view('Comptabilite.index')
        ->with('company',$CompanyIsActive)

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
            $checkHasCompany   = comptablitie::count();
            if($checkHasCompany == 0 ) 
            {
                $data['status'] = 1;
            }
            else if($checkHasCompany > 0)
            {
                $check = comptablitie::where('status',1)->count();
                if($check > 0)
                {
                    comptablitie::query()->update(['status' => 0]);
                }
            }
            $comptabilite = comptablitie::create($data);
            if($comptabilite)
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'Comptabilité créée avec succès',
                ]);
            }
            
        }
    }


    public function Update(Request $request)
    {
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'id'     => 'required|exists:comptabilite,id', 
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

        
        $comptabilite = DB::table('comptabilite')->where('id', $data['id'])->first();

        if (!$comptabilite) {
            return response()->json([
                'status' => 404,
                'message' => 'Comptabilité non trouvée.',
            ]);
        }

        if ($data['status'] == 1) {
            
            DB::table('comptabilite')->where('status', 1)->where('id', '!=', $data['id'])->update(['status' => 0]);
        } elseif ($data['status'] == 0 && $comptabilite->status == 1) {
            return response()->json([
                'status' => 404,
                'message' => 'Au moins, une comptabilité doit être active.',
            ]);
        }

        // Update the company
        DB::table('comptabilite')->where('id', $data['id'])->update([
            'name'   => $data['name'],
            'status' => $data['status'],
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Comptabilité modifiée avec succès.',
        ]);
    }
}
