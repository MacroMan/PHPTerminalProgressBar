<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TerminalProgress\Bar;

$pg = new Bar(960, "[:bar] :percent% - Elapsed::elapseds");

$pg->symbolComplete = "#";
$pg->symbolIncomplete = "-";

$pg->secondPrecision = 2;
$pg->percentPrecision = 4;

for ($i = 0; $i < 960; $i++) {
	usleep(10000);
	$pg->tick();
}
