@extends('dashboard.index')

@section('dashboard')
<script src="{{asset('js/vente/script.js')}}"></script>

<script>
    var csrf_token                                           = "{{csrf_token()}}";
    var Vente                                                = "{{url('Vente')}}";
    var AddVente                                             = "{{url('AddVente')}}";
    

</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-bold m-0 text-center text-uppercase">Vente de marchandises </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                        <li class="breadcrumb-item active"> Vente de  marchandises</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class=" mb-3">
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddMarchandiseVente">Liste opérations</button>
                            </div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table TableVente" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true" >Nombre</th>
                                                    <th data-sortable="true" >Acheteur</th>
                                                    <th data-sortable="true" >Vendeur</th>
                                                    <th data-sortable="true" >Livreur</th>
                                                    <th data-sortable="true" >Créer par</th>
                                                    <th data-sortable="true" >Créer le</th>
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


       

        <div class="modal fade" id="ModalAddMarchandiseVente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter un bon vente marchandise</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationVente"></ul>
                            <form id="FormAddMarchandiseVente">
                                <div class="row ">
                                    <div class="col-sm-12 col-md-12 col-xl-3 ">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-success w-100 text-center fs-3 text-white rounded-2">Acheteur <span class="text-danger">*</span></label>
                                            <select name="achteur"  class="form-select">
                                                <option value="0">Veuiller séléctionner acheteur</option>
                                                @foreach ($Clients as $value)
                                                    <option value={{$value->id}}>{{$value->firstname ." ". $value->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-xl-3 ">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-secondary w-100 text-center fs-3 text-white rounded-2">Vendeur <span class="text-danger">*</span></label>
                                            <select name="vendeur"  class="form-select">
                                                <option value="0">Veuiller séléctionner vendeur</option>
                                                @foreach ($Clients as $value)
                                                    <option value={{$value->id}}>{{$value->firstname ." ". $value->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-xl-3">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-primary w-100 text-center fs-3 text-white rounded-2">Livreur <span class="text-danger">*</span></label>
                                            <select name="idlivreur" id="" class="form-select">
                                                <option value="0">Veuiller séléctionner le livreur</option>
                                                @foreach ($Livreurs as $value)
                                                    <option value={{$value->id}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-xl-3">
                                        <div class="form-group mb-3 password-container">
                                            <label for="password" class="form-label bg-warning w-100 text-center fs-3 text-white rounded-2">Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="number_box" placeholder="Nombre">
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" class="btn btn-primary" id="AddMarchandiseVente">Sauvegarder</button>
                            <button type="button" class="btn btn-primary" id="UpdateMarchandiseEntree" style="display: none">Mise à jour</button>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>



        <div class="modal fade" id="ModalEditProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier compagnie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="pt-0">
                            <ul class="ValidationEditProduct"></ul>
                            <form id="FormEditProduct">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Titre <span class="text-danger">*</span></label>
                                            <input id="TitleProdutctEdit" type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                        <button type="button" class="btn btn-primary" id="EditProduct">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>



        
        
        
</div>
<script>
    window.Laravel = {
        ErrorsInfos: @json(session('ErrorsInfos')),
        ErrorsNumberStartBon : @json(session('ErrorsNumberStartBon'))
    };
</script>


@endsection