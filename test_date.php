<h1>Test Format Duration</h1>
<pre>
<?php
require_once("utility/date.php");
echo ReflectionFunction::export('format_duration');
?>
</pre>

<table>
<?
$max = 3601;
$min = 1;

$func = new ReflectionFunction('format_duration');

for($i = $min; $i < $max; $i++) {
	echo "<tr><td>";
	echo $i,"</td><td>";
	var_dump($func->invokeArgs(Array($i)));
	echo "</td></tr>";
}
?>
</table>

