<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de caisse retour</title>
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
            font-size: 12px
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
    </style>
</head>
<body>
<div class="containerAll">
    <div class="container">
        <div class="header">
            <p>{{$Info[0]->name}}</p>
            <p>Tél : {{$Info[0]->phone}}</p>
        </div>
    
        <h3>BON RETOUR CAISSES VIDES N° : {{ str_pad($Extract_number_bon, 4, '0', STR_PAD_LEFT) }} / 4</h3>
         <p><strong>Date :</strong> {{$Client->created_date}}</p>
        <p><strong>Nom du client :</strong> {{$Client->firstname}} {{$Client->lastname}}</p>
    
        <table>
            <thead>
                <tr>
                    <th>Nombre de Caisse</th>
                    <th>Nom Livreur / Matricule</th>
                    <th>CIN</th>
                    <th>Signature Livreur</th>
                    <th>Cumul</th>
                    <th>Etranger</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{$item->number_box}}</td>
                        <td>{{$item->name_livreur ." / ". $item->matricule}}</td>
                        <td>{{$item->cin_livreur}}</td>
                        <td></td>
                        <td>{{$item->cumul}}</td>
                        <td>{{$item->etranger}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        <div class="signature">
            <p>Visa Responsable Frigo : ..................................................</p>
        </div>
    
        <div class="footer">
            -bon délivré par {{Auth::user()->name}} le {{$DatePrintBon->format('Y-m-d')}} à {{$TimePrintBon}} <br>
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
    
        <h3>BON RETOUR CAISSES VIDES  N°: {{ str_pad($Extract_number_bon, 4, '0', STR_PAD_LEFT) }}  / 4</h3>
        <p><strong>Date :</strong> {{$Client->created_date}}</p>
        <p><strong>Nom du client :</strong> {{$Client->firstname}} {{$Client->lastname}}</p>
    
        <table>
            <thead>
                <tr>
                    <th>Nombre de Caisse</th>
                    <th>Nom Livreur / Matricule</th>
                    <th>CIN</th>
                    <th>Signature Livreur</th>
                    <th>Cumul</th>
                    <th>Etranger</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{$item->number_box}}</td>
                        <td>{{$item->name_livreur ." / ". $item->matricule}}</td>
                        <td>{{$item->cin_livreur}}</td>
                        <td></td>
                        <td>{{$item->cumul}}</td>
                        <td>{{$item->etranger}}</td>
                    </tr>
                @endforeach
               
            </tbody>
        </table>
    
        <div class="signature">
            <p>Visa Responsable Frigo : ..................................................</p>
        </div>
    
        <div class="footer">
            -bon délivré par {{Auth::user()->name}}  le {{$DatePrintBon->format('Y-m-d')}} à {{$TimePrintBon}} <br>
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
