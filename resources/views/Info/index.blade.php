@extends('dashboard.index')

@section('dashboard')
<script src="{{asset("js/Info/script.js")}}"></script>
<script>
    var csrf_token                      = "{{csrf_token()}}";
    var AddInformation                       = "{{url('AddInformation')}}";
    var UpdateInformation                   = "{{url('UpdateInformation')}}";
    var Info                         = "{{url('Info')}}";
    var DeleteInformation                         = "{{url('DeleteInformation')}}";
</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">List de information </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                        <li class="breadcrumb-item active">Information</li>
                    </ol>
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class=" mb-3">
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddInformation">Ajoute information</button>
                                <a href="{{url('/Bons')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                                <a href="{{url('home')}}" class="btn btn-primary float-end "         style="margin-right: 5px" >Page d'accueil</a>          

                            </div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table Table_Information" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" >Titre</th>
                                                    <th data-sortable="true" >Télephone</th>
                                                    <th data-sortable="true" >ICE</th>
                                                    <th data-sortable="true" >IF</th>
                                                    <th data-sortable="true" >CAPITAL</th>
                                                    <th data-sortable="true" >CARTE BANCAIRE</th>
                                                    <th data-sortable="true" >SOCIETE</th>
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


       

        <div class="modal fade" id="ModalAddInformation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajoute information</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationAddInformation"></ul>
                            <form id="FormAddInfo" >
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xl-6">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Titre <span class="text-danger">*</span></label>
                                            <input id="title" type="text" class="form-control" name="name" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                            <input id="phone" type="text" class="form-control" name="phone" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">ICE <span class="text-danger">*</span></label>
                                            <input id="ICE" type="text" class="form-control" name="ice" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">SOCIETE <span class="text-danger">*</span></label>
                                            <input id="SOCIETE" type="text" class="form-control" name="companie" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-xl-6">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">IF <span class="text-danger">*</span></label>
                                            <input id="IF" type="text" class="form-control" name="if" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">CAPITAL <span class="text-danger">*</span></label>
                                            <input id="CAPITAL" type="text" class="form-control" name="capital" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">CARTE BANCAIRE <span class="text-danger">*</span></label>
                                            <input id="CARTEBANCAIRE" type="text" class="form-control" name="cb" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="AddInfo">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalEditInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier information</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationEditInfo"></ul>
                            <form id="FormInfoUpdate" >
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-xl-6">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Titre <span class="text-danger">*</span></label>
                                            <input id="titleEdit" type="text" class="form-control" name="name" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                            <input id="phoneEdit" type="text" class="form-control" name="phone" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">ICE <span class="text-danger">*</span></label>
                                            <input id="ICEEdit" type="text" class="form-control" name="ice" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">SOCIETE <span class="text-danger">*</span></label>
                                            <input id="SOCIETEEdit" type="text" class="form-control" name="companie" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-xl-6">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">IF <span class="text-danger">*</span></label>
                                            <input id="IFEdit" type="text" class="form-control" name="if" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">CAPITAL <span class="text-danger">*</span></label>
                                            <input id="CAPITALEdit" type="text" class="form-control" name="capital" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>

                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label">CARTE BANCAIRE <span class="text-danger">*</span></label>
                                            <input id="CARTEBANCAIREEdit" type="text" class="form-control" name="cb" required >
                                            <i class="fa-solid fa-eye" id="togglePassword"></i>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="EditInformation">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
</div>



@endsection