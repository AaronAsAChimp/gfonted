<?php
/**
 * clamp
 * make sure a value is between $max and $min
 * @param $val the value to clamp
 * @param $max the maximum
 * @param $min the minimum
 */
function clamp($val, $max, $min) {
	$val = (($val > $max)? $max : $val);
	$val = (($val < $min)? $min : $val);
	return $val;
}

/**
 * random_val
 * generate a random number between $max and $min
 * @param $max the maximum
 * @param $min the minimum
 */
function random_val($max, $min) {
	return mt_rand(($min * 10000), $max * 10000) / 10000;
}
?>
