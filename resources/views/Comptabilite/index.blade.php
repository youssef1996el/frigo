@extends('dashboard.index')

@section('dashboard')
{{-- <script src="{{asset('js/company/script.js')}}"></script> --}}
<script src="{{asset('js/comptabilite/script.js')}}"></script>

<script>
    var csrf_token                      = "{{csrf_token()}}";
    var AddComptabilite                 = "{{url('AddComptabilite')}}";
    var UpdateComptabilite                   = "{{url('UpdateComptabilite')}}";
    var Comptabilite                    = "{{url('Comptabilite')}}";
</script>

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Comptabilité </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                        <li class="breadcrumb-item active">Comptabilité</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12"> 
                    <div class="card">

                        <div class="card-body">
                            <div class=" mb-3">
                                <button class="btn btn-primary"        style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddComptabilite">Ajoute comptabilité</button>
                                
                                <a href="{{url('Setting')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                                <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                            </div>
                            
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table Table_Comptabilite" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" style="width: 35.52729992520568%;">
                                                        Comptabilité
                                                    </th>
                                                    <th data-sortable="true" style="width: 11.518324607329843%;">Status
                                                        
                                                    </th>
                                                    <th data-sortable="true" style="width: 16.454749439042633%;">
                                                        Créer par
                                                        
                                                    </th>
                                                    <th data-sortable="true" style="width: 15.482423335826478%;">Créer le
                                                        
                                                    </th>
                                                    
                                                    <th data-sortable="true" style="width: 11.36873597606582%;">Action</th>
                                                </tr>
                                            </thead>
                                                <tbody>
                                                   
                                                </tbody>
                                            </table>
                                        </div>
                                                    
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


       

        <div class="modal fade" id="ModalAddComptabilite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajoute Comptabilité</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddComptabilite"></ul>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Nom Comptabilité</label>
                                <input id="nameComptabilite" type="text" class="form-control" name="nameComptabilite" required >
                                <i class="fa-solid fa-eye" id="togglePassword"></i>
                            </div>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Status</label>
                                <select name="" id="statusComptabilite" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Désactiver</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="AddComptabilite">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ModalEditComptabilite" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier Comptabilité</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddComptabilite"></ul>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Nom Comptabilité</label>
                                <input id="nameComptabiliteEdit" type="text" class="form-control" name="nameComptabilite" required >
                                <i class="fa-solid fa-eye" id="togglePassword"></i>
                            </div>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Status</label>
                                <select name="" id="statusComptabiliteEdit" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Désactiver</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="EditComptabilite">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>

</div>



@endsection