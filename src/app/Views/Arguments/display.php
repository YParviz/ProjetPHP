<?php
/* Fichier pour afficher un argument et tout ses sous arguments */

echo "<div class='argument'>";
echo "<div class='argument".$argument->getNumCamp()."'>";
echo "<p>".$argument->getText()."</p>";
echo "<p class='center' id='numVoteArg".$argument->getId()."'>";
if(!in_array($argument->getId(), $votes)) {
    echo "<input type='image' src='../../image/arguments/vote.png' class='imageVote' onclick='vote(".$argument->getId().")'>";
} else {
    echo "<input type='image' src='../../image/arguments/unvote.png' class='imageVote' onclick='unvote(".$argument->getId().")'>";
}
echo "  ".$argument->getVoteNumber()." votes</p>";
echo "</div>";
echo "<div class='sousArgument'>";
foreach ($argument->getSousArguments() as $sousArgument) {
    echo "<div class='sousArgument".$sousArgument->getNumCamp()."'>";
    echo "<p>".$sousArgument->getText()."</p>";
    echo "<p class='center'><button onclick='vote(".$argument->getId().")'>Voter</button>";
    echo "  ".$sousArgument->getVoteNumber()." votes</p>";
    echo "</div>";
}
echo "</div>";
echo "</div>";