@extends('dashboard.index')

@section('dashboard')

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <style>
            

            th {
                padding: 12px;
                vertical-align: middle !important;
                text-align: center;
            }

            .table-responsive {
                overflow-x: auto;
            }
        </style>
       
        
        <div class="container-fluid w-100">
            <div class="row w-100">
                <div class="col-12 position-relative">
                    <div class="shadow border rounded-2 text-white fs-3 text-center" style="height: 100px; line-height: 100px;">
                        <a href="#" class="text-dark text-decoration-none text-uppercase">Bons</a>
                        <a href="{{url('Setting')}}" class="btn btn-warning float-end mt-4"      style="margin-right: 5px" >Retour</a>
                        <a href="{{url('home')}}" class="btn btn-primary float-end mt-4"         style="margin-right: 5px" >Page d'accueil</a>
                    </div>
                </div>
            </div>

            <div class="card mt-5 p-2 ">
                <div class="row w-100 ">
                
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #5C5A68">
                            <a href="{{url('Info')}}" class="text-dark">
                                <p class="fs-5 py-2 text-center text-uppercase mt-2">Information</p> 
                            </a>
                        </div>
                    </div>


                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #926C61">
                            <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#ModalAddNombreBonCaisseVide">
                                <p class="fs-5 py-2 text-center text-uppercase mt-2">Numéro de départ de bon caisse vide</p>
                            </a>
                        </div>
                    </div>


                </div>

                <div class="row mt-3 w-100">
                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="border  " style="background-color: #2169A3">
                            <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#ModalAddNombreBonMarchandiseEntree" >
                                <p class="fs-5 py-2 text-center text-uppercase mt-2">Numéro de départ de bon marchandises entrée</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="border w-100 " style="background-color: #68899C">
                            <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#ModalAddNombreBonMarchandiseSortie" >
                                <p class="fs-5 py-2 text-center text-uppercase mt-2">Numéro de départ de bon marchandises sortie</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="border w-100 " style="background-color: #329fde">
                            <a href="#" class="text-dark" data-bs-toggle="modal" data-bs-target="#ModalAddNombreBonCaisseRetour">
                                <p class="fs-5 py-2 text-center text-uppercase mt-2">Numéro de départ de bon caisse retour</p>
                            </a>
                        </div>
                    </div>
                </div>

                

                {{-- <div class="row w-100 mt-5">
                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="bg-success d-flex justify-content-center align-items-center text-white fs-3" style="height: 200px;">
                            <a href="{{ url('livreur') }}" class="text-white text-decoration-none">livreurs</a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="bg-danger d-flex justify-content-center align-items-center text-white fs-3" style="height: 200px;">
                            <a href="{{ url('ListOrigin') }}" class="text-white text-decoration-none">Produits</a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="bg-info d-flex justify-content-center align-items-center text-white fs-3" style="height: 200px;">
                            <a href="{{ url('') }}" class="text-white text-decoration-none">Povoirs</a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-4 mt-2">
                        <div class="bg-info d-flex justify-content-center align-items-center text-white fs-3" style="height: 200px;">
                            <a href="{{ url('Charge') }}" class="text-white text-decoration-none">Charges</a>
                        </div>
                    </div>
                </div> --}}
            </div>

            {{-- <div class="card m-3 p-2">
                <div class="row w-100 ">
                    <div class="col-sm-12 col-md-12 col-xl-3">
                        <div class="bg-success d-flex justify-content-center align-items-center text-white fs-3" style="height: 200px;">
                            <a href="#" class="text-white text-decoration-none text-center" data-bs-toggle="modal" data-bs-target="#ModalAddNombreBonCaisseVide">Numéro de départ de bon caisse vide</a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-3">
                        <div class="bg-danger d-flex justify-content-center align-items-center text-white fs-3" style="height: 200px;">
                            <a href="#" class="text-white text-decoration-none text-center" data-bs-toggle="modal" data-bs-target="#ModalAddNombreBonMarchandiseEntree">Numéro de départ de bon marchandises entrée</a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-3">
                        <div class="bg-info d-flex justify-content-center align-items-center text-white fs-3" style="height: 200px;">
                            <a href="#" class="text-white text-decoration-none text-center" data-bs-toggle="modal" data-bs-target="#ModalAddNombreBonMarchandiseSortie">Numéro de départ de bon marchandises sortie</a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-3">
                        <div class="bg-info d-flex justify-content-center align-items-center text-white fs-3" style="height: 200px;">
                            <a href="#" class="text-white text-decoration-none text-center" data-bs-toggle="modal" data-bs-target="#ModalAddNombreBonCaisseRetour">Numéro de départ de bon caisse retour</a>
                        </div>
                    </div>
                </div>
            </div> --}}
            



            <div class="modal fade" id="ModalAddNombreBonCaisseVide" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Numéro de départ de bon caisse vide</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{url('SaveprintBonCaisseVide')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="number" id="number_bon" class="form-control" min="1" step="1" name="number_bon" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary" >Sauvegarder</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
            <!-- -->

            <div class="modal fade" id="ModalAddNombreBonMarchandiseEntree" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Numéro de départ de bon marchandise entrée</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{url('SaveprintBonMarchandiseEntree')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="number" id="number_bon" class="form-control" min="1" step="1" name="number_bon" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary" >Sauvegarder</button>
                            </div>
                        </form> 
                        
                    </div>
                </div>
            </div>
            <!-- -->
            <div class="modal fade" id="ModalAddNombreBonMarchandiseSortie" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Numéro de départ de bon marchandise sortie</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{url('SaveprintBonMarchandiseSortie')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="number" id="number_bon" class="form-control" min="1" step="1" name="number_bon" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary" >Sauvegarder</button>
                            </div>
                        </form> 
                        
                    </div>
                </div>
            </div>

            <!-- -->
            <div class="modal fade" id="ModalAddNombreBonCaisseRetour" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Numéro de départ de bon caisse retour</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{url('SaveprintBonCaisseRetour')}}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="number" id="number_bon" class="form-control" min="1" step="1" name="number_bon" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary" >Sauvegarder</button>
                            </div>
                        </form> 
                        
                    </div>
                </div>
            </div>
        </div>

        



    </div> <!-- content -->

    

</div>
@endsection