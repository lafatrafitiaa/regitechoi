<!DOCTYPE html>
<html>
<head>
    <title>Validation rendez-vous</title>
</head>
<body>
    <div style="background-color: #f0f2ef; text-align: center">
        <img src="{{asset('assets/images/logo/logoR.png')}}"  alt="">
        <p>Bonjour {{ $details['mailclient'] }},</p>

        <p>Cher client {{ $details['societe'] }}, votre rendez-vous pour {{ $details['daterdv'] }} à {{ $details['heurerdv'] }} à {{ $details['lieu'] }} a été <strong>validé</strong>.</p>

        <p>Pour plus de détail, contactez-nous.</p>

        <p>Cordialement,</p>
        <p>__________________</p>
        <p>Regitech OI</p>
        <p>II W 19G, Antsakaviro 101 Antananarivo Madagascar</p>
        <p>Contact@regitech-oi.com, +261 32 12 710 00</p>
    </div>
</body>
</html>
