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

        .email, .password {
            display: flex;
            flex-direction: column;
            text-align: left;
            margin-bottom: 16px;
        }

        label {
            margin-bottom: 4px;
        }

        input[type="email"],
        input[type="password"] {
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

        .button:hover {
            transform: scale(1.05);
            box-shadow: 5px 5px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <form action="/login" method="POST" class="card">
        <h2>Ravi de vous revoir !</h2>
        <div class="email">
            <label for="email">Adresse email :</label>
            <input type="email" name="email" id="email" placeholder="Adresse email" required>
        </div>

        <div class="password">
            <label for="mdp">Mot de passe :</label>
            <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" required>
        </div>

        <button type="submit" class="button">Se connecter</button>
    </form>
</body>
</html>
