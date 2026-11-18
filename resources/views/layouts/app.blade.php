<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Morpion</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <h1>Jeu du Morpion</h1>
    <p>Partie de Morpion.</p>
    
    <main>
        @yield('contenu')
    </main>
</body>
</html>