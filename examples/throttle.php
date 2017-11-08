<?php

include('../PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(1000);
// Set a 100 millisecond threshold
$pg->throttle = 0.1;

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->tick();
}