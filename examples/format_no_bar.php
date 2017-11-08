<?php

include('../PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(1000, "Look mum, no bar! :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s");

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->tick();
}