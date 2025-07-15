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

        <div class="container-fluid mt-3">
            <H3 class="text-center border py-2 bg-light text-uppercase text-dark"> Situation de stockage 
                <span>
                    <a href="{{url('home')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                </span>
            </H3>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-xl-6">
                    <div class="border w-100  " style="background-color: #1ed30e">
                        <a href="{{url('Stituation_SortieCaisseVide')}}" class="text-dark" >
                            <p class="fs-4 py-2 text-center text-uppercase mt-2">Sortie de caisses vides</p>
                        </a>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-xl-6">
                    <div class="border w-100  " style="background-color: #d3a80e">
                        <a href="{{url('Stituation_SortieCaisseRetour')}}" class="text-dark" >
                            <p class="fs-4 py-2 text-center text-uppercase mt-2">Retour de caisses vides</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-12 col-md-12 col-xl-6">
                    <div class="border w-100  " style="background-color: #a2e870">
                        <a href="{{url('Stituation_MarchandiseSortie')}}" class="text-dark" >
                            <p class="fs-4 py-2 text-center text-uppercase mt-2">Sortie de marchandises</p>
                        </a>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-xl-6">
                    <div class="border w-100  " style="background-color: #e3c968">
                        <a href="{{url('Stituation_MarchandiseEntree')}}" class="text-dark" >
                            <p class="fs-4 py-2 text-center text-uppercase mt-2">Entrée de marchandises</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-sm-12 col-md-12 col-xl-6">
                    <div class="border w-100  " style="background-color: #7ba55c">
                        <a href="{{url('Stituation_bilangenrale')}}" class="text-dark" >
                            <p class="fs-4 py-2 text-center text-uppercase mt-2">Le bilan général</p>
                        </a>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-xl-6">
                    <div class="border w-100  " style="background-color: #f0dfa5">
                        <a href="{{url('showClientSituation')}}" class="text-dark" >
                            <p class="fs-4 py-2 text-center text-uppercase mt-2">Sitution client</p>
                        </a>
                    </div>
                </div>
            </div>


            {{-- <div class="table-responsive">
                <table class="table table-bordered text-uppercase mt-3 text-center">
                    <tr>
                        <th class="bg-success ">
                            <a href="{{url('Stituation_SortieCaisseVide')}}" class="text-white">Sortie de caisses vides</a> 
                        </th>
                        <th colspan="2" class="bg-danger">
                           <a href="{{url('Stituation_MarchandiseEntree')}}" class="text-white">Entrée de marchandises</a> 
                        </th>
                    </tr>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-uppercase mt-3 text-center">
                    <tr>
                        <th class="bg-success " style="max-width: 195px;">
                            <a href="{{url('Stituation_MarchandiseSortie')}}" class="text-white">Sortie de marchandises</a> 
                        </th>
                        <th colspan="2" class="bg-danger">
                           <a href="{{url('Stituation_SortieCaisseRetour')}}" class="text-white">Retour de caisses vides</a> 
                        </th>
                    </tr>
                </table>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered text-uppercase mt-3 text-center">
                    <tr>
                        <th class="bg-success " style="max-width: 129px;">
                            <a href="{{url('Stituation_bilangenrale')}}" class="text-white">Le bilan général</a> 
                        </th>
                        <th colspan="2" class="bg-danger">
                           <a href="{{url('showClientSituation')}}" class="text-white">Sitution  client</a> 
                        </th>
                    </tr>
                </table>
            </div> --}}

            
        </div>

    </div> <!-- content -->

    

</div>
@endsection