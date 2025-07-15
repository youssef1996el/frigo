@extends('dashboard.index')

@section('dashboard')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.21/jspdf.plugin.autotable.min.js"></script>
<div class="content-page">
    <div class="content">
        <section class="mt-3">
            <div class="row bg-light p-2 rounded-2 border">
                <div class="col-sm-12 col-md-6 col-xl-6">
                    <h3 class="">Sitution  par le client</h3>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6">
                    <a href="{{url('Stitaution')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                    <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                </div>
            </div>
            <form action="{{url('showClientSituation')}}" method="get">
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
                        
                    </div>
                </div>
            </form>
            
            <table id="clientTable" class="table table-striped table-bordered  text-center">
                <thead class="thead-dark">
                    <tr>
                        <th class="align-middle text-center">Clients</th>
                        <th class="text-center">Caisse vides retirées</th>
                        <th class="text-center">Marchandises en stock</th>
                        <th class="text-center">Caisses vide non remplis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td>{{ $row['client'] }}</td>
                            <td>{{ $row['caisse_vide']}}</td>
                            <td>{{ $row['marchandise'] }}</td>
                            <td>{{   $row['caisse_vide']     - $row['marchandise'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">لا توجد بيانات متاحة</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-weight-bold ">
                    <tr>
                        <td class="text-center bg-success text-white">Total</td>
                        <td class="text-center bg-success text-white">{{ $totalCaisse }}</td>
                        <td class="text-center bg-success text-white">{{ $totalMarchandise }}</td>
                        <td class="text-center bg-success text-white">{{ $totalCaisse - $totalMarchandise }}</td>
                    </tr>
                </tfoot>
            </table>

        </section>  
    </div>
</div>
<script>
    $(document).ready(function () {
        var clientTable = $('#clientTable').DataTable({
            
            language: {
                "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
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
            }
        });
    });
</script>


    
@endsection
