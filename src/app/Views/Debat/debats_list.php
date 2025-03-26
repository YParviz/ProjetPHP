<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DebatArena - Accueil</title>
    <link rel="stylesheet" href="/css/debats/stylesListeDebats.css" >
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
<div class="container">
    <!-- Colonne de gauche  -->
    <div class="left-column">
        <div class="section-title">Récemment débattu</div>

        <!-- Liste des débats récents -->
        <div class="recent-debates">
            <?php foreach ($debatsRecents as $debat): ?>
                <?php
                $idDebat = $debat->getId();
                $statsDebat = $statsRecents[$idDebat] ?? ['nb_vote_camp_1' => 0, 'nb_vote_camp_2' => 0, 'pourcentage_camp_1' => 50, 'pourcentage_camp_2' => 50, 'nb_participants' => 0];
                $nbParticipants = $statsDebat['nb_participants'];
                $pourcentageCamp1 = $statsDebat['pourcentage_camp_1'];
                $pourcentageCamp2 = $statsDebat['pourcentage_camp_2'];
                ?>
                <a href="/debat/<?= $debat->getId() ?>" class="debate-item">
                    <div class="debate-title"><?= htmlspecialchars($debat->getName()) ?></div>
                </a>
                <div class="debate-stats"><?= $nbParticipants ?> participants</div>
                <div class="progress-container">
                    <div class="progress-blue" style="width: <?= $pourcentageCamp1 ?>%;"><?= $pourcentageCamp1 ?>%</div>
                    <div class="progress-red" style="width: <?= $pourcentageCamp2 ?>%;"><?= $pourcentageCamp2 ?>%</div>
                </div>
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

        <?php foreach ($debatsTendance as $debat): ?>
            <?php
            $idDebat = $debat->getId();
            $statsDebat = $statsTendance[$idDebat] ?? ['nb_vote_camp_1' => 0, 'nb_vote_camp_2' => 0, 'pourcentage_camp_1' => 50, 'pourcentage_camp_2' => 50, 'nb_participants' => 0];
            $nbParticipants = $statsDebat['nb_participants'];
            $pourcentageCamp1 = $statsDebat['pourcentage_camp_1'];
            $pourcentageCamp2 = $statsDebat['pourcentage_camp_2'];
            ?>

            <a href="/debat/<?= $debat->getId() ?>" class="debate-item">
                <div class="debat">
                    <h3><?= htmlspecialchars($debat->getName()) ?></h3>
                    <div class="debate-description"><?= htmlspecialchars($debat->getDescription()) ?></div>

                    <div class="progress-container">
                        <div class="progress-blue" style="width: <?= $pourcentageCamp1 ?>%;"><?= $pourcentageCamp1 ?>%</div>
                        <div class="progress-red" style="width: <?= $pourcentageCamp2 ?>%;"><?= $pourcentageCamp2 ?>%</div>
                    </div>

                    <div class="debate-stats">
                        <?= $nbParticipants ?> participants
                    </div>
                </div>
            </a>

        <?php endforeach; ?>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="/debats/<?= $page - 1 ?>">Précédent</a>
            <?php else: ?>
                <a href="#" class="disabled">Précédent</a>
            <?php endif; ?>

            <?php if (!$noMoreDebatsNextPage): ?>
                <a href="/debats/<?= $page + 1 ?>">Suivant</a>
            <?php else: ?>
                <a href="#" class="disabled">Suivant</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>