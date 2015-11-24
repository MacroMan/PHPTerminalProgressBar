<?php

Class PHPTerminalProgressBar {

	public $output;

	public function __construct($total = 0, $output = STDERR) {
		$this->output = $output;
		$write = sprintf("\033[0G\033[2K[%'=0s>%-100s] - 0%% - 0/$total", "", "");
		fwrite($this->output, $write);
	}

	public function update($done, $total) {
		$perc = floor(($done / $total) * 100);
		$left = 100 - $perc;
		$write = sprintf("\033[0G\033[2K[%'={$perc}s>%-{$left}s] - $perc%% - $done/$total", "", "");
		fwrite($this->output, $write);
	}

	public function end() {
		fwrite($this->output, "\n");
	}

}
