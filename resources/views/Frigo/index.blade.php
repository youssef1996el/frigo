@extends('dashboard.index')

@section('dashboard')
<script src="{{asset('js/Frigo/script.js')}}"></script>
<script>
    var csrf_token                      = "{{csrf_token()}}";
    var store                           = "{{url('storeFrigo')}}";
   
</script>
<style>
    table, th, td {
        border: 1px solid #ddd;
        text-align: center;
    }
    table {
        width: 100%;
        margin-bottom: 30px;
    }
    .table-wrapper {
        display: flex;
        justify-content: space-between;
        gap: 30px;
    }
    .summary-table {
        width: 300px;
    }
    .footer-row {
        background-color: black;
        color: white;
        font-weight: bold;
    }
</style>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column border bg-light rounded-2 py-2">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Frigo </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                        <li class="breadcrumb-item active">Frigo</li>
                    </ol>
                </div>
            </div>
             <div class=" mb-3">
                <a href="{{url('/home')}}" class="btn btn-warning float-end"      style="margin-right: 5px" >Retour</a>
            </div>

            <div class="table-responsive mt-3">
                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height d-flex justify-content-center w-100">
                    <div class="datatable-container w-75" >
                        <table class="table datatable datatable-table w-100 " >
                            <thead>
                                <tr>
                                    <th colspan="3" class="text-uppercase bg-primary-subtle">Frigo</th>
                                </tr>
                                <tr>
                                    <th>Dotation</th>
                                    <th>Charge</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="number" name="dotation" id="inputDotation" class="form-control">
                                    </td>
                                    <td>
                                        <select name="charge_id" id="inputCharge" class="form-select">
                                            <option value="0">veuillez sélectionner la charge</option>
                                            @foreach ($Charges as $item)
                                                <option value="{{$item->id}}">{{$item->libelle}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" id="inputMontant" class="form-control" name="montant">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-success" id="btnValidate">Validation</button>
                        <a href="{{route('frigo.export')}}" class="btn btn-primary" >Export excel</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-3">
                @if (session('success'))
                    <div class="alert alert-success" id="success-message">
                        {{ session('success') }}
                    </div>

                    <script>
                        setTimeout(function () {
                            let success = document.getElementById('success-message');
                            if (success) {
                                success.style.display = 'none';
                            }
                        }, 6000); // 6000 ms = 6 seconds
                    </script>
                @endif
                 <table class="table datatable datatable-table w-100 ">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th class="bg-warning text-dark">DOTATION</th>
                            <th class="bg-success text-white">Depense</th>
                            <th class="bg-success text-white">MONTANT</th>
                            <th>Action</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($operations as $op)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($op->operation_date)->format('d-m-Y') }}</td>
                            <td class="bg-warning text-dark ">{{ $op->sum_dotation }}</td>
                            <td class="bg-success text-white ">{{ $op->charge_name }}</td>

                            <td class="bg-success text-white ">{{ $op->sum_montant }}</td>
                            <td>
                                @if (\Carbon\Carbon::parse($op->operation_date)->diffInHours(now()) < 24)
                                    <a href="{{ url('DeleteFrigo/' . $op->idfrigo) }}" class="btn btn-danger">Supprimer</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #cfe2f3">
                            <td class="bg-info"><strong>Total</strong></td>
                            <td class="bg-info text-dark " colspan=""><strong>{{ $operations->sum('sum_dotation') }}</strong></td>
                            <td class="bg-info text-dark " colspan=""></td>
                            
                            <td class="bg-info text-center text-dark" ><strong>{{ $operations->sum('sum_montant') }}</strong></td>
                            <td></td>
                        </tr>
                        <tr style="background-color: #fa9016" >
                            <td  class="bg-dark text-white text-center"><strong>Le reste dans la boîte</strong></td>
                            
                            <td class="text-center bg-dark text-white" colspan="3"><strong>{{ ($operations->last()->cumul_dotation ?? 0) - ($operations->last()->cumul_montant ?? 0) }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                
                <div class="mt-3">
                    {{ $operations->links() }}
                </div>
            </div>

            <div class="table-responsive mt-3">
                @php
                    // تحديد الأعمدة التي تحتوي على بيانات > 0
                    $validColumns = [];

                    foreach ($chargesDetail as $id => $libelle) {
                        foreach ($grouped as $chargesValues) {
                            if (!empty($chargesValues[$id]) && $chargesValues[$id] > 0) {
                                $validColumns[$id] = $libelle;
                                break;
                            }
                        }
                    }
                @endphp

                @if(count($validColumns) > 0)
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="{{ count($validColumns) + 1 }}" class="text-center text-uppercase bg-light">
                                    totaux par dépenses
                                </th>
                            </tr>
                            <tr>
                                <th>Date</th>
                                @foreach($validColumns as $libelle)
                                    <th>{{ $libelle }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grouped as $date => $chargesValues)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</td>
                                    @foreach($validColumns as $id => $libelle)
                                        <td>{{ isset($chargesValues[$id]) && $chargesValues[$id] > 0 ? $chargesValues[$id] : '' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="bg-info"><strong>Total</strong></td>
                                @foreach($validColumns as $id => $libelle)
                                    <td class="bg-info">
                                        <strong>
                                            @if(isset($totals[$id]) && $totals[$id] > 0)
                                                {{ number_format($totals[$id], 0, ',', ' ') }}
                                            @endif
                                        </strong>
                                    </td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                @endif

            </div>


            {{-- <div class="table-responsive mt-3">
                 <table class="table datatable datatable-table w-100 ">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th class="bg-warning text-dark">SUM DOTATION</th>
                            <th class="bg-warning text-dark">CUMUL DOTATION</th>
                            <th class="bg-success text-white">SUM MONTANT</th>
                            <th class="bg-success text-white">CUMUL MONTANT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($operations as $op)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($op->operation_date)->format('d-m-Y') }}</td>
                            <td class="bg-warning text-dark ">{{ $op->sum_dotation }}</td>
                            <td class="bg-warning text-dark ">{{ $op->cumul_dotation }}</td>
                            <td class="bg-success text-white ">{{ $op->sum_montant }}</td>
                            <td class="bg-success text-white ">{{ $op->cumul_montant }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #cfe2f3">
                            <td class="bg-info"><strong>Total</strong></td>
                            <td class="bg-info text-start" colspan="2"><strong>{{ $operations->sum('sum_dotation') }}</strong></td>
                            
                            <td class="bg-info text-start" colspan="2"><strong>{{ $operations->sum('sum_montant') }}</strong></td>
                            
                        </tr>
                        <tr style="background-color: #fa9016" >
                            <td colspan="4" style="text-align: left;" class="bg-dark text-white"><strong>Balance (Dotation - Montant)</strong></td>
                            <td class="text-center bg-dark text-white" ><strong>{{ ($operations->last()->cumul_dotation ?? 0) - ($operations->last()->cumul_montant ?? 0) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Pagination links -->
                <div class="mt-3">
                    {{ $operations->links() }}
                </div>
            </div> --}}

            
            

        </div>
    </div>
</div>


@endsection