<?php

include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar();

for ($i = 0; $i <= 100; $i++) {
	usleep(100000);
	$pg->update($i, 100);
}
