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
        <div class="container-fluid mt-5 ">
            <div class="container-fluid  d-flex flex-column justify-content-center w-75 mt-5">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #a6c4dd">
                            <a href="{{url('caissesortie')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center  mt-2">sortie de caisse vides</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100  " style="background-color: #538fd8">
                            <a href="{{url('caisseretour')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center  mt-2">retour caisse vides</p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100  " style="background-color: #62711E">
                            <a href="{{url('MarchandisEntre')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center  mt-2">entrée marchandises</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #367838">
                            <a href="{{url('MarchandisSortie')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center  mt-2">sortie de marchandise</p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="border w-100  " style="background-color: #d3a80e">
                            <a href="{{url('#')}}" class="text-dark" data-bs-toggle="modal" data-bs-target="#ModalChooseFrigoOrFerme">
                                <p class="fs-4 py-2 text-center  mt-2">comptabilité</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="border w-100 " style="background-color: #12141A">
                            <a href="{{url('Stitaution')}}" class="text-white">
                                <p class="fs-4 py-2 text-center  mt-2">Situation de stockage</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-4">
                        <div class="border w-100 " style="background-color: #ad7241">
                            <a href="{{url('Setting')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center  mt-2">parametres</p>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="modal fade  mt-5" id="ModalChooseFrigoOrFerme" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Comptabilité</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <div class="row mt-3">
                                <div class="col-sm-12 col-md-12 col-xl-6">
                                    <div class="border w-100  " style="background-color: #62711E">
                                        <a href="{{url('Ferme')}}" class="text-dark">
                                            <p class="fs-4 py-2 text-center text-uppercase mt-2">fermme</p>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-xl-6">
                                    <div class="border w-100 " style="background-color: #367838">
                                        <a href="{{url('Frigo')}}" class="text-dark">
                                            <p class="fs-4 py-2 text-center text-uppercase mt-2">frigo</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>








        {{-- <div class="container-fluid mt-3">
            <div class="container-fluid  d-flex flex-column justify-content-center w-75 mt-5">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 bg-success ">
                            <a href="{{url('caissesortie')}}" class="text-white">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">sortie de caisse vides</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 bg-danger ">
                            <a href="{{url('caisseretour')}}" class="text-white">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">retour caisse vides</p>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color:rgb(67, 191, 67)">
                            <a href="{{url('MarchandisEntre')}}" class="text-white">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">entrée marchandises</p>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color:rgb(194, 67, 67)">
                            <a href="#" class="text-white">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">sortie / vente marchandises</p>
                            </a>
                        </div>
                        <div class="row g-0">
                            <div class="col-6">
                                <div class="border w-100 " style="background-color:rgba(67, 191, 67, 0.66)">
                                    <a href="{{url('MarchandisSortie')}}" class="text-white">
                                        <p class="fs-4 py-2 text-center text-uppercase mt-2">sortie de marchandise</p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border w-100 " style="background-color:rgba(194, 67, 67, 0.644)">
                                    <a href="{{url('Vente')}}" class="text-white">
                                        <p class="fs-4 py-2 text-center text-uppercase mt-2">vente marchandises</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 bg-success ">
                            <a href="{{url('Stitaution')}}" class="text-white">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">situation de stockage</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 bg-danger ">
                            <a href="{{url('Setting')}}" class="text-white">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">parametres</p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div class="border w-100 bg-warning">
                            <p class="text-white fs-4 py-2 text-center text-uppercase">comptabilité</p>
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col-sm-12 col-md-12 col-xl-6">
                            <div class="border" style="background-color:rgb(80, 80, 218)">
                                <a href="{{url('Ferme')}}" class="text-white">
                                    <p class="fs-4 py-2 text-center text-uppercase mt-2">fermme</p>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-6">
                            <div class="border" style="background-color:rgb(80, 142, 218)">
                                <a href="{{url('Frigo')}}" class="text-white">
                                    <p class="fs-4 py-2 text-center text-uppercase mt-2">frigo</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

    </div> <!-- content -->

    

</div>
@endsection