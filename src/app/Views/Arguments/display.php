<?php
/* Fichier pour afficher un argument et tout ses sous arguments */

echo "<div class='argument'>";
echo "<div class='argument".$argument->getNumCamp()."'>";
echo "<p>".$argument->getText()."</p>";
echo "<div class='divVote'>";
if (!in_array($argument->getId(), $votes)) {
    echo "<input type='image' src='../../image/arguments/vote.png' class='imageVote' onclick='vote(".$argument->getId().")' id='imgVoteArg".$argument->getId()."' class='center'>";
} else {
    echo "<input type='image' src='../../image/arguments/unvote.png' class='imageVote' onclick='unvote(".$argument->getId().")' id='imgVoteArg".$argument->getId()."' class='center'>";
}
echo "<p id='numVoteArg".$argument->getId()."'>".$argument->getVoteNumber()." votes</p>";
echo "</div>";
echo "</div>";
echo "<div class='sousArgument'>";
foreach ($argument->getSousArguments() as $sousArgument) {
    echo "<div class='sousArgument".$sousArgument->getNumCamp()."'>";
    echo "<p>".$sousArgument->getText()."</p>";
    echo "<div class='divVote'>";
    if (!in_array($sousArgument->getId(), $votes)) {
        echo "<input type='image' src='../../image/arguments/vote.png' class='imageVote' onclick='vote(".$sousArgument->getId().")' id='imgVoteArg".$sousArgument->getId()."'>";
    } else {
        echo "<input type='image' src='../../image/arguments/unvote.png' class='imageVote' onclick='unvote(".$sousArgument->getId().")' id='imgVoteArg".$sousArgument->getId()."'>";
    }
    echo "<p id='numVoteArg".$sousArgument->getId()."'>".$sousArgument->getVoteNumber()." votes</p>";
    echo "</div>";
    echo "</div>";
}
echo "</div>";
echo "</div>";
