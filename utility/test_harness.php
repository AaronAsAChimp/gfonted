<?php
class Test_Harness_Class {
	protected $buffer = "";
	protected $reflector = null;
	
	protected $tests = Array();
	
	public function test_start($name, $clean = false) {
		ob_start();

		$this->tests[$name]['start'] = microtime(true);
		$this->tests[$name]['clean'] = $clean;
		$this->tests[$name]['success'] = true;

	}
	
	public function test_set_success($name, $fail) {
		$this->tests[$name]['success'] = $fail;
	}
	
	public function test_end($name) {
		$time = microtime(true) - $this->tests[$name]['start'];
		
		// grab anc clear output buffer
		$tmp = ob_get_contents();
		$lvl = (ob_get_level() > 7)? 7 : ob_get_level();
		ob_end_clean();
		
		if($this->tests[$name]['clean']) {
			$tmp =  "<pre>" . htmlspecialchars($tmp) . "</pre>";
		}

		return "<h" . $lvl . ">Test " . $name . "</h" . $lvl . ">"
		       . (($this->tests[$name]['success'])? "Success!" : "Fail!")
		       . "<br />Time:  " . $time
		       . $tmp;
	}
	
	public function __construct($class, $test_func) {
		// buffer output and collect it for later use.
		$this->test_start($class);
		
		$this->test_start("Reflection", true);
		// create the reflector
		$this->reflector = new ReflectionClass($class);
		Reflection::export($this->reflector);
		
		echo $this->test_end("Reflection");
		
		
		// run test function
		$this->test_set_success($class, $test_func($this, $this->reflector));
		
		$this->buffer = $this->test_end($class);
		//var_dump($this->tests);
		//$this->buffer = $this->tests[$class]['buffer'];
	}
	
	public function __toString(){
		return $this->buffer;
	}
}

function test_harness_class_test_callback(Test_Harness_Class $harness, ReflectionClass $mirror) {
	return true;
}

?>
