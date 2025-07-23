<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
class StituationController extends Controller
{
    public function index()
    {
        $CompanyIsActive = Company::where('status',1)->value('name');
        return view('SituationStockage.index')->with('company',$CompanyIsActive);
    }

    public function SortieCaisseVide(Request $request)
    {
        $Compagnie = Company::all();
        $perPage = 10;

        $companyId = $request->compagnie ?? DB::table('companys')->where('status', 1)->value('id');

        $queryCaisseVide = DB::table('caissevides as tcs')
            ->join('clients', 'tcs.idclient', '=', 'clients.id')
            ->select(
                DB::raw('DATE_FORMAT(tcs.created_at, "%d-%m-%Y") AS dateoperation'),
                DB::raw('CONCAT(clients.firstname, " ", clients.lastname) AS client'),
                DB::raw('SUM(tcs.cumul) as cuml'),
                DB::raw('SUM(tcs.number_box) as nombre')
            )
            ->where('tcs.idcompany', '=', $companyId)
            ->groupBy('clients.id', DB::raw('DATE(tcs.created_at)'))
            ->orderBy(DB::raw('DATE(tcs.created_at)'))
            ->get();

        $clientsCaisseVide = $queryCaisseVide->pluck('client')->unique()->toArray();
        $dataCaisseVide = [];
        $totalsCaisseVide = [];

        foreach ($queryCaisseVide as $item)
        {
            if (!isset($dataCaisseVide[$item->dateoperation]))
            {
                $dataCaisseVide[$item->dateoperation]                      = array_fill_keys($clientsCaisseVide, ['nombre' => 0, 'Cuml' => 0]);
            }

            $dataCaisseVide[$item->dateoperation][$item->client]['nombre'] = $item->nombre;
            $dataCaisseVide[$item->dateoperation][$item->client]['Cuml']   = $item->cuml;

            $totalsCaisseVide[$item->dateoperation]['totalNombre']         = ($totalsCaisseVide[$item->dateoperation]['totalNombre'] ?? 0) + $item->nombre;
            $totalsCaisseVide[$item->dateoperation]['totalCuml']           = ($totalsCaisseVide[$item->dateoperation]['totalCuml'] ?? 0) + $item->cuml;
        }

        $totalsCaisseVide['grandTotalNombre'] = array_sum(array_column($totalsCaisseVide, 'totalNombre'));
        $totalsCaisseVide['grandTotalCuml']   = array_sum(array_column($totalsCaisseVide, 'totalCuml'));
        $CompanyIsActive                      = Company::where('status', 1)->value('name');

        $CompanyIsActiveID                    = Company::where('status', 1)->value('id');
        return view('SituationStockage.StituationCaisseVide.index')
            ->with('dataCaisseVide', $dataCaisseVide)
            ->with('clientsCaisseVide', $clientsCaisseVide)
            ->with('totalsCaisseVide', $totalsCaisseVide)
            ->with('Compagnie', $Compagnie)
            ->with('queryCaisseVide', $queryCaisseVide)
            ->with('company', $CompanyIsActive)
            ->with('CompanyIsActiveID' ,$CompanyIsActiveID);

    }



