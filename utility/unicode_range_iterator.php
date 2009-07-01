<?php
/**
 * Iterate through unicode range.
 * Specifically designed for Blocks.txt from the unicode specification.
 * 
 */
class Unicode_Range_Iterator implements Iterator {
	protected $origin = "0000..007F; Basic Latin";
	protected $label = "Basic Latin";
	protected $start = 0x0000;
	protected $end = 0x007F;
	
	protected $current = 0x0000;
	
	const range_ex = "/([0-9A-F]*)\.\.([0-9A-F]*);\s*(.*)/";
	
	public function __construct($string) {
		preg_match(self::range_ex, $string, $matches);
		$this->origin = $string;
		$this->current = $this->start = hexdec($matches[1]);
		$this->end = hexdec($matches[2]);
		$this->label = $matches[3];
	}
	
	public function __toString() {
		return "$this->label\n$this->start..$this->end => $this->current";
	}
	
	function rewind() {
		$this->current = $this->start;
	}

	function current() {
		return chr($this->current);
	}

	function key() {
		return $this->current;
	}

	function next() {
		$this->current++;
	}

	function valid() {
		return ($this->current <= $this->end);
	}
}
?>
