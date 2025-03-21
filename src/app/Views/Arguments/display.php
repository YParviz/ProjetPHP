<?php
/* Fichier pour afficher un argument et tout ses sous arguments */

echo "<div class='argument'>";
echo "<div class='argument".$argument->getNumCamp()."'>";
echo "<p>".$argument->getText()."</p>";
displayOptionVote($argument);
echo "</div>";
echo "<div class='sousArgument'>";
foreach ($argument->getSousArguments() as $sousArgument) {
    echo "<div class='sousArgument".$sousArgument->getNumCamp()."'>";
    echo "<p>".$sousArgument->getText()."</p>";
    displayOptionVote($sousArgument);
    echo "</div>";
}
echo "</div>";
echo "</div>";


function displayOptionVote($arg): void
{
    echo "<div class='center'>";
    if(!in_array($arg->getId(), $votes)) {
        echo "<input type='image' src='../../image/arguments/vote.png' class='imageVote' onclick='vote(".$arg->getId().")' id='imgVoteArg".$arg->getId()."'>";
    } else {
        echo "<input type='image' src='../../image/arguments/unvote.png' class='imageVote' onclick='unvote(".$arg->getId().")' id='imgVoteArg".$arg->getId()."'>";
    }
    echo "<p id='numVoteArg".$arg->getId()."'>".$arg->getVoteNumber()." votes</p>";
    echo "</div>";
}