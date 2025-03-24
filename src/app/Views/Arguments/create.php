<h1>Poster un argument</h1>
<form method="post" action="/debate/<?= $idDebate ?>/postArg">
    <input type="radio" name="camp" id="camp1" value="<?= $camps[0]->getId() ?>" checked/>
    <label for="camp1"><?= $camps[0]->getName() ?></label>
    <input type="radio" name="camp" id="camp2" value="<?= $camps[1]->getId() ?>" />
    <label for="camp2"><?= $camps[1]->getName() ?></label>
    <br>
    <textarea name="argument"></textarea>
    <br>
    <input type="submit">
</form>
