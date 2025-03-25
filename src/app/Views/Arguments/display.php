<div class="argument">
    <div class="argument<?= $argument->getNumCamp() ?>">
        <p><?= $argument->getText() ?></p>
        <div class="divVote">
            <?php if (!in_array($argument->getId(), $votes)): ?>
                <input type="image" src="../../image/arguments/vote.png" alt="Bouton de vote" class="imageVote" id="imgVoteArg<?= $argument->getId()?>" onclick="vote(<?= $argument->getId() ?>)">
            <?php else: ?>
                <input type="image" src="../../image/arguments/unvote.png" alt="Bouton de vote" class="imageVote" id="imgVoteArg<?= $argument->getId()?>" onclick="unvote(<?= $argument->getId() ?>)">
            <?php endif; ?>
            <p id="numVoteArg<?= $argument->getId() ?>"><?= $argument->getVoteNumber() ?> votes</p>
        </div>
    </div>
    <div class="sousArgument">
        <?php foreach($argument->getSousArguments() as $sousArgument): ?>
            <div class="sousArgument<?= $sousArgument->getNumCamp() ?>">
                <p><?= $sousArgument->getText() ?></p>
                <div class="divVote">
                    <?php if (!in_array($sousArgument->getId(), $votes)): ?>
                        <input type="image" src="../../image/arguments/vote.png" alt="Bouton de vote" class="imageVote" id="imgVoteArg<?= $argument->getId()?>" onclick="vote(<?= $argument->getId() ?>)">
                    <?php else: ?>
                        <input type="image" src="../../image/argumets/unvote.png" alt="Bouton de vote" class="imageVote" id="imgVoteArg<?= $argument->getId()?>" onclick="unvote(<?= $argument->getId() ?>)">
                    <?php endif; ?>
                    <p id="numVoteArg<?= $argument->getId() ?>"><?= $argument->getVoteNumber() ?> votes</p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
