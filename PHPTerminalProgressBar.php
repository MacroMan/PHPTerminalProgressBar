<?php

declare(ticks = 1);
pcntl_signal(SIGINT, function($signo) {
	fwrite(STDOUT, "\n\033[?25h");
	fwrite(STDERR, "\n\033[?25h");
	exit;
});

Class PHPTerminalProgressBar {

	const MOVE_START = "\033[1G";
	const HIDE_CURSOR = "\033[?25l";
	const SHOW_CURSOR = "\033[?25h";
	const ERASE_LINE = "\033[2K";

	// Available screen width
	private $width;
	// Ouput stream. Usually STDOUT or STDERR
	private $stream;
	// Output string format
	private $format;
	// Time the progress bar was initialised in seconds (with millisecond precision)
	private $startTime;
	// Time since the last draw
	private $timeSinceLastCall;
	// Pre-defined tokens in the format
	private $ouputFind = array(':current', ':total', ':elapsed', ':percent', ':eta', ':rate');
	// Do not run drawBar more often than this (bypassed by interupt())
	public $throttle = 0.016; // 16 ms
	// The symbol to denote completed parts of the bar
	public $symbolComplete = "=";
	// The symbol to denote incomplete parts of the bar
	public $symbolIncomplete = " ";
	// Current tick number
	public $current = 0;
	// Maximum number of ticks
	public $total = 1;
	// Seconds elapsed
	public $elapsed = 0;
	// Current percentage complete
	public $percent = 0;
	// Estimated time until completion
	public $eta = 0;
	// Current rate
	public $rate = 0;

	public function __construct($total = 1, $format = "Progress: [:bar] - :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s", $stream = STDERR) {
		// Get the terminal width
		$this->width = exec("tput cols");
		if (!is_numeric($this->width)) {
			// Default to 80 columns, mainly for windows users with no tput
			$this->width = 80;
		}

		$this->total = $total;
		$this->format = $format;
		$this->stream = $stream;

		// Initialise the display
		fwrite($this->stream, self::HIDE_CURSOR);
		fwrite($this->stream, self::MOVE_START);

		// Set the start time
		$this->startTime = microtime(true);
		$this->timeSinceLastCall = microtime(true);

		$this->drawBar();
	}

	/**
	 * Add $amount of ticks. Usually 1, but maybe different amounts if calling
	 * this on a timer or other unstable method, like a file download.
	 */
	public function tick($amount = 1) {
		$this->update($this->current + $amount);
	}

	public function update($amount) {
		$this->current = $amount;
		$this->elapsed = microtime(true) - $this->startTime;
		$this->percent = $this->current / $this->total * 100;
		$this->rate = $this->current / $this->elapsed;
		$this->eta = ($this->current) ? ($this->elapsed / $this->current * $this->total - $this->elapsed) : false;
		$drawElapse = microtime(true) - $this->timeSinceLastCall;
		if ($drawElapse > $this->throttle) {
			$this->drawBar();
		}
	}

	/**
	 * Add a message on a newline before the progress bar
	 */
	public function interupt($message) {
		fwrite($this->stream, self::MOVE_START);
		fwrite($this->stream, self::ERASE_LINE);
		fwrite($this->stream, $message . "\n");
		$this->drawBar();
	}

	/**
	 * Does the actual drawing
	 */
	private function drawBar() {
		$this->timeSinceLastCall = microtime(true);
		fwrite($this->stream, self::MOVE_START);

		$replace = array(
			$this->current,
			$this->total,
			$this->roundAndPadd($this->elapsed),
			$this->roundAndPadd($this->percent),
			$this->roundAndPadd($this->eta),
			$this->roundAndPadd($this->rate),
		);

		$output = str_replace($this->ouputFind, $replace, $this->format);

		if (strpos($output, ':bar') !== false) {
			$availableSpace = $this->width - strlen($output) + 4;
			$done = $availableSpace * ($this->percent / 100);
			$left = $availableSpace - $done;
			$output = str_replace(':bar', str_repeat($this->symbolComplete, $done) . str_repeat($this->symbolIncomplete, $left), $output);
		}

		fwrite($this->stream, $output);
	}

	/**
	 * Adds 0 and space padding onto floats to ensure the format is fixed length nnn.nn
	 */
	private function roundAndPadd($input) {
		$parts = explode(".", round($input, 2));
		$output = $parts[0];
		if (isset($parts[1])) {
			$output .= "." . str_pad($parts[1], 2, 0);
		} else {
			$output .= ".00";
		}

		return str_pad($output, 6, " ", STR_PAD_LEFT);
	}

	/**
	 * Cleanup
	 */
	public function end() {
		fwrite($this->stream, "\n" . self::SHOW_CURSOR);
	}

	public function __destruct() {
		$this->end();
	}

}
