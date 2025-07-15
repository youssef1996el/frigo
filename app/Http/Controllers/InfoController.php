<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class InfoController extends Controller
{
    public function index(Request $request)
    {
        
        if($request->ajax())
        {
            $Data = DB::table('infos as i')
            ->join('users as u','u.id','=','i.iduser')
            ->select('i.*','u.name as usr');

            return DataTables::of($Data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $user = auth()->user();
                    $btn = '';

                // زر التعديل (تحقق من الصلاحية)
                /* if ($user && $user->can('company-modifier')) { */
                    $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 EditInfo"
                                data-id="' . $row->id . '"  
                                title="Modifier company" data-bs-toggle="modal" data-bs-target="#">
                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                            </a>';
                /* } */

                // زر الحذف (تحقق من الصلاحية)
                /* if ($user && $user->can('company-supprimer')) { */
                    $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteInformation"
                                data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                title="Supprimer company">
                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                            </a>';
                /* } */

                return $btn;
            })
            ->rawColumns(['action']) // تجنب ترميز HTML
            ->make(true);
        }
        $CompanyIsActive = Company::where('status',1)->value('name');
        return view('Info.index')
        ->with('company',$CompanyIsActive);
    }

    public function store(Request $request)
    {
        // Get the first active company
        $activeCompany = Company::where('status', 1)->first();

        if (!$activeCompany) {
            return response()->json([
                'status' => 404,
                'message' => 'Aucune compagnie active trouvée.',
            ]);
        }

        // Add authenticated user ID and active company ID to the data
        $data = $request->all();
        $data['iduser'] = Auth::id();
        $data['idcompany'] = $activeCompany->id;

        // Validation
        $validator = Validator::make($data, [
            'name' => 'required',
            'ice' => 'required',
            'if' => 'required',
            'phone' => 'required',
            'capital' => 'required',
            'cb' => 'required',
            'companie' => 'required',
        ], [
            'required' => 'Le champ :attribute est requis.',
        ], [
            'name' => 'Titre',
            'ice' => 'ICE',
            'if' => 'IF',
            'phone' => 'téléphone',
            'capital' => 'capital',
            'cb' => 'carte bancaire',
            'companie' => 'compagnie',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }

        // Create the Info record
        $info = Info::create($data);

        if ($info) {
            return response()->json([
                'status' => 200,
                'message' => 'information créée avec succès.',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur s\'est produite lors de la création.',
            ]);
        }
    }

    public function update(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'ice' => 'required',
            'if' => 'required',
            'phone' => 'required',
            'capital' => 'required',
            'cb' => 'required',
            'companie' => 'required',
        ], [
            'required' => 'Le champ :attribute est requis.',
        ], [
            'name' => 'Titre',
            'ice' => 'ICE',
            'if' => 'IF',
            'phone' => 'téléphone',
            'capital' => 'capital',
            'cb' => 'carte bancaire',
            'companie' => 'compagnie',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        }
       
        $Info = Info::find($request->id);
        if (!$Info) {
            return response()->json([
                'status' => 404,
                'message' => 'information non trouvé'
            ]);
        }

        // حفظ البيانات الجديدة
        $Info->update([
            'name' => $request->name,
            'ice'   => $request->ice,
            'if'  => $request->if,
            'phone'  => $request->phone,
            'capital'  => $request->capital,
            'cb'  => $request->cb,
            'companie'  => $request->companie,
        ]);


        return response()->json([
            'status'  => 200,
            'message' => 'Mise à jour réussie'
        ]);
    }

    public function Destory(Request $request)
    {
        try {
            $id = $request->id; // ID caissevide
            
            $information = DB::table('infos')->where('id', $id)->first();
        
            if (!$information) {
                return response()->json(['status' => 404, 'message' => 'Information non trouvée']);
            }
        
            // حذف السجل
            DB::table('infos')->where('id', $id)->delete();
        
            // إعادة حساب الكوميل
           
            return response()->json(['status' => 200, 'message' => 'Suppression avec succès']);
        
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        } 

        

    }

}
