<?php

declare(ticks = 1);
pcntl_signal(SIGINT, function($signo) {
	fwrite(STDOUT, "\n\033[?25h");
	fwrite(STDERR, "\n\033[?25h");
	exit;
});

Class PHPTerminalProgressBar {

	const MOVE_START = "\033[1;1H";
	const HIDE_CURSOR = "\033[?25l";
	const SHOW_CURSOR = "\033[?25h";
	const ERASE_DISPLAY = "\033[2J";
	const COLOR_RED = "\033[1;31m";
	const COLOR_GREEN = "\033[1;32m";
	const BACKGROUND_RED = "\033[41m";
	const BACKGROUND_GREEN = "\033[42m";
	const FORMAT_RESET = "\033[0m";

	public $output,
			$width,
			$lastTime;

	public function __construct($output = STDERR) {
		$this->width = exec("tput cols") / 100;
		if (!is_numeric($this->width)) {
			$this->width = 0.8;
		}
		$this->output = $output;
		fwrite($this->output, self::HIDE_CURSOR);
		fwrite($this->output, self::ERASE_DISPLAY);
		fwrite($this->output, self::MOVE_START);
		$this->lastTime = microtime();
	}

	public function update($done, $total) {
		$check = microtime() - $this->lastTime;
		if ($check > 0.01) {
			$perc = ($total) ? floor(($done / $total) * 100) : 0;
			$percPrint = floor($perc * $this->width);

			$percText = "$done/$total - $perc%";
			$percBefore = str_repeat(" ", floor((($this->width * 100) - strlen($percText)) / 2));
			$percAfter = str_repeat(" ", ceil((($this->width * 100) - strlen($percText)) / 2));
			$percParts = str_split($percBefore . $percText . $percAfter);

			fwrite($this->output, self::MOVE_START);
			fwrite($this->output, self::COLOR_RED . self::BACKGROUND_GREEN);

			foreach ($percParts as $i => $part) {
				if ($i == $percPrint) {
					fwrite($this->output, self::COLOR_GREEN . self::BACKGROUND_RED);
				}
				fwrite($this->output, $part);
			}

			fwrite($this->output, self::FORMAT_RESET);
		}
		$this->lastTime = microtime();
	}

	public function __destruct() {
		fwrite($this->output, "\n" . self::SHOW_CURSOR);
	}

}
