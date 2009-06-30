<h1>Test Clamp</h1>
<pre>
<?php
require_once("utility/math.php");
echo ReflectionFunction::export('clamp');
?>
</pre>

<table>
<?
$max = 1;
$min = 0.01;

$func = new ReflectionFunction('clamp');

for($i = $min - .5; $i < $max + .5; $i += 0.01) {
	echo "<tr><td>";
	echo $i,"</td><td>";
	var_dump($func->invokeArgs(Array($i, $max, $min)));
	echo "</td></tr>";
}
?>
</table>

<h1>Test random_val</h1>
<pre>
<?php
echo ReflectionFunction::export('random_val');
?>
</pre>

<table>
<?
$max = -0.06;
$min = 0.06;
$func = new ReflectionFunction('random_val');

for($i = 0; $i < 50; $i++) {
	echo "<tr><td>";
	echo $i,"</td><td>";
	var_dump($func->invokeArgs(Array($max, $min)));
	echo "</td></tr>";
}

?>
</table>
