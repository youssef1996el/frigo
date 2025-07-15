@extends('dashboard.index')

@section('dashboard')
<script src="{{asset("js/client/script.js")}}"></script>
<link rel="stylesheet" href="{{asset('css/Client/style.css')}}">
<script>
    var csrf_token                      = "{{csrf_token()}}";
    var AddClient                       = "{{url('AddClient')}}";
    var UpdateClient                   = "{{url('UpdateClient')}}";
    var client                         = "{{url('client')}}";
    var DisplayContract                = "{{url('DisplayContract')}}";
    var DisplayCIN                     = "{{url('DisplayCIN')}}"
</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid"> 

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">List de client </h4>
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
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddClient">Ajoute client</button>
                                <a href="{{url('Setting')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                                <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                            </div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table Table_Client" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" >Nom</th>
                                                    <th data-sortable="true" >Prénom</th>
                                                    <th data-sortable="true" >C.I.N</th>
                                                    <th data-sortable="true" >Adresse</th>
                                                    <th data-sortable="true" >Téléphone</th>
                                                    <th data-sortable="true">Image C.I.N</th>
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


       

        <div class="modal fade" id="ModalAddClient" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajoute client</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddClient"></ul>
                            <form id="FormAddClient" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                                            <input id="NomClient" type="text" class="form-control" name="firstname" required>
                                        </div>
        
                                        <div class="form-group mb-3">
                                            <label class="form-label">CIN <span class="text-danger">*</span></label>
                                            <input id="CinClient" type="text" class="form-control" name="cin" required>
                                        </div>
        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                            <input id="PhoneClient" type="text" class="form-control" name="phone" required>
                                        </div>
                                    </div>
        
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                            <input id="PrenomClient" type="text" class="form-control" name="lastname" required>
                                        </div>
        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                            <input id="AddressClient" type="text" class="form-control" name="address" required>
                                        </div>
        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Photo C.I.N <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="PhotoCinClient" name="photo_cin[]" multiple>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="AddClient">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalEditClient" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier compagnie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationEditClient"></ul>
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-xl-6">
                                    <div class="form-group mb-3 password-container">
                                        <label for="password" class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input id="NomClientEdit" type="text" class="form-control" name="nameCompany" required >
                                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                                    </div>

                                    <div class="form-group mb-3 password-container">
                                        <label for="password" class="form-label">Cin <span class="text-danger">*</span></label>
                                        <input id="CinClientEdit" type="text" class="form-control" name="nameCompany" required >
                                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                                    </div>

                                    <div class="form-group mb-3 password-container">
                                        <label for="password" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                        <input id="PhoneClientEdit" type="text" class="form-control" name="nameCompany" required >
                                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-xl-6">
                                    <div class="form-group mb-3 password-container">
                                        <label for="password" class="form-label">Prénom <span class="text-danger">*</span></label>
                                        <input id="PrenomClientEdit" type="text" class="form-control" name="nameCompany" required >
                                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                                    </div>

                                    <div class="form-group mb-3 password-container">
                                        <label for="password" class="form-label">Adresse <span class="text-danger">*</span></label>
                                        <input id="AddressClientEdit" type="tel" class="form-control" name="nameCompany" required >
                                        <i class="fa-solid fa-eye" id="togglePassword"></i>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="EditClient">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalDisplayImageCIN" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row w-100">
                            <div class="col-sm-12 col-md-12 col-xl-6">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Photo C.I.N</h1>
                            </div>
                            <div class="col-sm-12 col-md-12 col-xl-6 d-flex justify-content-end">
                                <button id="printImage" class="btn btn-primary">Imprimer</button>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center ContentForPictureCinCustomer card shadow p-3 m-3"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <style>
            

        </style>
        <div class="modal fade" id="ModalDisplayContract" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Afficher le contrat ou télécharger un nouveau contrat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('contracts.upload') }}" method="POST" enctype="multipart/form-data">
                         @csrf
                        <div class="modal-body text-center">
                            <div class="card text-start">
                                <div class="card-body shadow">
                                    <div id="contractsList"></div>
                                    {{-- <h4 class="card-title" id="DivContentLinkContract">
                                        <a href="#">link</a>
                                    </h4> --}}
                                </div>
                            </div>
                            <div class="card text-start shadow">
                                <img class="card-img-top" />
                                <div class="card-body">
                                    <h4 class="card-title border p-2 rounded-2 text-primary text-center text-uppercase border-dark">Télécharger contrat client</h4>
                                    <div class="modalSecound d-inline-block ">
                                        <div class="modal-bodySecound ">
                                            <label class="custum-file-upload" for="file">
                                                <div class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                        <path d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z"></path>
                                                    </svg>
                                                </div>
                                                <div class="text">
                                                    <span id="fileName">Cliquez pour télécharger contrat</span>
                                                </div>
                                                <input type="file" id="file" onchange="displayFileName()" name="contract_file" required>
                                                <input type="hidden" name="idclient" id="idclient_contract" value="1">
                                                <input type="hidden" name="idcompany" id="idcompany_contract" value="2">
                                            </label>
                                    
                                            <script>
                                                function displayFileName() {
                                                    const fileInput = document.getElementById("file");
                                                    const fileNameDisplay = document.getElementById("fileName");
                                                    if (fileInput.files.length > 0) {
                                                        fileNameDisplay.textContent = fileInput.files[0].name;
                                                    } else {
                                                        fileNameDisplay.textContent = "Cliquez pour télécharger contrat";
                                                    }
                                                }
                                            </script>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary" >Télécharger le fichier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        
</div>



@endsection