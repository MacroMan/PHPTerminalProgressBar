<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TerminalProgress\Bar;

$pg = new Bar(1000, "Look mum, no bar! :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s");

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->tick();
}
