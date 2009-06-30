<?php 
/**
 * format a time duration
 * \param $seconds since the start date
 */
function format_duration($seconds) {

	$periods = array(
		array( 't' => 3155692600,	's' => ' centuries'),
		array( 't' => 315569260,	's' => ' decades'),
		array( 't' => 31556926,		's' => ' years'),
		array( 't' => 2629743,		's' => ' months'),
		array( 't' => 604800,		's' => ' weeks'),
		array( 't' => 86400,        's' => ' days'),
		array( 't' => 3600,         's' => 'h'),
		array( 't' => 60,           's' => 'm'),
		array( 't' =>1,             's' => 's'));

	$durations = "";


	foreach($periods as $mag => $period) {
		if ($seconds >= $period['t']) {
			$lastPeriod = (($mag - 1) < 0 ) ? 1 : $periods[$mag - 1]['t'];
			$durations .= floor(($seconds % $lastPeriod) / $period['t']) .  $period['s'] . " ";
		}
	}
   
	return $durations;

}

?>
