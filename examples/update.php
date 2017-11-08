<?php

include('../PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(1000);

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->update($i);
}