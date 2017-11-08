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
include('PHPTerminalProgressBar.php');

$pg = new PHPTerminalProgressBar(1000);

for ($i = 0; $i < 1000; $i++) {
	usleep(10000);
	$pg->tick();
}
```

You can also use `update(amount)` to set the current tick value instead of ticking each time there is an increment:

```php
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
- `throttle` minimum time between updates in milliseconds defaulting to 16
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


### Interrupt

To display a message during progress bar execution, use `interrupt()`
```php
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

## License

See LICENSE