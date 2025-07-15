@extends('dashboard.index')

@section('dashboard')
<div class="content-page">
    <div class="content">

        <!-- Début du contenu -->
        <div class="container-fluid ">
            <div class="card card-body py-3 mt-3">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="d-sm-flex align-items-center justify-space-between">
                            <h4 class="mb-4 mb-sm-0 card-title"> Entrée de marchandises </h4>
                            <nav aria-label="breadcrumb" class="ms-auto">
                                <ol class="breadcrumb">
                                   
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-6 bg-primary-subtle text-primary">
                                            Détail  entrée de marchandises 
                                        </span>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="widget-content searchable-container list">
                <div class="card card-body">
                    <h5 class="card-title border p-2 bg-light rounded-2 mb-4">Information client par  entrée de marchandises  N° {{$id}}</h5>
                    <div class="row">
                        <div class="col-md-12 col-xl-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="" style="min-width: 115px">Nom client :</label>
                                    <span class="border p-2 bg-light rounded-2">{{$Clients->firstname}}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="" style="min-width: 115px">Prénom client :</label>
                                    <span class="border p-2 bg-light rounded-2">{{$Clients->lastname}}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="" style="min-width: 115px">Adresse client :</label>
                                    <span class="border p-2 bg-light rounded-2">{{$Clients->address}}</span>
                                </div>
                            </div>
                            
        
                        </div>
                        <div class="col-md-12 col-xl-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="" style="min-width: 115px">Téléphone client :</label>
                                    <span class="border p-2 bg-light rounded-2">{{$Clients->phone}}</span>
                                </div>
                                <div class="mb-4">
                                    <label for="" style="min-width: 115px">C.I.N client :</label>
                                    <span class="border p-2 bg-light rounded-2">{{$Clients->cin}}</span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="card card-body">
                <h5 class="card-title border p-2 bg-light rounded-2">Fiche détail entrée marchandise  </h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped TableLineOrder">
                        <thead>
                            <tr>
                                <th data-sortable="true" >C.I.N (Chauffeur)</th>
                                <th data-sortable="true" >Matricule</th>
                                <th data-sortable="true" >Chauffeur</th>
                                <th data-sortable="true" >Produit</th>
                                <th data-sortable="true" >Client</th>
                                <th data-sortable="true" >Nombre caisse</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $SumTotalCaisse = 0;
                            @endphp
                            @foreach ($Data as $value)
                                @php
                                    $SumTotalCaisse += $value->total_quantity;
                                @endphp
                                <tr>
                                    <td>{{$value->cin}}</td>
                                    <td>{{$value->matricule}}</td>
                                    <td>{{$value->livreur}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->name_client}}</td>
                                    <td class="text-end">{{$value->total_quantity}}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex">
                        <div class="flex-fill"></div>
                        <div class="flex-fill">
                            <table class="table table-striped table-bordered">
                                <tbody><tr>
                                    <th>Total nombre caisse</th>
                                    <th class="text-end">{{$SumTotalCaisse}}</th>
                                </tr>
                                
                            </tbody></table>
                        </div>
                    </div>
        
        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection