<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DebatArena - Profil Utilisateur</title>
    <style>
        body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            padding: 20px;
            font-family: Arial, sans-serif;
            height: 100vh;
            width: 100vw;
            margin: 0;
            box-sizing: border-box;
        }

        .profile_info, .recent_debat, .statistique {
            background-color: #FEF8E8;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 16px;
        }

        .profile_info p, .profile_info .editable {
            display: flex;
            flex-direction: column;
            margin-bottom: 8px;
        }

        .bouton {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 16px;
        }

        .button {
            border: none;
            border-radius: 20px;
            padding: 10px 30px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .button-modifier { background-color: #A2A9DC; }
        .button-supprimer { background-color: #F44336; }
        .button-enregistrer { background-color: #4CAF50; }

        .button:hover {
            transform: scale(1.05);
            box-shadow: 5px 5px 12px rgba(0, 0, 0, 0.3);
        }

        .hidden {
            display: none;
        }

        .statistique p {
            background-color: #fff;
            border-radius: 8px;
            padding: 8px 12px;
            margin: 4px 0;
        }

        .div_stat {
            display: grid;
            grid-template-rows: 1fr 1fr;
            gap: 16px;
        }

        .highlight {
            background-color: #FFEE58;
            font-weight: bold;
        }

        .recent_debat .debat {
            background-color: #fff;
            border-radius: 8px;
            padding: 8px 12px;
            margin: 8px 0;
        }
    </style>
</head>
<body>

    <div class="profile_info">
        <h2>Vos informations</h2>

        <!-- Champs non éditables par défaut -->
        <div id="displayFields">
            <p><strong>Pseudo :</strong> <?php echo htmlspecialchars($pseudo); ?></p>
            <p><strong>Adresse email :</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Mot de passe :</strong> <?php echo htmlspecialchars($mdp);?> </p>
        </div>

        <!-- Formulaire de modification caché par défaut -->
        <form method="POST" action="/updateProfile" id="editForm" class="hidden">
            <div class="editable">
                <label>Pseudo:</label>
                <input type="text" name="pseudo" value="<?php echo htmlspecialchars($pseudo); ?>">
            </div>

            <div class="editable">
                <label>Adresse email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="editable">
                <label>Mot de passe:</label>
                <input type="mdp" name="mdp" value="<?php echo htmlspecialchars($mdp); ?>">
            </div>

            <div class="bouton">
                <button type="submit" class="button button-enregistrer">Enregistrer</button>
            </div>
        </form>

        <p><strong>Compte créé le :</strong> 
            <?php echo htmlspecialchars($date_creation instanceof DateTime 
            ? $date_creation->format('d/m/Y H:i:s') 
            : $date_creation); ?>
        </p>

        <div class="bouton">
            <button type="button" class="button button-modifier" id="editButton" onclick="toggleEdit()">Modifier</button>
            <button type="button" class="button button-supprimer" onclick="confirmDelete()">Supprimer</button>
        </div>
    </div>

    <script>
        // Fonction pour afficher les champs en mode édition
        function toggleEdit() {
            const displayFields = document.getElementById('displayFields');
            const editForm = document.getElementById('editForm');
            const editButton = document.getElementById('editButton');

            // Bascule entre affichage des champs et des inputs
            displayFields.classList.toggle('hidden');
            editForm.classList.toggle('hidden');

            // Change le bouton "Modifier" en "Annuler"
            if (!editForm.classList.contains('hidden')) {
                editButton.textContent = "Annuler";
                editButton.classList.remove('button-modifier');
                editButton.classList.add('button-supprimer');
            } else {
                editButton.textContent = "Modifier";
                editButton.classList.remove('button-supprimer');
                editButton.classList.add('button-modifier');
            }
        }

        // Confirmation avant suppression du compte
        function confirmDelete() {
            const confirmation = confirm("Si vous acceptez la suppression, vous serez redirigé vers la page d'accueil.");
            if (confirmation) {
                window.location.href = "/deleteProfile";
            }
        }
    </script>

    <div class="div_stat">
        <div class="recent_debat">
            <h2>Récemment débattu</h2>
            <?php foreach ($debates as $debate): ?>
            <div class="debat">
                <h3><?php echo htmlspecialchars($debate->getName()); ?></h3>
                <p>Nombre de participants : <?php echo $debate->getNbParticipants(); ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="statistique">
            <h2>Statistiques</h2>
            <?php if (isset($stats)) : ?>
                <p><strong>Nombre total de votes accumulés :</strong> <?php echo $stats['total_votes'] ?? 0; ?></p>
                <p><strong>Débat remporté :</strong> <?php echo $stats['debates_won'] ?? 0; ?> Victoires</p>
                <p class="highlight">Classement mois en cours : <?php echo $stats['rank_month'] ?? 'Non classé'; ?></p>
                <p><strong>Classement global :</strong> <?php echo $stats['rank_global'] ?? 'Non classé'; ?></p>
            <?php else : ?>
                <p>Aucune statistique disponible pour cet utilisateur.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
