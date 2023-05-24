<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laravel 9 Create PDF File using DomPDF Tutorial - LaravelTuts.com</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <img class="mb-3" src="{{url('/')}}/logo/logo-fr-white.png" alt="" height="36" >
    <h2 style="font-size: 34px;">{{ $title }}</h2>
    <p><b>Date de facturation: </b>{{ $date }}</p>
    <p>&nbsp;</p>
    
    <ul style="list-style: none;padding-left: 0; margin-left: 0">
        <li>
            <b> Prénom:</b> {{$user->firstname}}
        </li>
        <li>
            <b> Nom: </b>{{$user->lastname}}
        </li>
        <li>
            <b> email: </b> {{$user->email}}
        </li>
        <li>
            @if ($user->company_name)
            <b>  Société: </b>{{$user->company_name}}
            @endif
        </li>
        <li>
            @if ($user->company_vat)
            <b> Numéro de TVA:</b> {{$user->company_vat}}
            @endif
        </li>
    </ul>
    <table class="table table-bordered">
        <tr>
            <th>Reference</th>
            <th>Libellé</th>
            <th>Prix</th>
        </tr>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->reference }}</td>
                <td>{{ $transaction->type }}</td>
                <td>€{{ $transaction->price }} TVAC</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td style="text-align: right"><b>Total</b></td>
            <td>€{{ $total }} TVAC (21%: €{{ number_format($total-$vat,2) }})</td>
        </tr>
    </table>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua.</p>
        <ul style="list-style: none;padding-left: 0; margin-left: 0">
            <li>
                <b> Vacancesweb.be</b>
            </li>
            <li>
                <b> email: </b> info@vacancesweb.be
            </li>
            <li>
                <b> Société: </b>Immovlan S.A.
            </li>
            <li>
                <b> Numéro de TVA:</b> BE 0 999.999.999
            </li>
            <li>
                Rue de la fusée 35, 1167 Bruxelles Belgique
            </li>
        </ul>
        
</body>
</html>