    public function MarchandiseEntree(Request $request)
    {
        $Compagnie = Company::all();
        $perPage = 10;

        $companyId = $request->compagnie ?? DB::table('companys')->where('status', 1)->value('id');

        $query = DB::table('marchandis_entree as tce')
            ->join('clients', 'tce.idclient', '=', 'clients.id')
            ->select(
                DB::raw('DATE_FORMAT(tce.created_at, "%d-%m-%Y") AS dateoperation'),
                DB::raw('CONCAT(clients.firstname, " ", clients.lastname) AS client'),
                DB::raw('SUM(tce.cumul) as cuml'),
                DB::raw('SUM(tce.number_box) as nombre')
            )
            ->where('tce.idcompany', '=', $companyId)
            ->groupBy('clients.id', DB::raw('DATE(tce.created_at)'))
            ->orderBy(DB::raw('DATE(tce.created_at)'))
            ->get();


        $clientsMarchEntree = $query->pluck('client')->unique()->toArray();
        $dataMarchEntree = [];
        $totalsMarchEntree = [];

        foreach ($query as $item)
        {
            if (!isset($dataMarchEntree[$item->dateoperation][$item->client])) {
                foreach ($clientsMarchEntree as $client) {
                    $dataMarchEntree[$item->dateoperation][$client] = [
                        'nombre' => 0,
                        'Cuml' => 0,
                    ];
                }
            }

            $dataMarchEntree[$item->dateoperation][$item->client]['nombre'] = $item->nombre;
            $dataMarchEntree[$item->dateoperation][$item->client]['Cuml'] = $item->cuml;

            $totalsMarchEntree[$item->dateoperation]['totalNombre'] = isset($totalsMarchEntree[$item->dateoperation]['totalNombre'])
                ? $totalsMarchEntree[$item->dateoperation]['totalNombre'] + $item->nombre
                : $item->nombre;

            $totalsMarchEntree[$item->dateoperation]['totalCuml'] = isset($totalsMarchEntree[$item->dateoperation]['totalCuml'])
                ? $totalsMarchEntree[$item->dateoperation]['totalCuml'] + $item->cuml
                : $item->cuml;
        }

        $totalsMarchEntree['grandTotalNombre'] = array_sum(array_column($totalsMarchEntree, 'totalNombre'));
        $totalsMarchEntree['grandTotalCuml'] = array_sum(array_column($totalsMarchEntree, 'totalCuml'));

        $CompanyIsActive                      = Company::where('status', 1)->value('name');

        $CompanyIsActiveID                    = Company::where('status', 1)->value('id');
        return view('SituationStockage.SituationMarchandiseEntree.index')


        ->with('dataMarchEntree',$dataMarchEntree)
        ->with('clientsMarchEntree',$clientsMarchEntree)
        ->with('totalsMarchEntree',$totalsMarchEntree)
        ->with('Compagnie',$Compagnie)
        ->with('query',$query)
        ->with('company', $CompanyIsActive)
        ->with('CompanyIsActiveID' ,$CompanyIsActiveID);


    }


    public function MarchandiseSortie(Request $request)
    {
        // marchandise Sortie
        $Compagnie = Company::all();
        $companyId = $request->compagnie ?? DB::table('companys')->where('status', 1)->value('id');
        $query = DB::table('marchandise_sortie as tcs')
            ->join('clients', 'tcs.idclient', '=', 'clients.id')
            ->select(
                DB::raw('DATE_FORMAT(tcs.created_at, "%d-%m-%Y") AS dateoperation'),
                DB::raw('CONCAT(clients.firstname, " ", clients.lastname) AS client'),
                DB::raw('SUM(tcs.cumul) as cuml'),
                DB::raw('SUM(tcs.number_box) as nombre')
            )
            ->where('tcs.idcompany', '=', $companyId)
            ->groupBy('clients.id', DB::raw('DATE(tcs.created_at)'))
            ->orderBy(DB::raw('DATE(tcs.created_at)'))
            ->get();






        $clientsMarchSortie = $query->pluck('client')->unique()->toArray();
        $dataMarchSortie = [];
        $totalsMarchSortie = [];
        foreach ($query as $item)
        {
            if (!isset($dataMarchSortie[$item->dateoperation][$item->client])) {
                foreach ($clientsMarchSortie as $client) {
                    $dataMarchSortie[$item->dateoperation][$client] = [
                        'nombre' => 0,
                        'Cuml' => 0,
                    ];
                }
            }

            $dataMarchSortie[$item->dateoperation][$item->client]['nombre'] = $item->nombre;
            $dataMarchSortie[$item->dateoperation][$item->client]['Cuml'] = $item->cuml;

            $totalsMarchSortie[$item->dateoperation]['totalNombre'] = isset($totalsMarchSortie[$item->dateoperation]['totalNombre'])
                ? $totalsMarchSortie[$item->dateoperation]['totalNombre'] + $item->nombre
                : $item->nombre;

            $totalsMarchSortie[$item->dateoperation]['totalCuml'] = isset($totalsMarchSortie[$item->dateoperation]['totalCuml'])
                ? $totalsMarchSortie[$item->dateoperation]['totalCuml'] + $item->cuml
                : $item->cuml;
        }
        $totalsMarchSortie['grandTotalNombre'] = array_sum(array_column($totalsMarchSortie, 'totalNombre'));
        $totalsMarchSortie['grandTotalCuml'] = array_sum(array_column($totalsMarchSortie, 'totalCuml'));


         $CompanyIsActive                      = Company::where('status', 1)->value('name');

        $CompanyIsActiveID                    = Company::where('status', 1)->value('id');
        return view('SituationStockage.StituationMarchandiseSortie.index')



        ->with('dataMarchSortie',$dataMarchSortie)
        ->with('clientsMarchSortie',$clientsMarchSortie)
        ->with('totalsMarchSortie',$totalsMarchSortie)
        ->with('Compagnie',$Compagnie)
        ->with('query',$query)
        ->with('company', $CompanyIsActive)
        ->with('CompanyIsActiveID' ,$CompanyIsActiveID)
        ;
    }



