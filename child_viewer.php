<?php
//header("Content-Type: application/xhtml+xml");
session_start();

require_once("config.php");
require_once("utility/date.php");
require_once("utility/sql.php");

$db = new mysqli(db_lctn, db_user, db_pass, db_db);

$kids = Array();
$id = filter_input(INPUT_GET, 'c', FILTER_SANITIZE_NUMBER_INT);

$pts_stmt = $db->stmt_init();
$pts_stmt->prepare(SQL_SELECT_POINTS_FOR_CHILD);
$pts_stmt->bind_param("i", $id);

$pts_stmt->execute();
$pts_stmt->bind_result($x, $y, $s);	
//$j = 0;
while($pts_stmt->fetch()){
	$kid[] = array(x => $x, y => $y, s => $s);
	//$j++;
} 

$pts_stmt->close();


$pts_stmt = $db->prepare(SQL_SELECT_CHILD_DETAIL);
echo $db->error;
$pts_stmt->bind_param("i", $id);

$pts_stmt->execute();
$pts_stmt->bind_result($char, $segments, $age, $status, $fitness);	

$pts_stmt->fetch();

$pts_stmt->close();


//var_dump($kids);

echo <<< XML
<?xml version="1.0" encoding="UTF-8"?>
XML
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<script src="lib/jQuery/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="jquery.gfonted.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready( function() {
	var letter = <?=json_encode($kid)?>;
	
	$("#glyph").gfonted_draw_glyph(letter, 250);
	
});
</script>
</head>
<body>
<div id="glyph"></div>
<dl>
	<dt>UTF Codepoint</dt>
	<dd><?= $char ?></dd>
	
	<dt>Number of segments</dt>
	<dd><?= $segments ?></dd>
	
	<dt>Age</dt>
	<dd><?= format_duration($age) ?></dd>
	
	<dt>Status</dt>
	<dd><?= $status ?></dd>
	
	<dt>Fitness</dt>
	<dd><?= $fitness ?></dd>
</dl>
</body>
</html>
