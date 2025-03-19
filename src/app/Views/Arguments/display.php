<?php
/* Fichier pour afficher un argument et tout ses sous arguments */

echo "<div class='argument'>";
echo "<div class='argument".$argument->getNumCamp()."'>";
echo "<p>".$argument->getText()."</p>";
echo "<p class='center' id='numVoteArg".$argument->getId()."'>";
echo "<button onclick='unvote(".$argument->getId().")'>";
if(!in_array($argument->getId(), $votes)) {
    echo "Voter"; //TODO : remplacer par une image
} else {
    echo "Dévoter"; //TODO : remplacer par une image
}
echo "</button>";
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