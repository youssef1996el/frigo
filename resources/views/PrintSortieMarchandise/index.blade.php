<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BON DE SORTIE MARCHANDISE </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
           
        }
        .containerAll{
            border: 1px solid #000;
            width: 100%;
           
           padding: 20px;
           margin-bottom: 10px; /* بدلها على 10px باش ميبقاش الفرق كبير بين النسختين */
           display: inline-block; /* باش يبقاو في نفس الصفحة */
           page-break-inside: avoid; /* يمنع تكسير الصفحة */
        }


        .header {
            text-align: left;
            margin-bottom: 20px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }
        th {
            background: #ddd;
        }
        .signature {
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
        }
        .divider 
        {
            /* display: none; */ /* نحيد الخط الفاصل */
            border-top: 2px dashed black;
            margin: 10px 0;
        }
        .title
        {
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #ddd;
        }
        .title_table
        {
            font-size: 10px;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="containerAll">
    <div class="container">
        <div class="header">
           <p>{{$Info[0]->name}}</p>
            <p>Tél : {{$Info[0]->phone}}</p>
        </div>
    
        <h3 class="title">BON DE SORTIE MARCHANDISE N°  : {{ str_pad($Extract_number_bon, 4, '0', STR_PAD_LEFT) }} / 3</h3>
        <p><strong>Date :</strong> {{$Client->created_date}}</p>
        <p><strong>Nom du client :</strong> {{$Client->firstname}} {{$Client->lastname}}</p>
    
        <table>
            <thead>
                <tr>
                    <th class="title_table">NOMBRE DE CAISSE </th>
                    <th class="title_table">PRODUIT</th>
                    <th class="title_table">Cumul</th>
                    <th class="title_table">Etranger </th>
                    
                </tr>
            </thead>
            <tbody>
                {{-- @php
                dd($Data);
                @endphp --}}
                @foreach ($Data as $item)
                    <tr>
                        <td>{{$item->total_quantity}}</td>
                        <td>{{$item->name }}</td>
                        <td>{{$item->cumul}}</td>
                        <td>{{$item->etranger}}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th class="title_table">NOM LIVREUR / MATRICULE</th>
                    <th class="title_table">C.I.N</th>
                    <th class="title_table">SIGNATURE LIVREUR</th>
                    <th class="title_table">NATURE CAISSES</th>
                    <th class="title_table">VISA FRIGO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$Data[0]->livreur ." / ". $Data[0]->matricule}}</td>
                    <td>{{$Data[0]->cin}}</td>
                    <td></td>
                    <td>1er choix</td>
                </tr>
            </tbody>
        </table>
    
       {{--  <div class="signature">
            <p>Visa Responsable Frigo : ..................................................</p>
        </div> --}}
    
        <div class="footer">
            -bon délivré par {{$userPrintBon}} le {{$DatePrintBon->format('Y-m-d')}} à {{$TimePrintBon}} <br>
            <p style="font-size: 9.8px;border:1px solid #000;border-radius: 5px;padding:5px;background-color:#ddd">
            <strong> SOCIETE</strong> : {{$Info[0]->companie}}  AU CAPITAL DE : {{$Info[0]->capital}} 
            <strong> ICE N° </strong> : {{$Info[0]->ice}} 
            <strong> IF N° </strong>  : {{$Info[0]->if}}  
            <strong> C/B  </strong>   : {{$Info[0]->cb}} 
        </p>
        </div>
    </div>
    
    <div class="divider"></div>
    
    <div class="container">
        <div class="header">
             <p>{{$Info[0]->name}}</p>
            <p>Tél : {{$Info[0]->phone}}</p>
        </div>
    
        <h3 class="title">BON DE SORTIE MARCHANDISE N°  : {{ str_pad($Extract_number_bon, 4, '0', STR_PAD_LEFT) }} / 3</h3>
        <p><strong>Date :</strong> {{$Client->created_date}}</p>
        <p><strong>Nom du client :</strong> {{$Client->firstname}} {{$Client->lastname}}</p>
    
        <table>
            <thead>
                <tr>
                    <th class="title_table">NOMBRE DE CAISSE </th>
                    <th class="title_table">PRODUIT</th>
                    <th class="title_table">Cumul</th>
                    <th class="title_table">Etranger </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Data as $item)
                    <tr>
                        <td>{{$item->total_quantity}}</td>
                        <td>{{$item->name }}</td>
                        <td>{{$item->cumul}}</td>
                        <td>{{$item->etranger}}</td>
                    </tr>
                @endforeach
               
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th class="title_table">NOM LIVREUR / MATRICULE</th>
                    <th class="title_table">C.I.N</th>
                    <th class="title_table">SIGNATURE LIVREUR</th>
                    <th class="title_table">NATURE CAISSES</th>
                    <th class="title_table">VISA FRIGO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$Data[0]->livreur ." / ". $Data[0]->matricule}}</td>
                    <td>{{$Data[0]->cin}}</td>
                    <td></td>
                    <td>1er choix</td>
                </tr>
            </tbody>
        </table>
    
       
    
        <div class="footer">
            -bon délivré par {{$userPrintBon}} le {{$DatePrintBon->format('Y-m-d')}} à {{$TimePrintBon}} <br>
            <p style="font-size: 9.8px;border:1px solid #000;border-radius: 5px;padding:5px;background-color:#ddd">
            <strong> SOCIETE</strong> : {{$Info[0]->companie}}  AU CAPITAL DE : {{$Info[0]->capital}} 
            <strong> ICE N° </strong> : {{$Info[0]->ice}} 
            <strong> IF N° </strong>  : {{$Info[0]->if}}  
            <strong> C/B  </strong>   : {{$Info[0]->cb}}
        </p>
        </div>
    </div>
</div>


</body>
</html>
