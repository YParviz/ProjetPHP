<?php

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../css/arguments/styleListArguments.css\" />";
echo "<script src='../../js/arguments/scriptListArguments.js'></script>";
echo "</head>";

echo "<body>";
echo "<div class='arguments'>";
echo "<div class='camp1'>";
echo "<h2 class='center'>".$camp1->getName()."</h2>";
foreach ($arguments[0] as $argument) {
    include __DIR__."/display.php";
}
echo "</div>";
echo "<div class='camp2'>";
echo "<h2 class='center'>".$camp2->getName()."</h2>";
foreach ($arguments[1] as $argument) {
    include __DIR__."/display.php";
}
echo "</div>";
echo "</div>";
echo "</body>";
echo "</html>";