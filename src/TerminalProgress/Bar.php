<?php

namespace TerminalProgress;

declare(ticks=1);
pcntl_signal(SIGINT, function ($signo) {
    fwrite(STDOUT, "\n\033[?25h");
    fwrite(STDERR, "\n\033[?25h");
    exit;
});

class Bar
{

    const MOVE_START = "\033[1G";
    const HIDE_CURSOR = "\033[?25l";
    const SHOW_CURSOR = "\033[?25h";
    const ERASE_LINE = "\033[2K";

    /**
     * Available screen width
     *
     * @var int
     */
    private $width;

    /**
     * Ouput stream. Usually STDOUT or STDERR
     *
     * @var false|mixed|resource
     */
    private $stream;

    /**
     * Output string format
     *
     * @var string
     */
    private $format;

    /**
     * Time the progress bar was initialised in seconds (with millisecond precision)
     *
     * @var float|string
     */
    private $startTime;

    /**
     * Time since the last draw
     *
     * @var float|string
     */
    private $timeSinceLastCall;

    /**
     * Pre-defined tokens in the format
     *
     * @var string[]
     */
    private $ouputFind = array(':current', ':total', ':elapsed', ':percent', ':eta', ':rate');

    /**
     * Do not run drawBar more often than this (bypassed by interupt())
     *
     * @var float
     */
    public $throttle = 0.1;

    /**
     * The symbol to denote completed parts of the bar
     *
     * @var string
     */
    public $symbolComplete = "=";

    /**
     * The symbol to denote incomplete parts of the bar
     *
     * @var string
     */
    public $symbolIncomplete = " ";

    /**
     * Number of decimal places to use for seconds units
     *
     * @var int
     */
    public $secondPrecision = 0;

    /**
     * Number of decimal places used for percentage units
     *
     * @var int
     */
    public $percentPrecision = 1;

    /**
     * Current tick number
     *
     * @var int
     */
    public $current = 0;

    /**
     * Maximum number of ticks
     *
     * @var int|mixed
     */
    public $total = 1;

    /**
     * Seconds elapsed
     *
     * @var int
     */
    public $elapsed = 0;

    /**
     * Current percentage complete
     *
     * @var int
     */
    public $percent = 0;

    /**
     * Estimated time until completion
     *
     * @var int
     */
    public $eta = 0;

    /**
     * Current rate
     *
     * @var int
     */
    public $rate = 0;


    public function __construct($total = 1, $format = "Progress: [:bar] - :current/:total - :percent% - Elapsed::elapseds - ETA::etas - Rate::rate/s", $stream = STDERR)
    {
        // Get the terminal width
        $this->width = exec("tput cols 2>/dev/null");
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
     * Increment by $amount ticks
     *
     * @param int $amount
     */
    public function tick($amount = 1)
    {
        $this->update($this->current + $amount);
    }

    /**
     * Set the increment and re-calculate data
     *
     * @param int $amount
     */
    public function update($amount)
    {
        $this->current = $amount;
        $drawElapse = microtime(true) - $this->timeSinceLastCall;

        if ($drawElapse > $this->throttle) {
            $this->elapsed = microtime(true) - $this->startTime;
            $this->percent = $this->current / $this->total * 100;
            $this->rate = $this->current / $this->elapsed;
            $this->eta = ($this->current) ? ($this->elapsed / $this->current * $this->total - $this->elapsed) : false;

            $this->drawBar();
        }
    }

    /**
     * Add a message on a newline before the progress bar
     */
    public function interupt($message)
    {
        fwrite($this->stream, self::MOVE_START);
        fwrite($this->stream, self::ERASE_LINE);
        fwrite($this->stream, $message . "\n");
        $this->drawBar();
    }

    /**
     * Does the actual drawing
     */
    private function drawBar()
    {
        $this->timeSinceLastCall = microtime(true);
        fwrite($this->stream, self::MOVE_START);

        $replace = array(
            $this->current,
            $this->total,
            $this->roundAndPad($this->elapsed, $this->secondPrecision),
            $this->roundAndPad($this->percent, $this->percentPrecision),
            $this->roundAndPad($this->eta, $this->secondPrecision),
            $this->roundAndPad($this->rate),
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
     *
     * @param $input
     * @param int $precision
     *
     * @return string
     */
    private function roundAndPad($input, $precision = 1)
    {
        return str_pad(number_format($input, $precision, '.', ''), 6, " ", STR_PAD_LEFT);
    }

    /**
     * Cleanup
     */
    public function end()
    {
        fwrite($this->stream, "\n" . self::SHOW_CURSOR);
    }

    public function __destruct()
    {
        $this->end();
    }

}
