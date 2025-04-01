<head>
    <link rel="stylesheet" href="/css/arguments/styleCreateArgument.css">
</head>
<body>
<div class="container">
    <form class="card" method="post" action="/debate/<?= $idDebate ?>/postArg">
        <h1>Poster un argument</h1>
        <p>Camp :
        <input class="radioSetCamp" type="radio" name="camp" id="camp1" value="<?= $camps[0]->getId() ?>" checked/>
        <label for="camp1"><?= $camps[0]->getName() ?></label>
        <input class="radioSetCamp" type="radio" name="camp" id="camp2" value="<?= $camps[1]->getId() ?>" />
        <label for="camp2"><?= $camps[1]->getName() ?></label>
        </p>
        <textarea class="textArgument" name="argument" required></textarea>
        <br>
        <button class="buttonBack" onclick="window.location.href='/debat/<?= $idDebate ?>'">Retour</button>
        <input class="buttonSubmit" id="buttonBack" type="submit">
    </form>
</div>
</body>