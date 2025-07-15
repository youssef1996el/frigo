@extends('dashboard.index')

@section('dashboard')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<div class="content-page">
    <div class="content">
        <section class="mt-3">
            <div class="row bg-light p-2 rounded-2 border">
                <div class="col-sm-12 col-md-6 col-xl-6">
                    <h3 class="">Le bilan général</h3>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6">
                    
                    <a href="{{url('Stitaution')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                    <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                </div>
            </div>
        
            <div class="noPrint">
                <form action="{{url('Stituation_bilangenrale')}}" method="get">
                    <div class="row  mb-3">

                            <div class="col-sm-12 col-md-6 col-xl-6 ">
                                <label for="">Compagnie</label>
                                 @php
                                    $CompanyBySearch = isset($_GET['compagnie']) ? $_GET['compagnie'] : $CompanyIsActiveID;
                                @endphp
                                <select name="compagnie" id="" class="form-select">
                                    @foreach ($Compagnie as $item)
                                        <option value="{{ $item->id }}" {{ $CompanyBySearch == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6 col-xl-6 ">
                                <button class="btn btn-secondary mr-3 float-end mt-4" type="submit">Recherche</button>
                                <button class="btn btn-info mr-3 float-end mt-4 me-3" type="button" onclick="generatePDF()">Print</button>
                            </div>
                    </div>
                </form>
            </div>

            <table class="table table-bordered" id="tableBilan" data-page-lenght="-1">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="background-color: rgb(248, 154, 154)">Sortie de caisses vides</th>
                        <th style="background-color: rgb(174, 245, 174)">Entré de marchandises</th>
                        <th style="background-color: rgb(114, 235, 205)">Sortie de marchandises</th>
                        <th style="background-color: rgb(247, 211, 211)">Retour de caisses vides</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mergedData as $row)
                        <tr>
                            <td style="white-space: nowrap">{{ $row['date'] }}</td>
                            <td style="background-color: rgb(248, 154, 154)" >{{ intval($row['caisseVide'] )}}</td>
                            <td style="background-color: rgb(174, 245, 174)">{{ intval($row['totalEntree']) }}</td>
                            <td style="background-color: rgb(114, 235, 205)">{{ $row['totalSortie'] }}</td>
                            <td style="background-color: rgb(247, 211, 211)">{{ $row['caisseRetour'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>Totaux</td>
                        <td style="background-color: rgb(248, 154, 154)">{{ $totals['caisseVide'] }}</td>
                        <td style="background-color: rgb(174, 245, 174)">{{ $totals['totalEntree'] }}</td>
                        <td style="background-color: rgb(114, 235, 205)">{{ $totals['totalSortie'] }}</td>
                        <td style="background-color: rgb(247, 211, 211)">{{ $totals['caisseRetour'] }}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-striped table-bordered w-50 mt-5">
                <tr>
                    <th class="fs-3 text-uppercase">Caisses vides retirées</th>
                    <th class="text-center bg-warning fs-3"> {{ $totals['caisseVide'] - $totals['caisseRetour'] }}</th>
                </tr>

                <tr>
                    <th class="fs-3 text-uppercase">Marchandises dans le frigo </th>
                    <th class="text-center bg-warning fs-3"> {{ $totals['totalEntree'] - $totals['totalSortie'] }}</th>
                </tr>

                <tr>
                    <th class="fs-3 text-uppercase">Caisses vides non remplis  </th>
                    <th class="text-center bg-warning fs-3">  {{ ($totals['caisseVide'] - $totals['caisseRetour']) - ($totals['totalEntree'] - $totals['totalSortie']) }}</th>
                </tr>
            </table> 


            {{-- <p class="bg-light p-2 border mt-3 fs-3">
                Caisses vides retirées 
                <span class="fs-3 border p-2 ms-2 d-inline-block bg-warning" style="min-width: 60px; text-align: center;">
                    {{ $totals['caisseVide'] - $totals['caisseRetour'] }}
                </span>
            </p>

            <p class="bg-light p-2 border mt-2 fs-3">
                Marchandises dans le frigo 
                <span class="fs-3 border p-2 ms-2 d-inline-block bg-warning" style="min-width: 60px; text-align: center;">
                    {{ $totals['totalEntree'] - $totals['totalSortie'] }}
                </span>
            </p>

            <p class="bg-light p-2 border mt-2 fs-3">
                Caisses vides non remplis 
                <span class="fs-3 border p-2 ms-2 d-inline-block bg-warning" style="min-width: 60px; text-align: center;">
                    {{ ($totals['caisseVide'] - $totals['caisseRetour']) - ($totals['totalEntree'] - $totals['totalSortie']) }}
                </span>
            </p> --}}
            {{-- <p class="bg-light p-2 border mt-3 fs-3">Caisses vides retirées = <span class="fs-3">{{ $totals['caisseVide'] - $totals['caisseRetour'] }}</span> </p>
            <p class="bg-light p-2 border mt-2 fs-3">Marchandises dans le frigo = <span class="fs-3">{{ $totals['totalEntree'] - $totals['totalSortie'] }}</span> </p>
            <p class="bg-light p-2 border mt-2 fs-3">Caisses vides non remplis = <span class="fs-3">{{ ($totals['caisseVide'] - $totals['caisseRetour']) - ($totals['totalEntree'] - $totals['totalSortie']) }}</span> </p> --}}

        </section>
    <style>
        @media print {
                .noPrint  {
                    display: none;
                }

                .dt-buttons
                {
                    display: none;
                }
                .dataTables_filter
                {
                    display: none;
                }
                .dataTables_info
                {
                    display: none;
                }
                .pagination
                {
                    display: none;
                }
            }
        .listStockage .five-menu-item {
                color: rgb(0, 202, 91);
                font-size: 19px;
                font-weight: bold;
            }
    </style>
    <script>


    function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape', 'pt', 'a4');
            const pageWidth = doc.internal.pageSize.getWidth();

            const currentDate = new Date().toLocaleDateString();

            const tableElement = document.getElementById('tableBilan');
            const tableHeader = tableElement.querySelector('thead');
            const tableBody = tableElement.querySelector('tbody');

            // Extract headers
            const headers = Array.from(tableHeader.querySelectorAll('th')).map(th => th.innerText);

            // Extract body rows
            const rows = Array.from(tableBody.querySelectorAll('tr')).map(row => {
                return Array.from(row.querySelectorAll('td')).map(cell => cell.innerText);
            });

            // Create a table in PDF
            doc.autoTable({
                head: [headers],
                body: rows,
                startY: 40,
                styles: {
                    fontSize: 8,
                    cellPadding: 3,
                    overflow: 'linebreak'
                },
                columnStyles: {
                    0: {cellWidth: 'auto'},
                },
                margin: {top: 30, left: 10, right: 10},
                didDrawPage: function (data) {
                    // Center the title
                    doc.setFontSize(14);
                    const title = "Le bilan général";
                    const titleWidth = doc.getTextWidth(title);
                    const titleX = (pageWidth - titleWidth) / 2;
                    doc.setFillColor(240, 240, 240); // Light grey background
                    doc.rect(titleX - 5, 10, titleWidth + 10, 20, 'F');
                    doc.text(title, titleX, 25);

                    // Print current date on the top right
                    doc.setFontSize(10);
                    const dateWidth = doc.getTextWidth(currentDate);
                    doc.text(currentDate, pageWidth - dateWidth - 10, 10);
                }
            });

            doc.save('Le bilan général.pdf');
        }
        </script>
        <script>
            $(document).ready(function ()
            {
                var currentPath = window.location.pathname;
                var pathSegments = currentPath.split('/');
                var lastSegment = pathSegments[pathSegments.length - 1];
                if(lastSegment === "bilanGeneral")
                {

                        $('.list-unstyled .listStockage > ul').css('display', 'block');

                        $('.list-unstyled li:has(span:contains("Situation de stockage"))').css({
                            'background-color': 'rgb(159 226 212)',
                            'box-shadow' :'5px 5px 10px #888888',
                            'border-top-right-radius': '10px',
                            'border-bottom-right-radius': '10px'
                        }).find('i[data-feather="users"]').addClass('text-white');


                }
                $('#tableBilan').DataTable({
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
    </div>
</div>
    
@endsection