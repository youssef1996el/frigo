{{-- @extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New User
                </div>
                <div class="float-end">
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                        <div class="col-md-6">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label text-md-end text-start">Roles</label>
                        <div class="col-md-6">           
                            <select class="form-select @error('roles') is-invalid @enderror" multiple aria-label="Roles" id="roles" name="roles[]">
                                @forelse ($roles as $role)

                                    @if ($role!='Super Admin')
                                        <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                        {{ $role }}
                                        </option>
                                    @else
                                        @if (Auth::user()->hasRole('Super Admin'))   
                                            <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                            {{ $role }}
                                            </option>
                                        @endif
                                    @endif

                                @empty

                                @endforelse
                            </select>
                            @error('roles')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add User">
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>    
@endsection --}}


<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <title>Log In | Hando - Responsive Admin Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->


        <!-- App css -->
        <link href="{{asset('css/custom/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
        <!-- Icons -->
        <link href="{{asset('css/custom/icons.min.css')}}" rel="stylesheet" type="text/css" />


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css" integrity="sha512-/k658G6UsCvbkGRB3vPXpsPHgWeduJwiWGPCGS14IQw3xpr63AEMdA8nMYG2gmYkXitQxDTn6iiK/2fD4T87qA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        
        <script src="{{asset('js/head.js')}}"></script>
        <style>
            .account-page-bg
            {
                background-image : url('images/image_login.jpg')
            }
        </style>


    </head>

    <body>
        <!-- Begin page -->
        <div class="account-page">
            <div class="container-fluid p-0">
                <div class="row align-items-center g-0 px-3 py-3 vh-100">

                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <a href="{{url('users')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                                                    <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                                                    
                                        <div class="mb-0 p-0 p-lg-3">
                                            <div class="mb-0 border-0 p-md-4 p-lg-0">
                                                <div class="mb-4 p-0 text-lg-start text-center">
                                                    <div class="auth-brand">
                                                        <a class='logo logo-light' href='/hando/html/'>
                                                            <span class="logo-lg">
                                                                <img src="{{asset('images/person-2.png')}}" alt="" height="24">
                                                            </span>
                                                        </a>
                                                        <a class='logo logo-dark' href='/hando/html/'>
                                                            <span class="logo-lg">
                                                                <img src="assets/images/logo-dark-3.png" alt="" height="24">
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                
        
                                                <div class="auth-title-section mb-4 text-lg-start text-center"> 
                                                    
                                                </div>
                                                <div class="pt-0">
                                                    <form method="POST" action="{{ route('createuser') }}" class="my-4">
                                                        @csrf
                                                        <div class="form-group mb-3">
                                                            <label for="emailaddress" class="form-label">{{ __('Nom') }}</label>
                                                            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="emailaddress" required="" placeholder="Entrez votre nom">
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="emailaddress" class="form-label">Adresse e-mail</label>
                                                            <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" id="email" required="" placeholder="Entrez votre email">
                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                            
                                                        <div class="form-group mb-3">
                                                            <label for="emailaddress" class="form-label">Mot de passe</label>
                                                            <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" id="email" name="password" required placeholder="Entrez votre mot de passe">
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="emailaddress" class="form-label">Confirme mot de passe</label>
                                                            <input class="form-control " type="password" id="email" name="password_confirmation" required  placeholder="Entrez votre mot de passe de confirmation">
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label for="emailaddress" class="form-label">Roles</label>
                                                            <select class="form-select @error('roles') is-invalid @enderror" multiple aria-label="Roles" id="roles" name="roles[]">
                                                                @forelse ($roles as $role)
                                
                                                                    @if ($role!='Super Admin')
                                                                        <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                                                        {{ $role }}
                                                                        </option>
                                                                    @else
                                                                        @if (Auth::user()->hasRole('Super Admin'))   
                                                                            <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                                                            {{ $role }}
                                                                            </option>
                                                                        @endif
                                                                    @endif
                                
                                                                @empty
                                
                                                                @endforelse
                                                            </select>
                                                            @error('roles')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group mb-0 row">
                                                            <div class="col-12">
                                                                <div class="d-grid">
                                                                    

                                                                    <button type="submit" class="btn btn-primary fw-semibold">
                                                                        {{ __('sauvegarder') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-7 d-none d-xl-inline-block">
                        <div class="account-page-bg rounded-4">
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        <!-- END wrapper -->

        <!-- Vendor -->
        <script src="{{asset('js/jquery/jquery.min.js')}}"></script>
        <script src="{{asset("js/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
        <script src="{{asset("js/simplebar/simplebar.min.js")}}"></script>
        <script src="{{asset("js/node-waves/waves.min.js")}}"></script>
        <script src="{{asset("js/waypoint/lib/jquery.waypoints.min.js")}}"></script>
        <script src="{{asset("js/jquery-counterup/jquery.counterup.min.js")}}"></script>
        <script src="{{asset("js/feather-icons/feather.min.js")}}"></script>

        <!-- App js-->
        <script src="{{asset("js/app.js")}}"></script>
        
    </body>
</html>