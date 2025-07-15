
<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>Error 500 | Hando - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />

        <!-- Icons -->
        <link href="{{asset('css/custom/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />

        <link href="{{asset('css/custom/icons.min.css')}}" rel="stylesheet" type="text/css" />

        <script src="{{asset('js/head.js')}}"></script>


    </head>

    <body class="maintenance-bg-image">

        <!-- Begin page -->
        <div class="maintenance-pages">
            <div class="container-fluid p-0">
                <div class="row">

                    <div class="col-xl-12 align-self-center">
                        <div class="row">
                            <div class="col-md-5 mx-auto">
                                <div class="text-center">

                                    <div class="mb-0">
                                        <h3 class="fw-semibold text-dark text-capitalize">
                                            @if ($errors->any())
                                                <div class="">
                                                    {{ $errors->first() }}
                                                </div>
                                            @endif
                                        </h3>
                                        <p class="text-muted">Veuillez accéder à la section Entreprise et ajouter une nouvelle entreprise pour continuer</p>
                                    </div>

                                    <a class='btn btn-primary mt-3 me-1' href='{{ url()->previous() }}'>Retour à l'accueil</a>

                                    <div class="maintenance-img mt-4">
                                        <img src="{{asset('images/500-error.svg')}}" class="img-fluid" alt="coming-soon">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- END wrapper -->

        


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{asset("js/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
        <script src="{{asset("js/simplebar/simplebar.min.js")}}"></script>
        <script src="{{asset("js/node-waves/waves.min.js")}}"></script>
        <script src="{{asset("js/waypoint/lib/jquery.waypoints.min.js")}}"></script>
        <script src="{{asset("js/jquery-counterup/jquery.counterup.min.js")}}"></script>
        <script src="{{asset("js/feather-icons/feather.min.js")}}"></script>

        <script src="{{asset("js/app.js")}}"></script>
        
    </body>
</html>