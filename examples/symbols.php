<?php

include('../PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(1000, " [:bar] ");
$pg->symbolComplete = "#";
$pg->symbolIncomplete = "-";

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->tick();
}