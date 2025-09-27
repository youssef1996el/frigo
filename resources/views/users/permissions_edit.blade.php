@extends('dashboard.index')

@section('dashboard')
<div class="content-page">
    <div class="content">
        <div class="container-fluid w-100">
            <div class="card mt-5">
                <div class="card-header">
                    Gérer les utilisateurs
                    <a href="{{ url('Setting') }}" class="btn btn-warning float-end ms-2">Retour</a>
                    <a href="{{ url('home') }}" class="btn btn-primary float-end ms-2">Page d'accueil</a>
                </div>

                <div class="card-body">
                    <h2 class="mb-4">
                        🔑 Gérer les autorisations pour 
                        <span class="text-primary">{{ $user->name }}</span>
                    </h2>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ url('user_permission/'.$user->id) }}" method="POST">
                        @csrf
                        
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 20%">Groupe</th>
                                    <th style="width: 60%">Autorisation</th>
                                    <th style="width: 20%" class="text-center">A accès</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $group => $perms)
                                    @foreach($perms as $permission)
                                        <tr>
                                            <td>{{ ucfirst($group) }}</td>
                                            <td>{{ ucfirst($permission->name) }}</td>
                                            <td class="text-center">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    name="permissions[]" 
                                                    value="{{ $permission->name }}" 
                                                    id="perm_{{ $permission->id }}"
                                                    {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                >
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mt-3 btn-md shadow-sm ">
                            💾 Enregistrer les autorisations
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
