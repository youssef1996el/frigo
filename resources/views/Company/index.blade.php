@extends('dashboard.index')

@section('dashboard')
<script src="{{asset('js/company/script.js')}}"></script>

<script>
    var csrf_token                      = "{{csrf_token()}}";
    var AddCompany                      = "{{url('AddCompany')}}";
    var UpdateCompany                   = "{{url('UpdateCompany')}}";
    var company                         = "{{url('company')}}";
    var SaveClientByCompany             = "{{url('SaveClientByCompany')}}";
    var SaveLivreurByCompany            = "{{url('SaveLivreurByCompany')}}";
    var SaveProductByCompany            = "{{url('SaveProductByCompany')}}";
    var DisplayClientBycompany          = "{{url('DisplayClientBycompany')}}";
    var DisplayLivreurBycompany         = "{{url('DisplayLivreurBycompany')}}";
    var DisplayProductBycompany         = "{{url('DisplayProductBycompany')}}";

</script>
<style>
    #TableClientByCompany_length
    {
        margin-bottom: 30px !important;
    }
</style>

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Compagnie </h4>
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
                                <button class="btn btn-primary"        style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddCompany">Ajoute compagnie</button>
                                <button class="btn bg-primary-subtle"  style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalClientByCompany" id="BtnDisplayClient">Accorder le client à compagnie</button>
                                <button class="btn btn-info"           style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalLivreurByCompany" id="BtnDisplayLivreur">Accorder le livreur à compagnie</button>
                                <button class="btn btn-secondary"      style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalProductByCompany" id="BtnDisplayProduct">Accorder le produit à compagnie</button>
                                <a href="{{url('Setting')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                                <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                            </div>

                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">

                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table Table_Company" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" style="width: 35.52729992520568%;">
                                                        Compagnie
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

        <div class="modal fade" id="ModalAddCompany" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajoute compagnie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddCompany"></ul>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Nom compagnie</label>
                                <input id="nameCompany" type="text" class="form-control" name="nameCompany" required >
                                <i class="fa-solid fa-eye" id="togglePassword"></i>
                            </div>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Status</label>
                                <select name="" id="statusCompany" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Désactiver</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="AddCompany">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ModalEditCompany" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier compagnie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddCompany"></ul>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Nom compagnie</label>
                                <input id="nameCompanyEdit" type="text" class="form-control" name="nameCompany" required >
                                <i class="fa-solid fa-eye" id="togglePassword"></i>
                            </div>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Status</label>
                                <select name="" id="statusCompanyEdit" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Désactiver</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="EditCompany">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ModalClientByCompany" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Clients compagnie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                        <div class="modal-body">
                            <div class="pt-0">


                                <ul class="ValidationAddCompany"></ul>
                                <div class="form-group mb-3 password-container">
                                    <label for="password" class="form-label">Nom compagnie</label>
                                    <select name="idcompany" id="select-company" class="form-select">
                                        @foreach ($ListCompany as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="role" value="Client" hidden>
                                </div>
                                <table class="table table-responsive table-bordered" id="TableClientByCompany">
                                    <thead>
                                        <tr >
                                            <th>Clients</th>
                                            <th class="text-center">Ajouter</th>
                                            <th class="text-center">Supprimer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Clients as $item)
                                            <tr>
                                                <td>{{$item->firstname . ' ' . $item->lastname}}</td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input ajouter" type="checkbox" value="{{$item->id}}" name="ajouter[]" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input supprimer" type="checkbox" value="{{$item->id}}" name="supprimer[]" />
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                            <button type="button" class="btn btn-primary" id="SaveClientByCompany">Sauvegarder</button>
                        </div>

                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalLivreurByCompany" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Livreurs compagnie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="pt-0">

                            <ul class="ValidationAddCompany"></ul>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Nom compagnie</label>
                                <select name="idcompany" id="CompanyLivreur" class="form-select">
                                    @foreach ($ListCompany as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="role" value="Livreur" hidden>
                            </div>

                            <table class="table table-responsive table-bordered" id="TableLivreurByCompany">
                                <thead>
                                    <tr >
                                        <th>Livreur</th>
                                        <th class="text-center">Ajouter</th>
                                        <th class="text-center">Supprimer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Livreurs as $item)
                                        <tr>
                                            <td>{{$item->name}}</td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input ajouter" type="checkbox" value="{{$item->id}}" name="ajouter[]" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input supprimer" type="checkbox" value="{{$item->id}}" name="supprimer[]" />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="SaveLivreurByCompany">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalProductByCompany" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Produit compagnie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="pt-0">

                            <ul class="ValidationAddCompany"></ul>
                            <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Nom compagnie</label>
                                <select name="idcompany" id="CompanyProduct" class="form-select">
                                    @foreach ($ListCompany as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="role" value="Product" hidden>
                            </div>
                            <table class="table table-responsive table-bordered" id="TableProductByCompany">
                                <thead>
                                    <tr >
                                        <th>Produit</th>
                                        <th class="text-center">Ajouter</th>
                                        <th class="text-center">Supprimer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Products as $item)
                                        <tr>
                                            <td>{{$item->name}}</td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input ajouter" type="checkbox" value="{{$item->id}}" name="ajouter[]" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input supprimer" type="checkbox" value="{{$item->id}}" name="supprimer[]" />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <div class="form-group mb-3 password-container">
                                <label for="password" class="form-label">Produits</label>
                                <select name="idpermission[]"  class="form-select" multiple>

                                    @foreach ($Products as $item)
                                        <option value="{{ $item->id }}">{{ $item->name}}</option>
                                    @endforeach
                                </select>

                            </div> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="SaveProductByCompany">Sauvegarder</button>
                    </div>

                </div>
            </div>
        </div>




</div>



@endsection
