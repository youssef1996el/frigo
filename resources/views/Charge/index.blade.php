@extends('dashboard.index')
@section('dashboard')
<script src="{{asset('js/Charge/script.js')}}"></script>
<script>
    var Charge                   = "{{url('Charge')}}";
    var update                   = "{{url('update')}}";
    var store                    = "{{url('storeCharge')}}";
    var Destroy                    = "{{url('Destroy')}}";
    var csrf_token               = "{{csrf_token()}}";
</script>
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">List de charge </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                        <li class="breadcrumb-item active"> Charge </li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mb-3">
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddCharge">Ajouter opération</button>
                                <a href="{{url('Setting')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                                <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                            </div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table table-hover   datatable datatable-table Table_Charge" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" >Libelle</th>
                                                    <th data-sortable="true" >Compagnie</th>
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


                <!-- Modal تعديل Charge -->
                <div class="modal fade" id="EditChargeModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="FormEditCharge">
                            <ul class="ValidationCharge"></ul>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier charge</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Ferme"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Libellé</label>
                                        <input type="text" name="libelle" class="form-control" id="libelle" required>
                                    </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="BtnUpdateCharge">Modifier charge</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="modal fade" id="ModalAddCharge" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="FormAddCharge">
                            <ul class="ValidationChargeAdd"></ul>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ajouter charge</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Ferme"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Libellé</label>
                                        <input type="text" name="libelle" class="form-control" id="libelle" required>
                                    </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="BtnAddCharge">Sauvegarder</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@endsection