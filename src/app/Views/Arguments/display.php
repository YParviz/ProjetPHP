<?php
/* Fichier pour afficher un argument et tout ses sous arguments */

echo "<div class='argument'>";
echo "<div class='argument".$argument->getNumCamp()."'>";
echo "<p>".$argument->getText()."</p>";
echo "<p class='center'><button onclick='redirect(".$idDebat.",".$argument->getId().")'>Voter</button>";
echo "  ".$argument->getVoteNumber()." votes</p>";
echo "</div>";
echo "<div class='sousArgument'>";
foreach ($argument->getSousArguments() as $sousArgument) {
    echo "<div class='sousArgument".$sousArgument->getNumCamp()."'>";
    echo "<p>".$sousArgument->getText()."</p>";
    echo "<p class='center'><button>Voter</button>";
    echo "  ".$sousArgument->getVoteNumber()." votes</p>";
    echo "</div>";
}
echo "</div>";
echo "</div>";