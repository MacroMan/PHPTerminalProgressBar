<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TerminalProgress\Bar;

$pg = new Bar(1000, ":bar :percent%");

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->tick();
}
