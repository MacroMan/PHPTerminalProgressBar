# PHPTerminalProgressBar

A progress bar utility for terminal written in PHP

The script prints to STDERR so normal output can still be redirected if needed.

The output can be changed by passing in a write location when initiating or you can set the $output property of the class.

This should run fine on most *nix and OSx environments and also on Windows as long as ANSI.SYS is installed. See https://en.wikipedia.org/wiki/ANSI.SYS

## Code Example

```
include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar();

for ($i = 0; $i <= 100; $i++) {
	usleep(100000);
	$pg->update($i, 100);
}
````

## Installation

Download and include `PHPTerminalProgressBar.php`

## API Reference

`__construct($output = STDERR);` - Initialise the script, optionally setting the output.

`update($done, $total)` - Update the progress bar, giving the current completed and total figures

## Tests

Run `example.php` from terminal or command prompt.

## License

GNU GENERAL PUBLIC LICENSE v2 - See LICENSE