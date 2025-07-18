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
            <div class="row justify-content-center mt-5">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-start">
                                Modifier utilisateur
                            </div>
                            <div class="float-end">
                                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">Page d'accueil</a>
                                <a href="{{ route('users.index') }}" class="btn btn-warning btn-sm"> Retour</a>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.update', $user->id) }}" method="post">
                                @csrf
                                @method("PUT")

                                <div class="mb-3 row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                                    <div class="col-md-6">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="email" class="col-md-4 col-form-label text-md-end text-start">Mail</label>
                                    <div class="col-md-6">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="password" class="col-md-4 col-form-label text-md-end text-start">Mot de passe</label>
                                    <div class="col-md-6">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">Confirm mot de passe</label>
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
                                                <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                                    {{ $role }}
                                                </option>
                                                @else
                                                    @if (Auth::user()->hasRole('Super Admin'))   
                                                    <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
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
                                    <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Modifier">
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>
  
@endsection