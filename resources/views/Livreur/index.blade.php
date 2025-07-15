@extends('dashboard.index')

@section('dashboard')
<script src="{{asset("js/livreur/script.js")}}"></script>
<script>
    var csrf_token                      = "{{csrf_token()}}";
    var AddLiveurs                       = "{{url('Addlivreur')}}";
    var UpdateLivreurs                   = "{{url('UpdateLivreurs')}}";
    var livreur                         = "{{url('livreur')}}";
    var deleteLivreur                         = "{{url('deleteLivreur')}}";
</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">List de livreur </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                        <li class="breadcrumb-item active">Compagnie</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mb-3">
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddLivreurs">Ajoute livreur</button>
                                <a href="{{url('Setting')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                                <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                            </div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table Table_Liveurs" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" >Nom complet</th>
                                                    <th data-sortable="true" >C.I.N</th>
                                                    <th data-sortable="true" >Matricule</th>
                                                    <th data-sortable="true" >Téléphone</th>
                                                    <th data-sortable="true" >Photo C.I.N</th>
                                                    <th data-sortable="true" >Créer par</th>
                                                    <th data-sortable="true" >Créer le</th>
                                                    <th data-sortable="true" >Action</th>
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


       

        <div class="modal fade" id="ModalAddLivreurs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajoute livreur</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddLiveur"></ul>
                            <form id="FormAddLivreur" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xl-6">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                            <input id="NomClient" type="text" class="form-control" name="name" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">C.I.N <span class="text-danger">*</span></label>
                                            <input id="CinLiveur" type="text" class="form-control" name="cin" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Photo C.I.N <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="PhotoCinLivreur" name="image_cin[]" multiple>
                                        </div>

                                        
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-xl-6">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Matricule <span class="text-danger">*</span></label>
                                            <input id="Matricule" type="text" class="form-control" name="matricule" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                            <input id="PhoneLiveur" type="text" class="form-control" name="phone" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="AddLivreurs">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalEditLivreurs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier livreur</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationEditLiveur"></ul>
                            <form id="FormLivreurUpdate" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xl-6">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Nom complet<span class="text-danger">*</span></label>
                                            <input id="nameLivreurEdit" type="text" class="form-control" name="name" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>
    
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Matricule <span class="text-danger">*</span></label>
                                            <input id="matriculeLivreurEdit" type="text" class="form-control" name="matricule" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">Photo C.I.N <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="PhotoCinLivreurUpdate" name="image_cin[]" multiple>
                                        </div>
    
                                        
                                    </div>
    
                                    <div class="col-sm-12 col-md-12 col-xl-6">
                                        
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">C.I.N <span class="text-danger">*</span></label>
                                            <input id="cinLivreurEdit" type="text" class="form-control" name="cin" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>
    
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                            <input id="phoneLivreurEdit" type="text" class="form-control" name="phone" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="EditLivreurs">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
</div>



@endsection