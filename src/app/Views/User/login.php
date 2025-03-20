<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Connexion</h2>
    <form action="/login" method="POST">
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required>
        <br>
        
        <label for="mdp">Mot de passe :</label>
        <input type="password" name="mdp" id="mdp" required>
        <br>
        
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
