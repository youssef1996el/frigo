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
            <div class="card mt-5">
                <div class="card-header">
                    Manage Users
                    <a href="{{url('Setting')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
                    <a href="{{url('home')}}" class="btn btn-primary float-end"         style="margin-right: 5px" >Page d'accueil</a>
                                                    
                
                </div>
                
                <div class="card-body">
                    @can('create-user')
                        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Ajouter utilisateur</a>
                    @endcan
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                            <th scope="col">S#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <ul>
                                        @forelse ($user->getRoleNames() as $role)
                                            <li>{{ $role }}</li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </td>
                                <td>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                            @if (Auth::user()->hasRole('Super Admin'))
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Modifier</a>
                                            @endif
                                        @else
                                            @can('edit-user')
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Modifier</a>   
                                            @endcan

                                            @can('delete-user')
                                                @if (Auth::user()->id!=$user->id)
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this user?');"><i class="bi bi-trash"></i> Supprimer</button>
                                                @endif
                                            @endcan
                                        @endif

                                    </form>
                                </td>
                            </tr>
                            @empty
                                <td colspan="5">
                                    <span class="text-danger">
                                        <strong>No User Found!</strong>
                                    </span>
                                </td>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $users->links() }}

                </div>
            </div>
        </div>
    </div>
</div>

    
@endsection