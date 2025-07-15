@extends('dashboard.index')

@section('dashboard')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<div class="content-page">
    <div class="content">
        <section class="mt-3">
            <div class="row bg-light p-2 rounded-2 border">
                <div class="col-sm-12 col-md-6 col-xl-6">
                    <h3 class="">Table Entrée de  Marchandise</h3>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6">
                    <a href="{{url('Stitaution')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                    <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                </div>
            </div>
        
            <form action="{{url('Stituation_MarchandiseEntree')}}" method="get">
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

            <div style="overflow-x: auto">
                @php
                        $bgColors = [ 'bg-info', 'bg-warning', 'bg-primary', 'bg-danger', 'bg-secondary'];
                        shuffle($bgColors); // لخلط الألوان عشوائيًا
                        $clientBgClasses = [];
                        $i = 0;
                        foreach ($clientsMarchEntree as $client) {
                            // إذا خلصت الألوان نعيد الخلط
                            if (!isset($bgColors[$i])) {
                                $bgColors = [ 'bg-info', 'bg-warning', 'bg-primary', 'bg-danger', 'bg-secondary'];
                                shuffle($bgColors);
                                $i = 0;
                            }
                            $clientBgClasses[$client] = $bgColors[$i];
                            $i++;
                        }
                    @endphp
                <table class="table table-striped table-bordered" id="my-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Date</th>
                            @foreach ($clientsMarchEntree as $client)
                                <th  class="text-center text-dark {{-- {{ $clientBgClasses[$client] }} --}}">{{ $client }}</th>
                            @endforeach
                            <th  class="text-center">Total</th>
                        </tr>
                        {{-- <tr>
                            @foreach ($clientsMarchEntree as $client)
                                <th>Nombre</th>
                                <th>Cumul</th>
                            @endforeach
                            <th class="text-center">Total nombre par jour</th>
                            <th class="text-center">Total cumul par jour</th>
                        </tr> --}}
                    </thead>

                    <tbody>
                        @foreach ($dataMarchEntree as $date => $clientsData)
                            <tr>
                                <td style="white-space: nowrap">{{ $date }}</td>
                                @foreach ($clientsMarchEntree as $client)
                                    <td class="text-center text-dark {{-- {{ $clientBgClasses[$client] }} --}}">{{ intval($clientsData[$client]['nombre']) }}</td>
                                    {{-- <td class="text-center text-white {{ $clientBgClasses[$client] }}">{{ intval($clientsData[$client]['Cuml']) }}</td> --}}
                                @endforeach
                                <td class="text-center">{{ intval($totalsMarchEntree[$date]['totalNombre']) }}</td>
                                {{-- <td class="text-center">{{ intval($totalsMarchEntree[$date]['totalCuml']) }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td class=" text-dark">Totaux</td>
                            @foreach ($clientsMarchEntree as $client)
                                @php
                                    $sumNombre = 0;
                                    $sumCuml = 0;
                                    foreach ($dataMarchEntree as $date => $clientsData) {
                                        $sumNombre += $clientsData[$client]['nombre'];
                                        $sumCuml += $clientsData[$client]['Cuml'];
                                    }
                                @endphp
                                <td class=" text-dark text-center">{{ $sumNombre }}</td>
                                {{-- <td class="bg-success text-white text-center">{{ $sumCuml }}</td> --}}
                            @endforeach
                            <td class=" text-dark text-center">{{ $totalsMarchEntree['grandTotalNombre'] }}</td>
                            {{-- <td class="bg-success text-white text-center">{{ $totalsMarchEntree['grandTotalCuml'] }}</td> --}}
                        </tr>
                    </tfoot>
                </table>

            </div>


        </section>

        <style>
            .listStockage .secound-menu-item {
                color: rgb(0, 202, 91);
                font-size: 19px;
                font-weight: bold;
            }
            @media print {
                .btn-secondary {
                    display: none;
                }


            }
            #bodywrapper
            {
                overflow-x: auto;
            }
        </style>
        <script>


            function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape', 'pt', 'a4');
            const totalColumns = document.querySelectorAll('#my-table thead th').length - 2;
            const maxColumnsPerPage = 10;
            const pagesNeeded = Math.ceil(totalColumns / maxColumnsPerPage);
            const pageWidth = doc.internal.pageSize.getWidth();

            const currentDate = new Date().toLocaleDateString();

            for (let page = 0; page < pagesNeeded; page++) {
                const startColumn = page * maxColumnsPerPage;
                const endColumn = Math.min(startColumn + maxColumnsPerPage, totalColumns);


                const headerColumns = Array.from(document.querySelectorAll('#my-table thead th')).slice(startColumn + 1, endColumn + 1);


                const headers = ["Date", ...headerColumns.map(th => th.innerText)];


                const isLastPage = (page === pagesNeeded - 1);

                if (isLastPage) {
                    headers.push("Total");
                }


                const rows = Array.from(document.querySelectorAll('#my-table tbody tr')).map(row => {
                    const cells = Array.from(row.querySelectorAll('td')).slice(startColumn + 1, endColumn + 1);
                    const rowData = [row.querySelector('td').innerText, ...cells.map(cell => cell.innerText)];
                    if (isLastPage) {
                        rowData.push(row.querySelector('td:last-child').innerText);
                    }
                    return rowData;
                });


                const footerCells = Array.from(document.querySelectorAll('#my-table tfoot tr td')).slice(startColumn + 1, endColumn + 1);
                const foot = [["Totaux", ...footerCells.map(td => td.innerText)]];
                if (isLastPage) {
                    foot[0].push(document.querySelector('#my-table tfoot tr td:last-child').innerText);
                }


                doc.autoTable({
                    head: [headers],
                    body: rows,
                    foot: foot,
                    startY: page === 0 ? 40 : doc.lastAutoTable.finalY + 20,
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

                        doc.setFontSize(14);
                        const title = "Table Entrée de Marchandise";
                        const titleWidth = doc.getTextWidth(title);
                        const titleX = (pageWidth - titleWidth) / 2;
                        doc.setFillColor(240, 240, 240);
                        doc.rect(titleX - 5, 10, titleWidth + 10, 20, 'F');
                        doc.text(title, titleX, 25);


                        doc.setFontSize(10);
                        const dateWidth = doc.getTextWidth(currentDate);
                        doc.text(currentDate, pageWidth - dateWidth - 10, 10);
                    }
                });
            }

            doc.save('Table Entrée de Marchandise.pdf');
        }
        </script>
        <script>
            $(document).ready(function () {
                var currentPath = window.location.pathname;
                var pathSegments = currentPath.split('/');
                var lastSegment = pathSegments[pathSegments.length - 1];
                if(lastSegment === "Entremarchandises")
                {

                        $('.list-unstyled .listStockage > ul').css('display', 'block');

                        $('.list-unstyled li:has(span:contains("Situation de stockage"))').css({
                            'background-color': 'rgb(159 226 212)',
                            'box-shadow' :'5px 5px 10px #888888',
                            'border-top-right-radius': '10px',
                            'border-bottom-right-radius': '10px'
                        }).find('i[data-feather="users"]').addClass('text-white');


                }
            });
        </script>
    </div>
</div>
    
@endsection