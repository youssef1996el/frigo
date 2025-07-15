@extends('dashboard.index')

@section('dashboard')
<script src="{{asset("js/CaisseVides/script.js")}}"></script>
<script>
    var csrf_token                      = "{{csrf_token()}}";
    var AddCaisseVides                  = "{{url('AddCaisseVides')}}";
    var UpdateCaisseVide                = "{{url('UpdateCaisseVide')}}";
    var DeleteCaisseVide                = "{{url('DeleteCaisseVide')}}";
    var caissesortie                    = "{{url('caissesortie')}}";
    var GetNomberCaisseByClient                              = "{{url('GetNomberCaisseByClient')}}";
    var PrintCaisseVide                              = "{{url('PrintCaisseVide')}}";
</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-bold m-0 text-center text-uppercase">List de sortie de caisses vides </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                        <li class="breadcrumb-item active"> Sortie de caisses vides </li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mb-3">
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddCaisseSortie">Ajouter opération</button>
                                <a href="{{url('/home')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                            </div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table table-hover   datatable datatable-table Table_CaisseVide" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" >Client</th>
                                                    <th data-sortable="true" >Nombre</th>
                                                    <th data-sortable="true" >Cumul</th>
                                                    <th data-sortable="true" >Nom livreur</th>
                                                    <th data-sortable="true" >C.I.N (livreur)</th>
                                                    <th data-sortable="true" >Matricule</th>
                                                    <th data-sortable="true" >Type opération</th>
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


       

        <div class="modal fade" id="ModalAddCaisseSortie" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter Bon de Sortie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddCaisseVide"></ul>
                            <form id="FormAddCaisseVide">
                                <div class="row ">
                                    <div class="col-sm-12 col-md-12 col-xl-3 ">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-success w-100 text-center fs-3 text-white rounded-2">Client <span class="text-danger">*</span></label>
                                            <select name="idclient" id="idclient" class="form-select">
                                                <option value="0">Veuiller séléctionner le client</option>
                                                @foreach ($Clients as $value)
                                                    <option value={{$value->id}}>{{$value->firstname ." ". $value->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div> 
                                    </div> 

                                     <div class="col-sm-12 col-md-12 col-xl-3">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-warning w-100 text-center fs-3 text-white rounded-2">Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="number_box" placeholder="Nombre">
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-xl-3">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-primary w-100 text-center fs-3 text-white rounded-2">Livreur <span class="text-danger">*</span></label>
                                            <select name="idlivreur" id="" class="form-select">
                                                <option value="0">Veuiller séléctionner le livreur</option>
                                                @foreach ($Livreurs as $value)
                                                    <option value={{$value->id}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                   

                                    <div class="col-sm-12 col-md-12 col-xl-3">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-secondary w-100 text-center fs-3 text-white rounded-2">Etranger <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="etranger" placeholder="Etranger">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="card m-auto p-3 bg-light">
                                <div class="row">
                                    <div class="col-md-8 col-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Total nombre de caisse vide est :</th>
                                                <th id="NombreBoxCaisseVide">0</th>
                                            </tr>
                                            <tr>
                                                <th>Total nombre de marchandise entrée est :</th>
                                                <th id="NombreBoxMarchandisEntree">0</th>
                                            </tr>
                                            <tr>
                                                <th>Total nombre de marchandise sortie est :</th>
                                                <th id="NombreBoxMarchandisSortie">0</th>
                                            </tr>
                                            <tr>
                                                <th>Total nombre de caisse retour est :</th>
                                                <th id="NombreBoxCaisseRetour">0</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="AddCaisseVides">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalEditCaisseVide" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier livreur</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationUpdateCaisseVide"></ul>
                            <form id="FormCaisseVidesUpdate">
                                <div class="row ">
                                    <div class="col-sm-12 col-md-12 col-xl-4 ">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-success w-100 text-center fs-3 text-white rounded-2">Client <span class="text-danger">*</span></label>
                                            <select name="idclient" id="idclientEdit" class="form-select">
                                                <option value="0">Veuiller séléctionner le client</option>
                                                @foreach ($Clients as $value)
                                                    <option value={{$value->id}}>{{$value->firstname ." ". $value->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-xl-4">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-primary w-100 text-center fs-3 text-white rounded-2">Livreur <span class="text-danger">*</span></label>
                                            <select name="idlivreur" id="idlivreurEdit" class="form-select">
                                                <option value="0">Veuiller séléctionner le livreur</option>
                                                @foreach ($Livreurs as $value)
                                                    <option value={{$value->id}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-xl-4">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-warning w-100 text-center fs-3 text-white rounded-2">Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="number_box" placeholder="Nombre" id="number_boxEdit">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="UpdateCaisseVides">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>






</div>
<!-- Modal trigger button -->





<script>
    window.Laravel = {
        ErrorsInfos: @json(session('ErrorsInfos')),
        ErrorsNumberStartBon : @json(session('ErrorsNumberStartBon'))
    };
</script>





@endsection