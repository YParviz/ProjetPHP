<?php
/* Fichier pour afficher un argument et tout ses sous arguments */

echo "<div class='argument".$argument->getNumCamp()."'>";
echo "<p>".$argument->getText()."</p>";
echo "<p class='center'><button>Voter</button>";
echo "  ".$argument->getVoteNumber()." votes</p>";

echo "</div>";