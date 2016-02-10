# PHPTerminalProgressBar
A progress bar utility for terminal written in PHP

The script prints to STDERR so normal output can still be redirected if needed.

The output can be changed by passing in a write location when initiating or you can set the $output property of the class

This should run fine on most *nix and OSx environments and also on Windows as long as ANSI.SYS is installed. See https://en.wikipedia.org/wiki/ANSI.SYS

@author MacroMan (David Wakelin) - davidwakelin.co.uk

@licence GNU GENERAL PUBLIC LICENSE v2 - See LICENSE

@usage - Include the class in your script. Create a new instance to start the progress bar and call update(); to update it.

@example - See example.php which can be run from terminal
```
include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(100);

for ($i = 0; $i <= 100; $i++) {
	usleep(100000);
	$pg->update($i, 100);
}

$pg->end();
````

@example - For usage with narrower screens, eg. un-maximized terminal windows or native server screen, see example_narrow.php

```
<?php

include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(100, STDERR, true);

for ($i = 0; $i <= 100; $i++) {
	usleep(100000);
	$pg->update($i, 100);
}

$pg->end();
```
