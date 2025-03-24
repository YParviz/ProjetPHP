<h1>Poster un argument</h1>
<form method="post" action="debate/<?= $idDebate ?>/postArgs">
    <input type="radio" name="camp" id="camp1" value="<?= $camps[0]->getId() ?>" />
    <label for="camp1"><?= $camps[0]->getName() ?></label>
    <input type="radio" name="camp" id="camp1" value="<?= $camps[0]->getId() ?>" />
    <label for="camp1"><?= $camps[0]->getName() ?></label>
</form>
