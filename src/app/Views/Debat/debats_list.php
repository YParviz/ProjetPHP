<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DebatArena - Accueil</title>
    <link rel="stylesheet" href="/debatarena/src/web/css/debats/stylesListeDebats.css" >
    <link rel="stylesheet" href="/debatarena/src/web/css/navbar.css">
</head>
<body>
<div class="container">
    <!-- Colonne gauche - Classement des meilleurs débatteurs et débats récents -->
    <div class="left-column">
        <div class="section-title">Récemment débattu</div>

        <!-- Liste des débats récents (limités à 3) -->
        <div class="recent-debates">
            <?php
            $recentDebats = array_slice($debatsRecents, 0, 3);
            foreach ($recentDebats as $debat):
                $idDebat = $debat->getId();
                $statsDebat = $statsRecents[$idDebat] ?? ['nb_vote_camp_1' => 0, 'nb_vote_camp_2' => 0, 'pourcentage_camp_1' => 50, 'pourcentage_camp_2' => 50, 'nb_participants' => 0];
                $nbParticipants = $statsDebat['nb_participants'];
                $pourcentageCamp1 = $statsDebat['pourcentage_camp_1'];
                $pourcentageCamp2 = $statsDebat['pourcentage_camp_2'];

                ?>
                <?php $basePath = '/DebatArena/src/web'; ?>
                <a href="/DebatArena/src/web/debat/<?= $debat->getId() ?>" class="debate-item">
                    <div class="debate-title"><?= htmlspecialchars($debat->getName()) ?></div>
                </a>
                <div class="debate-stats"><?= $nbParticipants ?> participants</div>
                <div class="progress-container">
                    <div class="progress-blue" style="width: <?= $pourcentageCamp1 ?>%;"><?= $pourcentageCamp1 ?>%</div>
                    <div class="progress-red" style="width: <?= $pourcentageCamp2 ?>%;"><?= $pourcentageCamp2 ?>%</div>
                </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Classement des meilleurs débatteurs -->
        <div class="section-title">Classement meilleur débatteur du mois</div>
        <div class="user-ranking">
            <?php if (empty($userRanking)): ?>
                <div class="no-rankings">Aucun classement disponible pour ce mois</div>
            <?php else: ?>
                <ul class="user-ranking-list">
                    <?php
                    foreach ($userRanking as $index => $user):
                        $rankNum = $index + 1;
                        $rankLabel = $rankNum == 1 ? '1er' : $rankNum . 'ème';
                        ?>
                        <li class="user-ranking-item">
                            <div class="rank-badge rank-<?= $rankNum ?>">
                                <?= $rankNum ?>
                            </div>
                            <div class="user-info">
                                <div class="user-name"><?= htmlspecialchars($user['pseudo']) ?></div>
                                <div class="user-votes">Vote total : <?= $user['total_votes'] ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="right-column">
        <div class="section-title">Débat Tendance</div>

        <?php
        // Limiter à 3 débats par page
        $debatsPage = array_slice($debatsTendance, ($page - 1) * 3, 3);

        foreach ($debatsPage as $debat):
            $idDebat = $debat->getId();
            $statsDebat = $statsTendance[$idDebat] ?? ['nb_vote_camp_1' => 0, 'nb_vote_camp_2' => 0, 'pourcentage_camp_1' => 50, 'pourcentage_camp_2' => 50, 'nb_participants' => 0];

            // Nombre de votes pour chaque camp
            $nbVotesCamp1 = $statsDebat['nb_vote_camp_1'];
            $nbVotesCamp2 = $statsDebat['nb_vote_camp_2'];
            $nbVotesTotal = $nbVotesCamp1 + $nbVotesCamp2;

            // Nombre de participants au débat
            $nbParticipants = $statsDebat['nb_participants'];

            // Pourcentages des votes par camp
            $pourcentageCamp1 = $statsDebat['pourcentage_camp_1'];
            $pourcentageCamp2 = $statsDebat['pourcentage_camp_2'];
            ?>

            <!-- Lien vers la page du débat -->
            <a href="/DebatArena/src/web/debat/<?= $debat->getId() ?>" class="debate-item">
                <div class="debat">
                    <h3><?= htmlspecialchars($debat->getName()) ?></h3>
                    <div class="debate-description"><?= htmlspecialchars($debat->getDescription()) ?></div>

                    <!-- Barre de progression pour les votes des camps -->
                    <div class="progress-container">
                        <div class="progress-blue" style="width: <?= $pourcentageCamp1 ?>%;"><?= $pourcentageCamp1 ?>%</div>
                        <div class="progress-red" style="width: <?= $pourcentageCamp2 ?>%;"><?= $pourcentageCamp2 ?>%</div>
                    </div>

                    <!-- Affichage des statistiques du débat -->
                    <div class="debate-stats">
                        <?= $nbParticipants ?> participants
                    </div>
                </div>
            </a>

        <?php endforeach; ?>

        <!-- Section Conseils & Astuces -->
        <div class="section-title">Conseils & Astuces</div>
        <div class="tips">
            <ul>
                <li><strong>Conseil 1:</strong> Soyez respectueux dans vos arguments.</li>
                <li><strong>Conseil 2:</strong> Assurez-vous de bien comprendre le sujet avant de débattre.</li>
                <li><strong>Conseil 3:</strong> N'oubliez pas de valider vos arguments pour augmenter votre score.</li>
            </ul>
        </div>

        <div class="pagination">
            <!-- Bouton précédent -->
            <?php if ($page > 1): ?>
                <a href="/DebatArena/src/web/debats/<?= $page - 1 ?>">Précédent</a>
            <?php else: ?>
                <a href="#" class="disabled">Précédent</a>
            <?php endif; ?>

            <!-- Vérifier s'il y a des débats sur la page suivante -->
            <?php if (!$noMoreDebatsNextPage): ?>
                <a href="/DebatArena/src/web/debats/<?= $page + 1 ?>">Page suivante</a>
            <?php else: ?>
                <a href="#" class="disabled">Suivant</a>
            <?php endif; ?>
        </div>

    </div>


</div>
</body>
</html>