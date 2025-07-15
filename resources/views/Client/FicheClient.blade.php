@extends('dashboard.index')

@section('dashboard')

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        
        <style>
            #tableFiche_filter
            {
                margin-bottom: 5px;
            }
            /* .vertical-header {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                white-space: nowrap;
                text-align: center;
                vertical-align: middle;
                padding: 10px;
                width: 100px !important;
            } */
        </style>
        <div class="container-fluid mt-3">
            <form action="{{ url('getByCompanie/' . $idclient) }}" method="get">
                <label for=""> Companige</label>
                <select name="idCompa" id="" class="form-control d-inline">
                    @foreach ($AllCompangie as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
                <input type="text" name="id"  value="{{$idclient}}" hidden>
                <button type="submit" class="btn btn-secondary d-inline mt-2">search</button>
            </form>
            <div style="overflow-x: auto">
                <table class="table table-striped table-bordered w-100 mt-3" id="tableFiche" data-page-lenght="-1">
                    <thead>
                        <tr>
                            <th rowspan="2" class="vertical-header" style="widows: 145px !important">Date</th>
                            <th rowspan="2"class="vertical-header" style="background-color: rgb(248, 154, 154)">Sortie de caisses vides</th>
                            <th colspan="{{$NumberColSpanEntre}}" style="background-color: rgb(174, 245, 174)" class="vertical-header">Entré de marchandises</th>
                            <th colspan="{{$NumberColSpanSortie}}" style="background-color: rgb(114, 235, 205)" class="vertical-header">Sortie de marchandises</th>
                            <th rowspan="2" style="background-color: rgb(247, 211, 211)" class="vertical-header">Retour de caisses vides</th>
                        </tr>
                        <tr>
                            @if (!empty($uniqueProductsEntree))
                                @foreach ($uniqueProductsEntree as $item)
                                    <th style="background-color: rgb(174, 245, 174)" class="vertical-header">{{$item}}</th>
                                @endforeach
                            @else
                                <th style="background-color: rgb(174, 245, 174)" class="vertical-header"></th>
                            @endif

                            @if (!empty($uniqueProductsSortie))
                                @foreach ($uniqueProductsSortie as $item)
                                    <th style="background-color: rgb(114, 235, 205)" class="vertical-header">{{$item}}</th>
                                @endforeach
                            @else
                                <th style="background-color: rgb(114, 235, 205)" class="vertical-header"></th>
                            @endif



                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedData as $date => $data)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                                <td style="background-color: rgb(248, 154, 154);text-align: center;">{{ intval($data['caisseVide'] ?? 0) }}</td>

                                <!-- Check if uniqueProductsEntree is not empty -->
                                @if (!empty($uniqueProductsEntree))
                                    <!-- Iterate over uniqueProductsEntree -->
                                    @foreach ($uniqueProductsEntree as $product)
                                        <td style="background-color: rgb(174, 245, 174);text-align: center;">{{ intval($data['marchandiseEntree'][$product] ?? 0) }}</td>
                                    @endforeach
                                @else
                                    <td colspan="{{ count($uniqueProductsEntree) }}" style="background-color: rgb(174, 245, 174);text-align: center;">0</td>
                                @endif

                                <!-- Iterate over uniqueProductsSortie -->
                                @if (!empty($uniqueProductsSortie))
                                    @foreach ($uniqueProductsSortie as $product)
                                        <td style="background-color: rgb(114, 235, 205);text-align: center;">{{ intval($data['marchandiseSortie'][$product] ?? 0) }}</td>
                                    @endforeach
                                @else
                                    <!-- If uniqueProductsSortie is empty, display 0 in each column -->
                                    <td colspan="{{ count($uniqueProductsSortie) }}" style="background-color: rgb(114, 235, 205)">0</td>
                                @endif

                                <td style="background-color: rgb(247, 211, 211);text-align: center;">{{ intval($data['caisseRetour'] ?? 0) }}</td>
                            </tr>
                        @endforeach


                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td style="background-color: rgb(248, 154, 154);text-align: center;"></td>
                            @if (!empty($uniqueProductsEntree))
                                @foreach ($uniqueProductsEntree as $product)
                                    <td style="background-color: rgb(174, 245, 174);text-align: center;">{{ $totalss['entree'][$product] ?? 0 }}</td>
                                @endforeach
                            @else
                                <td style="background-color: rgb(174, 245, 174);text-align: center;"></td>
                            @endif

                            @if (!empty($uniqueProductsSortie))
                                @foreach ($uniqueProductsSortie as $product)
                                    <td style="background-color: rgb(114, 235, 205);text-align: center;">{{ $totalss['sortie'][$product] ?? 0 }}</td>
                                @endforeach
                            @else
                                <td style="background-color: rgb(114, 235, 205);text-align: center;"></td>
                            @endif


                            <td style="background-color: rgb(247, 211, 211);text-align: center;"></td>
                        </tr>
                        <tr>
                            <th>Total </th>
                            <th style="background-color: rgb(248, 154, 154);text-align: center;">{{ $totalss['caisseVide'] }}</th>

                            @php
                                $totalEntree = array_sum($totalss['entree']);
                                $totalSortie = array_sum($totalss['sortie']);
                            @endphp

                            <th colspan="{{ $NumberColSpanEntre }}" style="text-align: center;background-color: rgb(174, 245, 174)">{{ $totalEntree }}</th>
                            <th colspan="{{ $NumberColSpanSortie }}" style="text-align: center ;background-color: rgb(114, 235, 205)">{{ $totalSortie }}</th>
                            <th style="background-color: rgb(247, 211, 211);text-align: center;">{{ $totalss['caisseRetour'] }}</th>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
        <table class="table table-striped table-bordered w-50 mt-5">
                <tr>
                    <th class="fs-3 text-uppercase">Caisses vides retirées</th>
                    <th class="text-center bg-warning fs-3"> {{$totalss['caisseVide'] - $totalss['caisseRetour']}}</th>
                </tr>

                <tr>
                    <th class="fs-3 text-uppercase">Marchandises en stock </th>
                    <th class="text-center bg-warning fs-3"> {{$totalEntree - $totalSortie}}</th>
                </tr>

                <tr>
                    <th class="fs-3 text-uppercase">Caisses vides non remplis  </th>
                    <th class="text-center bg-warning fs-3">  {{ ($totalss['caisseVide'] - $totalss['caisseRetour']) - ($totalEntree - $totalSortie)   }}</th>
                </tr>
            </table>
           


    {{-- <div class="p-2 bg-light border mt-3">
        <p class=" fs-3">  Caisses vides retirées    = <span>{{$totalss['caisseVide'] - $totalss['caisseRetour']}}</span></p>
        <p class="fs-3"> Marachandise en stock= <span>{{$totalEntree - $totalSortie}}</span></p>
        <p class="fs-3"> Caisses vides non remplis = <span>{{ ($totalss['caisseVide'] - $totalss['caisseRetour']) - ($totalEntree - $totalSortie)   }}</span></p>
    </div> --}}



    <script>
        $(document).ready(function ()
        {

            $('#tableFiche').DataTable({
                "ordering": false,
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],
                paging: false,
                "pageLength": -1,

                "select": {
                    "style": "single"
                },
                "language":
                {
                    "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sLoadingRecords": "Chargement...",
                    "sProcessing": "Traitement...",
                    "sSearch": "Rechercher :",
                    "sZeroRecords": "Aucun élément correspondant trouvé",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sLast": "Dernier",
                        "sNext": "Suivant",
                        "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                        "_": "%d lignes sélectionnées",
                        "0": "Aucune ligne sélectionnée",
                        "1": "1 ligne sélectionnée"
                        }
                    }
                },
            });
        });
    </script>

           
        

    </div> <!-- content -->

    

</div>
@endsection