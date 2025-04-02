<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Débat</title>
    <style>
        :root {
            --primary-color: #3d3548;
            --secondary-color: #f0f0f0;
            --accent-blue: #8c91e3;
            --accent-red: #ff6b6b;
            --text-color: #333;
            --border-radius: 10px;
            --gold: #FFD700;
            --silver: #C0C0C0;
            --bronze: #CD7F32;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        /* Navbar en haut */
        .navbar {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Contenu de la page */
        .content {
            flex-grow: 1;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .debate-form {
            background-color: var(--secondary-color);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .debate-form label {
            font-size: 1rem;
            color: var(--primary-color);
            font-weight: bold;
        }

        .debate-form input, .debate-form textarea {
            padding: 10px;
            border-radius: var(--border-radius);
            border: 1px solid #ccc;
            font-size: 1rem;
            color: var(--text-color);
            margin-bottom: 10px;
        }

        .debate-form input:focus, .debate-form textarea:focus {
            border-color: var(--accent-blue);
            outline: none;
        }

        .debate-form button {
            background-color: var(--accent-blue);
            color: white;
            padding: 12px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .debate-form button:hover {
            background-color: #7073c0;
        }

        .debate-form textarea {
            height: 150px;
            resize: vertical;
        }
    </style>
</head>
<body>

<!-- Contenu principal -->
<div class="content">
    <form action="/debat/creer" method="POST" class="debate-form">
        <label for="nom">Nom du débat :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea>

        <label for="camp1">Nom du premier camp :</label>
        <input type="text" id="camp1" name="camp1" required>

        <label for="camp2">Nom du second camp :</label>
        <input type="text" id="camp2" name="camp2" required>

        <button type="submit">Créer le débat</button>
    </form>
</div>

</body>
</html>
