@extends('dashboard.index')

@section('dashboard')
<script src="{{asset("js/ListOrigins/script.js")}}"></script>

<script>
    var csrf_token                     = "{{csrf_token()}}";
    var AddProduct                     = "{{url('AddProduct')}}";
    var UpdateProduct                   = "{{url('UpdateProduct')}}";
    var ListOrigin                     = "{{url('ListOrigin')}}";
    var DeleteProduct                  = "{{url('DeleteProduct')}}";
    
</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Liste de produits </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                        <li class="breadcrumb-item active">List de produits</li>
                    </ol>
                </div>
            </div>

            <div class="row"> 
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class=" mb-3">
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddProduct">Ajoute un produit</button>
                                <a href="{{url('Setting')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                                <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                            </div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table Table_Product" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" >Titre</th>
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


       

        <div class="modal fade" id="ModalAddProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajoute un produit</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddProduct"></ul>
                            <form id="FormAddProduct">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Titre <span class="text-danger">*</span></label>
                                            <input id="TitleProdutct" type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="AddProduct">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalEditProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier compagnie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationEditProduct"></ul>
                            <form id="FormEditProduct">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Titre <span class="text-danger">*</span></label>
                                            <input id="TitleProdutctEdit" type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="EditProduct">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        
        
        
</div>



@endsection