<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\DB;
use App\Models\CaisseVide;
use App\Models\Company;
use App\Models\Print_Caisse_Vides;
use App\Models\Info;
use Carbon\Carbon;
class PrintBonSoriteController extends Controller
{
    //

    public function printBon($id)
    {
        $Check_Info = Info::count();
        if($Check_Info == 0)
        {
            return redirect('caissesortie')->with('ErrorsInfos','Veuillez insérer les informations de l\'entreprise');
            
        }
        $Check_Print_Caisse_Vides = Print_Caisse_Vides::count();
        if($Check_Print_Caisse_Vides == 0)
        {
            return redirect('caissesortie')->with('ErrorsNumberStartBon','Veuillez choisir le numéro que vous souhaitez strat bon caisse vide');
        }
        // Get the active company status
        $CompanyIsActive = Company::where('status', 1)->value('status');
        
        // Get the company ID where status = 1
        $IdCompany = Company::where('status', $CompanyIsActive)->value('id');
        
        // Check if a record already exists for this company and caissevide ID
        $existingRecord = DB::table('print_caisse_vides')
            ->where('idcompany', $IdCompany)
            ->where('idcaissevide', $id)
            ->first();
           

        if (!$existingRecord) {
            // Get last number_bon for the company, or default to 0 if none found
            $lastRecord = DB::table('print_caisse_vides')
                ->where('idcompany', $IdCompany)
                ->orderByDesc('id')
                ->first();
            if($lastRecord->number_bon && $lastRecord->idcaissevide == null)
            {
                $newNumberBon = 1;
                Print_Caisse_Vides::where('id',$lastRecord->id)->update([
                    'idcaissevide' =>$id
                ]);
            }
            else
            {
                $newNumberBon = $lastRecord ? $lastRecord->number_bon + 1 : 1;
                    Print_Caisse_Vides::create([
                    'number_bon' => $newNumberBon,
                    'idcaissevide' => $id,
                    'idcompany' => $IdCompany
                ]);
            }
        }
        

        try {
            $data = DB::table('caissevides as c')
                ->select(
                    'c.number_box'      ,'c.cumul','c.etranger',DB::raw("CONCAT(cl.firstname, ' ', cl.lastname) as custommer"),
                    'l.name as name_livreur'     ,'l.cin as cin_livreur','l.matricule'
                )
                ->join('clients as cl'      , 'c.idclient'  , '=', 'cl.id')
                ->leftjoin('livreurs as l'  , 'c.idlivreur' , '=', 'l.id')
                ->join('users as u'         , 'c.iduser'    , '=', 'u.id')
                ->where('c.id', $id)
                ->get();

                   
                if(is_null($data[0]->name_livreur))
                {
                    $data = DB::table("caissevides as c")
                    ->join('ventes as v'        ,'v.id'     ,'='    ,'c.idvente')
                    ->join('clients as cl'      ,'cl.id'    ,'='    ,'v.achteur')
                    ->join('clients as clv'     ,'clv.id'   ,'='    ,'v.vendeur')
                    ->join('list_origins as l'  ,'l.id'     ,'='    ,'v.idproduct')
                    ->select(DB::raw('concat(clv.firstname," ",clv.lastname) as vendeur'),DB::raw('concat(cl.firstname," ",cl.lastname) as achteur'),
                    'l.name as product','c.number_box','c.created_at')
                    ->where('c.id',$id)
                    ->get();
                }
                
            if ($data->isNotEmpty()) 
            {
                $CaisseVide = CaisseVide::find($id);
                if ($CaisseVide) {
                    $CaisseVide->update([
                        'clotuer' => true,
                    ]);
                }
            }
            

            $Extract_number_bon = DB::table('print_caisse_vides')
                ->where('idcompany'     , $IdCompany)
                ->where('idcaissevide'  , $id)
                ->orderByDesc('id')
                ->value('number_bon');

             $Client = DB::table('caissevides as c')
                                ->join('clients as co'  , 'co.id'  , 'c.idclient')
                                ->where('c.id',$id)
                                ->select('co.firstname', 'co.lastname', DB::raw('DATE(c.created_at) as created_date'))
                                ->first();
 
            $Info = Info::all();
            $DatePrintBon = Carbon::now();
            $DatePrintBon->format('d-m-Y');
            $TimePrintBon = $DatePrintBon->format('H:i');
           
            if(isset($data[0]->name_livreur))
            {
                
                $html = view('PrintCaisseVide.index', compact('data', 'Extract_number_bon','Client','Info','DatePrintBon','TimePrintBon'))->render();
            }
            else
            {
               
                $Vendeur =  $data[0]->vendeur;
                $Achteur =  $data[0]->achteur;
                $title = "BON DE SORTIE CAISSES VIDE";
                $html = view('Vente.PrintVente',compact('title','data','Achteur','Vendeur','Info','Client','DatePrintBon','TimePrintBon','Extract_number_bon'))->render();
            }
            

            $mpdf = new \Mpdf\Mpdf([
                'default_font' => 'Amiri', // Arabic font
                'format' => 'A4',
                'mode' => 'utf-8',
                'margin_top' => 10,
                'margin_bottom' => 10,
            ]);
           

            $mpdf->WriteHTML($html);
            return $mpdf->Output("Bon_Sortie_$id.pdf", 'I'); // I = display | D = download | F = save file

        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }

}
