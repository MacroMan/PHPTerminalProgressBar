<?php

include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(1000);

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	if ($i % 100 == 0) {
		// Interupt every 100th tick
		$pg->interupt($i);
	}
	$pg->tick();
}