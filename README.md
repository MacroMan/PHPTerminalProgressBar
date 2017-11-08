Flexible ascii progress bar.

## Installation

```
Include the PHPTerminalProgressBar class
```

## Usage

First we create a `ProgressBar`, giving it a `format` string
as well as the `total`, telling the progress bar when it will
be considered complete. After that all we need to do is `tick()` appropriately.

```php
// example/basic.php
include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(1000);

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->tick();
}
```

You can also use `update(amount)` to set the current tick value instead of ticking each time there is an increment:

```php
// example/update.php
include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(1000);

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->update($i);
}
```

### Options

These are properties in the object you can read/set:

- `symbolComplete` completion character defaulting to "="
- `symbolIncomplete` incomplete character defaulting to " "
- `throttle` minimum time between updates in seconds defaulting to 0.016
- `current` current tick
- `total` same value passed in when initialising
- `percent` (read only) current percentage completion
- `eta` (read only) estimate seconds until completion
- `rate` (read only) number of ticks per second
- `elapsed` (read only) seconds since initialisation

### Tokens

These are tokens you can use in the format of your progress bar.

- `:bar` the progress bar itself
- `:current` current tick number
- `:total` total ticks
- `:elapsed` time elapsed in seconds
- `:percent` completion percentage
- `:eta` estimated completion time in seconds
- `:rate` rate of ticks per second

### Format example
```php
// example/format.php
// Full options
new PHPTerminalProgressBar(10, "Progress: [:bar] - :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s");
```

```php
// example/format_percent.php
// Just percentage plus the bar
new PHPTerminalProgressBar(10, ":bar :percent%");
```

```php
// example/format_no_bar.php
// You don't even have to have a bar
new PHPTerminalProgressBar(10, "Look mum, no bar! :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s");
```

### Interrupt example

To display a message during progress bar execution, use `interrupt()`
```php
// example/interrupt.php
$pg = new PHPTerminalProgressBar(1000);

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	if ($i % 100 == 0) {
		// Interupt every 100th tick
		$pg->interupt($i);
	}
	$pg->tick();
}
```

### Symbols example

To change the symbols used on the progress bar
```php
// example/symbols.php
$pg = new PHPTerminalProgressBar(1000);
$pg->symbolComplete = "#";
$pg->symbolIncomplete = "-";
```

### Throttle example

If you are `ticking` several hundred or thousands of times per second, the `throttle` setting will be prevent the progress bar from slowing down execution time too much, however, 16ms is quite optimistic, so you may wish to increase it on slower machines.

```php
// example/throttle.php
$pg = new PHPTerminalProgressBar(1000);
// Set a 100 millisecond threshold
$pg->throttle = 0.1;
```

## License

See LICENSE