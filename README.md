Flexible ascii progress bar.

## Installation

```
composer require macroman/terminal-progress-bar
```

## Usage

First we create a `Bar`, giving it a `format` string
as well as the `total`, telling the progress bar when it will
be considered complete. Then call `tick()` when needed.

```php
// examples/basic.php
use TerminalProgress\Bar;

$pg = new Bar(1000);

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->tick();
}
```

You can also use `update(amount)` to set the current tick value instead of ticking each time there is an increment:

```php
// examples/update.php
use TerminalProgress\Bar;

$pg = new Bar(1000);

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
// examples/format.php
// Full options
new Bar(10, "Progress: [:bar] - :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s");
```

```php
// examples/format_percent.php
// Just percentage plus the bar
new Bar(10, ":bar :percent%");
```

```php
// examples/format_no_bar.php
// You don't even have to have a bar
new Bar(10, "Look mum, no bar! :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s");
```

### Interrupt example

To display a message during progress bar execution, use `interrupt()`
```php
// examples/interrupt.php
$pg = new Bar(1000);

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
// examples/symbols.php
$pg = new Bar(1000);
$pg->symbolComplete = "#";
$pg->symbolIncomplete = "-";
```

### Throttle example

If you are `ticking` several hundred or thousands of times per second, the `throttle` setting will be prevent the progress bar from slowing down execution time too much, however, 16ms (default) is quite optimistic, so you may wish to increase it on slower machines.

```php
// examples/throttle.php
$pg = new Bar(1000);
// Set a 100 millisecond throttle
$pg->throttle = 0.1;
```

## License

See LICENSE