    public function SortieCaisseRetour(Request $request)
    {
        $Compagnie = Company::all();
        $companyId = $request->compagnie ?? DB::table('companys')->where('status', 1)->value('id');
        $queryCaisseRetour= DB::table('caisse_retour as tcs')
            ->join('clients', 'tcs.idclient', '=', 'clients.id')
            ->select(
                DB::raw('DATE_FORMAT(tcs.created_at, "%d-%m-%Y") AS dateoperation'),
                DB::raw('CONCAT(clients.firstname, " ", clients.lastname) AS client'),
                DB::raw('SUM(tcs.cumul) as cuml'),
                DB::raw('SUM(tcs.number_box) as nombre')
            )
            ->where('tcs.idcompany', '=', $companyId)
            ->groupBy('clients.id', DB::raw('DATE(tcs.created_at)'))
            ->orderBy(DB::raw('DATE(tcs.created_at)'))
            ->get();


        $clientsCaisseRetour = $queryCaisseRetour->pluck('client')->unique()->toArray();
        $dataCaisseRetour = [];
        $totalsCaisseRetour = [];
        foreach ($queryCaisseRetour as $item) {
            if (!isset($dataCaisseRetour[$item->dateoperation][$item->client])) {
                foreach ($clientsCaisseRetour as $client) {
                    $dataCaisseRetour[$item->dateoperation][$client] = [
                        'nombre' => 0,
                        'Cuml' => 0,
                    ];
                }
            }

            $dataCaisseRetour[$item->dateoperation][$item->client]['nombre'] = $item->nombre;
            $dataCaisseRetour[$item->dateoperation][$item->client]['Cuml'] = $item->cuml;

            $totalsCaisseRetour[$item->dateoperation]['totalNombre'] = isset($totalsCaisseVide[$item->dateoperation]['totalNombre'])
                ? $totalsCaisseRetour[$item->dateoperation]['totalNombre'] + $item->nombre
                : $item->nombre;

            $totalsCaisseRetour[$item->dateoperation]['totalCuml'] = isset($totalsCaisseRetour[$item->dateoperation]['totalCuml'])
                ? $totalsCaisseRetour[$item->dateoperation]['totalCuml'] + $item->cuml
                : $item->cuml;
        }
        $totalsCaisseRetour['grandTotalNombre'] = array_sum(array_column($totalsCaisseRetour, 'totalNombre'));
        $totalsCaisseRetour['grandTotalCuml'] = array_sum(array_column($totalsCaisseRetour, 'totalCuml'));
        //////////////////////////////////////
        $sumByDate = [];

        foreach ($dataCaisseRetour as $date => $values) {
            $sum = 0;
            foreach ($values as $item) {
                $sum += (float) $item['nombre'];
            }
            $sumByDate[$date] = $sum;
        }
        $CompanyIsActive                      = Company::where('status', 1)->value('name');

        $CompanyIsActiveID                    = Company::where('status', 1)->value('id');
        $sumCumlByDate = [];
        foreach ($dataCaisseRetour as $date => $values) {
            $sum = 0;
            foreach ($values as $item) {
                $sum += (float) $item['Cuml'];
            }
            $sumCumlByDate[$date] = $sum;
        }
        return view('SituationStockage.StituationCaisseRetour.index')
        ->with('Compagnie', $Compagnie)
        ->with('dataCaisseRetour', $dataCaisseRetour)
        ->with('clientsCaisseRetour', $clientsCaisseRetour)
        ->with('totalsCaisseRetour', $totalsCaisseRetour)
        ->with('queryCaisseRetour', $queryCaisseRetour)
        ->with('totalSum', array_sum($sumByDate))
        ->with('sumByDate', $sumByDate)
        ->with('totalCuml', array_sum($sumCumlByDate)) // ✅ أضف هذا
        ->with('sumCumlByDate', $sumCumlByDate)        // ✅ أضف هذا أيضاً
        ->with('company', $CompanyIsActive)
        ->with('CompanyIsActiveID', $CompanyIsActiveID);

    }


