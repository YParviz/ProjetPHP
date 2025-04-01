<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DebatArena - Login</title>
    <style>
        body {
            display: flex;
            height: 100vh;
            width: 100vw;
            margin: 0;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .card {
            background-color: #FEF8E8;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .email, .password, .pseudo {
            display: flex;
            flex-direction: column;
            text-align: left;
            margin-bottom: 16px;
        }

        label {
            margin-bottom: 4px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"]{
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 12px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .button {
            background-color: #A2A9DC;
            border: none;
            border-radius: 20px;
            padding: 10px 30px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .button_retour {
            background-color: #B2C3D3;
            border: none;
            border-radius: 20px;
            padding: 10px 30px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .button:hover {
            transform: scale(1.05);
            box-shadow: 5px 5px 12px rgba(0, 0, 0, 0.3);
        }

        .btn{
            display: flex;
            gap: 16px;
            justify-content: center;
        }
    </style>
</head>
<body>
<form action="/register" method="POST" class="card">
    <h2>Créer un compte</h2>
    <div class="pseudo">
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" required>
    </div>

    <div class="email">
        <label for="email">Adresse email :</label>
        <input type="email" name="email" id="email" placeholder="Adresse email" required>
    </div>

    <div class="password">
        <label for="mdp">Mot de passe :</label>
        <input type="password" name="password" id="password" placeholder="Mot de passe" required>
    </div>
    <div class="btn">
        <button type="submit" class="button_retour" onclick="retour()">Retour</button>
        <button type="submit" class="button">Créer un compte</button>
    </div>


    <script>
        function retour() {
            window.location.href = "/";
        }
    </script>
</form>
</body>
</html>
