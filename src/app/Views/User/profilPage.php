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
            height: 100vh; /* Prend toute la hauteur de l'écran */
            width: 100vw;  /* Prend toute la largeur de l'écran */
            margin: 0;     /* Supprime les marges par défaut du body */
            box-sizing: border-box; /* Évite les débordements */
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

        .profile_info p {
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

        .button:hover {
            transform: scale(1.05);
            box-shadow: 5px 5px 12px rgba(0, 0, 0, 0.3);
        }

        .statistique p {
            background-color: #fff;
            border-radius: 8px;
            padding: 8px 12px;
            margin: 4px 0;
        }

        .div_stat{
            display: grid;
            grid-template-rows: 1fr 1fr;
            gap: 16px;
        }

        .highlight {
            background-color: #FFEE58; /* Jaune vif */
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
        <p><strong>Pseudo:</strong> <?php echo htmlspecialchars($pseudo); ?></p>
        <p><strong>Adresse email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Mot de passe:</strong> <?php echo htmlspecialchars($mdp); ?></p>
        <p><strong>Compte créé le :</strong> <?php echo htmlspecialchars($date_creation instanceof DateTime 
            ? $date_creation->format('d/m/Y H:i:s') 
            : $date_creation); ?>
        </p>
        <div class="bouton">
            <button class="button button-modifier">Modifier</button>
            <button class="button button-supprimer">Supprimer</button>
        </div>
    </div>

    <div class="div_stat">
        <div class="recent_debat">
            <h2>Récemment débattu</h2>
            <?php foreach ($debates as $debate): ?>
            <div class="debat">
                <h3><?php echo htmlspecialchars($debate->getNomD()); ?></h3>
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