    public function bilangenrale(Request $request)
    {
        $Compagnie = Company::all();
        $companyId = $request->compagnie ?? DB::table('companys')->where('status', 1)->value('id');

        // Fetch data using the selected or default company ID
       $CaisseVide = DB::select('
            SELECT DATE_FORMAT(DATE(created_at), "%d-%m-%Y") AS dateCaisseVide, SUM(number_box) AS caisseVide
            FROM caissevides
            WHERE idcompany = ?
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC', [$companyId]);


        $caisseRetour = DB::select('
            SELECT DATE_FORMAT(DATE(created_at), "%d-%m-%Y") AS dateCaisseRetour, SUM(number_box) AS caisseRetour
            FROM caisse_retour
            WHERE idcompany = ?
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC', [$companyId]);

        $MarchandiseEntree = DB::select('
            SELECT DATE_FORMAT(DATE(created_at), "%d-%m-%Y") AS dateMarchadiseEntree, SUM(number_box) AS totalEntree
            FROM marchandis_entree
            WHERE idcompany = ?
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC', [$companyId]);

        $MarchandiseSortie = DB::select('
            SELECT DATE_FORMAT(DATE(created_at), "%d-%m-%Y") AS dateMarchadiseSortie, SUM(number_box) AS totalEntree
            FROM marchandise_sortie
            WHERE idcompany = ?
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC', [$companyId]);






        $mergedData = [];

        // Combine all unique dates from all datasets
        $allDates = array_unique(array_merge(
            array_column($CaisseVide       , 'dateCaisseVide'),
                    array_column($MarchandiseEntree, 'dateMarchadiseEntree'),
                    array_column($MarchandiseSortie, 'dateMarchadiseSortie'),
                    array_column($caisseRetour     , 'dateCaisseRetour')
        ));

        // Create a base entry for each date
        foreach ($allDates as $date) {
            $mergedData[$date] = [
                'date' => $date,
                'caisseVide' => 0,
                'totalEntree' => 0,
                'totalSortie' => 0,
                'caisseRetour' => 0,
            ];
        }

        // Update entries with data from each dataset
        foreach ($CaisseVide as $caisseVideItem) {
            $date = $caisseVideItem->dateCaisseVide;
            $mergedData[$date]['caisseVide'] = $caisseVideItem->caisseVide;
        }

        foreach ($MarchandiseEntree as $entreeItem) {
            $date = $entreeItem->dateMarchadiseEntree;
            $mergedData[$date]['totalEntree'] = $entreeItem->totalEntree;
        }

        foreach ($MarchandiseSortie as $sortieItem) {
            $date = $sortieItem->dateMarchadiseSortie;
            $mergedData[$date]['totalSortie'] += $sortieItem->totalEntree;
        }

        foreach ($caisseRetour as $retourItem) {
            $date = $retourItem->dateCaisseRetour;
            $mergedData[$date]['caisseRetour'] += $retourItem->caisseRetour;
        }

        // Sort the merged data by date
        ksort($mergedData);

        $timestamps = array_map('strtotime', array_keys($mergedData));
        asort($timestamps);

        $sortedData = [];
        foreach ($timestamps as $timestamp) {
            $date = date('d-m-Y', $timestamp);
            $sortedData[$date] = $mergedData[$date];
        }



        $mergedData = $sortedData;

        // Calculate the totals
        $totals = [
                    'caisseVide' => array_sum(array_column($mergedData, 'caisseVide')),
                    'totalEntree' => array_sum(array_column($mergedData, 'totalEntree')),
                    'totalSortie' => array_sum(array_column($mergedData, 'totalSortie')),
                    'caisseRetour' => array_sum(array_column($mergedData, 'caisseRetour')),
                ];
        $CompanyIsActive                      = Company::where('status', 1)->value('name');

        $CompanyIsActiveID                    = Company::where('status', 1)->value('id');
        return view('SituationStockage.BilanGenerale.index')
        ->with('mergedData',$mergedData)
        ->with('totals',$totals)
        ->with('Compagnie',$Compagnie)
        ->with('company', $CompanyIsActive)
        ->with('CompanyIsActiveID', $CompanyIsActiveID);
    }


    public function showClientSituation(Request $request)
    {
        // ✅ تحديد الشركة تلقائيًا إذا لم يتم تمرير compagnie
        $companyId = $request->compagnie ?? DB::table('companys')->where('status', 1)->value('id');

        //$clients = DB::table('clients')->get();
        $clients = DB::table('companys as c')
        ->join('display_with_company as d', 'd.idcompany', '=', 'c.id')
        ->join('clients as cl', 'cl.id', '=', 'd.idpermission')
        ->where('c.id', $companyId)
        ->where('d.role', 'Client')
        ->select('cl.*')
        ->get();

        $data = [];
        $totalCaisse = 0;
        $totalMarchandise = 0;

        foreach ($clients as $client) {
            $idclient = $client->id;

            $sortie_caisse = DB::table('caissevides')
                ->where('idclient', $idclient)
                ->where('idcompany', $companyId)
                ->sum('number_box');

            $retour_caisse = DB::table('caisse_retour')
                ->where('idclient', $idclient)
                ->where('idcompany', $companyId)
                ->sum('number_box');

            $caisse_vide_chez_clt = $sortie_caisse - $retour_caisse;

            $entree_marchandise = DB::table('marchandis_entree')
                ->where('idclient', $idclient)
                ->where('idcompany', $companyId)
                ->sum('number_box');

            $sortie_marchandise = DB::table('marchandise_sortie')
                ->where('idclient', $idclient)
                ->where('idcompany', $companyId)
                ->sum('number_box');

            $marchandise_stock = $entree_marchandise - $sortie_marchandise;

            $data[] = [
                'client' => $client->firstname.' '.$client->lastname,
                'caisse_vide' => $caisse_vide_chez_clt,
                'marchandise' => $marchandise_stock
            ];

            $totalCaisse += $caisse_vide_chez_clt;
            $totalMarchandise += $marchandise_stock;
        }

        $company                      = Company::where('status', 1)->value('name');
        $Compagnie = Company::all();
        $CompanyIsActiveID                    = Company::where('status', 1)->value('id');
        return view('SituationStockage.StituationClient.index',
         compact('data', 'totalCaisse',
         'totalMarchandise', 'companyId','Compagnie',

        'company','CompanyIsActiveID'));
    }

}
