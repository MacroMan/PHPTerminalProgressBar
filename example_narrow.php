<?php

include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(100, STDERR, true);

for ($i = 0; $i <= 100; $i++) {
	usleep(100000);
	$pg->update($i, 100);
}

$pg->end();
