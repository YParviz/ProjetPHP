<h1>Poster un argument</h1>
<form method="post" action="debate/<?= $idDebate ?>/postArgs">
    <input type="radio" name="camp" id="<?= $camps[0]->getId() ?>" value="<?= $camps[0]->getId() ?>" />
    <label for="<?= $camps[0]->getId() ?>"><?= $camps[0]->getName() ?></label>
</form>
