<?php
require_once("utility/unicode.php");
require_once("utility/test_harness.php");

function stuff (Test_Harness_Class $harness, ReflectionClass $mirror) {
	
	//
	// test range_ex with Basic Latin
	//
	$harness->test_start("range_ex: Basic Latin", true);

	preg_match(Unicode_Range_Iterator::range_ex, "0000..007F; Basic Latin", $matches);
	
	if($matches[1] != "0000") {
		$harness->test_set_success("range_ex: Basic Latin", false);
		echo "Not parsing first value in range.\n"; 
	} else if($matches[2] != "007F") {
		$harness->test_set_success("range_ex: Basic Latin", false);
		echo "Not parsing second value in range.\n";
	} else if($matches[3] != "Basic Latin") {
		$harness->test_set_success("range_ex: Basic Latin", false);
		echo "Not parsing range label.\n";
	}
	
	var_dump($matches);
	
	echo $harness->test_end("range_ex: Basic Latin");
	
	
	//
	// test range_ex with multibyte range
	//
	$harness->test_start("range_ex: Aegean Numbers", true);

	preg_match(Unicode_Range_Iterator::range_ex, "10100..1013F; Aegean Numbers", $matches);
	
	if($matches[1] != "10100") {
		$harness->test_set_success("range_ex: Aegean Numbers", false);
		echo "Not parsing first value in range.\n"; 
	} else if($matches[2] != "1013F") {
		$harness->test_set_success("range_ex: Aegean Numbers", false);
		echo "Not parsing second value in range.\n";
	} else if($matches[3] != "Aegean Numbers") {
		$harness->test_set_success("range_ex: Aegean Numbers", false);
		echo "Not parsing range label.\n";
	}
	
	var_dump($matches);
	
	echo $harness->test_end("range_ex: Aegean Numbers");
	
	//
	// test full loop with Latin-1 Supplement
	//
	$harness->test_start("full range loop: Latin-1 Supplement");
	
	$test_instance = $mirror->newInstanceArgs(Array("0080..00FF; Latin-1 Supplement"));
	?>
	<table>
		<tr><td>codepoint</td><td>char</td></tr>
	<?
	
	foreach($test_instance as $cp => $char) {
		$codepoint = "U+" . dechex($cp);
		$character = htmlentities($char);
		echo "<tr><td>$codepoint</td><td>$character</td></tr>";
		
	}
	
	?>
	</table>
	<?
	echo $harness->test_end("full range loop: Latin-1 Supplement");
	
	return true;
}

echo new Test_Harness_Class('Unicode_Range_Iterator', stuff);

?>

