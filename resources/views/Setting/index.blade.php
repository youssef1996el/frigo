@extends('dashboard.index')

@section('dashboard')
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
<div class="content-page">
    <div class="content">

       
       
        
        <div class="container-fluid w-100">
            <div class="row w-100">
                <div class="col-12 position-relative">
                    <div class="shadow border rounded-2 text-white fs-3 text-center" style="height: 100px; line-height: 100px;">
                        <a href="#" class="text-dark text-decoration-none text-uppercase">Paramètre</a>
                        <a href="{{ url('/home') }}" class="btn btn-warning position-absolute" style="top: 50%; right: 20px; transform: translateY(-50%);">
                            Retour
                        </a>
                    </div>
                </div>
            </div>
            <div class="card mt-5 p-2 ">
                <div class="row w-100 ">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #5C5A68">
                            <a href="{{url('company')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">Companie</p>
                            </a>
                        </div>
                    </div>
                    
                </div>

                <div class="row w-100 ">
                    
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #926C61">
                            <a href="{{url('users')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">pouvoirs</p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 w-100">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border  " style="background-color: #2169A3">
                            <a href="{{url('client')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">Clients</p>
                            </a>
                        </div>
                    </div>
                    
                </div>

                <div class="row mt-3 w-100">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #68899C">
                            <a href="{{url('livreur')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">Livreurs</p>
                            </a>
                        </div>
                    </div>
                    
                </div>

                <div class="row mt-3 w-100">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border  " style="background-color: #2E6D36">
                            <a href="{{url('ListOrigin')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">Marchandises</p>
                            </a>
                        </div>
                    </div>
                    
                    
                </div>
                
                 <div class="row mt-3 w-100">
                    
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #AD5F55">
                            <a href="{{url('Charge')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">Depenses</p>
                            </a>
                        </div>
                    </div>
                   
                </div>
                
                <div class="row mt-3 w-100">
                    
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border w-100 " style="background-color: #858585">
                            <a href="{{url('Bons')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">Bons</p>
                            </a>
                        </div>
                    </div>
                   
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="border  " style="background-color: #57a0d7">
                            <a href="{{url('Comptabilite')}}" class="text-dark">
                                <p class="fs-4 py-2 text-center text-uppercase mt-2">comptabilité</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div> <!-- content -->
</div>
@endsection