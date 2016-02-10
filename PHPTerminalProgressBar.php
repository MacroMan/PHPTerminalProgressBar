<?php

Class PHPTerminalProgressBar {

	public $output, $narrow = false;

	public function __construct($total = 0, $output = STDERR, $narrow = false) {
		$this->output = $output;
		$this->narrow = $narrow;
		$bar = ($narrow) ? 50 : 100;
		$write = sprintf("\033[0G\033[2K[%'=0s>%-{$bar}s] - 0%% - 0/$total", "", "");
		fwrite($this->output, $write);
	}

	public function update($done, $total) {
		$perc = $bar = floor(($done / $total) * 100);
		$left = 100 - $perc;
		if ($this->narrow) {
			$bar = floor($bar / 2);
			$left = ceil($left / 2);
		}
		$write = sprintf("\033[0G\033[2K[%'={$bar}s>%-{$left}s] - $perc%% - $done/$total", "", "");
		fwrite($this->output, $write);
	}

	public function end() {
		fwrite($this->output, "\n");
	}

}
