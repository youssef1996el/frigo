<?php

namespace App\Http\Controllers;


use App\Models\Company;
use App\Models\Charge;
use App\Models\Ferme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\comptablitie;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OperationExport;
class FermeController extends Controller
{
    public function index()
    {
        
        $operations = Ferme::select(
            DB::raw('DATE(ferme.date) as operation_date'),
            DB::raw('SUM(ferme.dotation) as sum_dotation'),
            DB::raw('SUM(ferme.montant) as sum_montant'),
            'charges.libelle as charge_name','ferme.id as idferme'
        )
        ->leftjoin('charges', 'ferme.charge_id', '=', 'charges.id') 
        ->join('comptabilite as c','c.id','=','ferme.idcomptabilite')
        ->where('c.status','=',1)
        ->groupBy(DB::raw('DATE(ferme.date)'), 'charges.libelle','ferme.id') 
        ->orderBy('operation_date', 'desc')
        ->paginate(20);
           
       

        
        $cumulDotation = 0;
        $cumulMontant = 0;

        foreach ($operations as $operation) {
            $cumulDotation += $operation->sum_dotation;
            $cumulMontant += $operation->sum_montant;

            $operation->cumul_dotation = $cumulDotation;
            $operation->cumul_montant = $cumulMontant;
        }


        $chargesDetail = DB::table('charges')->pluck('libelle', 'id');

        $operationsCharge = DB::table('ferme')
                ->select(
                    DB::raw('DATE(date) as date'),
                    'charge_id',
                    DB::raw('SUM(montant) as montant')
                )
                ->groupBy(DB::raw('DATE(date)'), 'charge_id')
                ->get();

        
        $grouped = [];

        foreach ($operationsCharge as $op) {
            $date = $op->date;
            $chargeId = $op->charge_id;
            $montant = $op->montant;

            $grouped[$date][$chargeId] = $montant;
        }

        
        $totals = [];

        foreach ($chargesDetail as $id => $libelle) {
            $totals[$id] = $operationsCharge->where('charge_id', $id)->sum('montant');
        }

       
                 
       
        $CompanyIsActive = Company::where('status',1)->value('status');
           
        $IdCompany       = Company::where('status',$CompanyIsActive)->value('id');
        $Charges  = Charge::where('idcompany',$IdCompany)->get();
        $CompanyIsActive = Company::where('status',1)->value('name');
        return view('Ferme.index')
        ->with('company',$CompanyIsActive)
        ->with('Charges',$Charges)
        ->with('operations',$operations)
        ->with('chargesDetail', $chargesDetail)
        ->with('grouped', $grouped)
        ->with('totals', $totals);
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'dotation'   => 'nullable|numeric',
            'montant'    => 'nullable|numeric',
            'charge_id'  => 'nullable|exists:charges,id',
        ]);

        if (empty($validated['charge_id']) || $validated['charge_id'] == 0) {
            $validated['charge_id'] = null;
            if (!is_null($validated['montant'])) {
                $validated['montant'] = 0;
            }
        }

        $dotation = $validated['dotation'] ?? 0;
        $montant = $validated['montant'] ?? 0;
        $charge_id = $validated['charge_id'];

        $last = Ferme::orderBy('id', 'desc')->first();

    

        
        if ($dotation != 0) 
        {
            $last = Ferme::sum("dotation");
            $newCumulDotation = $last + $dotation;
        } 
        else 
        {
            $newCumulDotation = 0;
        }
        

      
        if ($montant != 0) 
        {
            $last = Ferme::sum("montant");
            $newCumulCharge = $last + $montant;
        } else {
            $newCumulCharge = 0;
        }
        $ComptabiliteIsActive = comptablitie::where('status',1)->value('id');
       
        Ferme::create([
            'date'           => now(),
            'dotation'       => $dotation != 0 ? $dotation : null,
            'charge_id'      => $charge_id,
            'montant'        => $montant != 0 ? $montant : null,
            'cumul_dotation' => $newCumulDotation,
            'cumul_charge'   => $newCumulCharge,
            'idcomptabilite'   => $ComptabiliteIsActive,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Opération ajoutée avec succès',
        ]);
    }

    public function export()
    {
        return Excel::download(new OperationExport, 'ferme.xlsx');
    }

    public function DeleteFerme($id)
    {
        $Delete_Ferme = Ferme::where('id',$id)->delete();
        if($Delete_Ferme)
        {
            return redirect()->to('Ferme')->with('success', 'Opération effectuée avec succès');
        }
        
    }
}
