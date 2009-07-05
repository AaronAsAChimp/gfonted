<?php
//header("Content-Type: application/xhtml+xml");
session_start();

require_once("config.php");
require_once("utility/sql.php");
/*
require_once(facebook_api_files);

$db = new mysqli(db_lctn, db_user, db_pass, db_db);

$count_res = $db->query(SQL_COUNT_CHILDREN);
$count = $count_res->fetch_row();
$count = $count[0];

$kids = Array();
$random = 0;
$id = 0;

$_SESSION['target'] = "";
$_SESSION['child_ids'] = Array();
$_SESSION['template_ids'] = Array();

for($i = 0; $i< GFE_NUM_LETTERS; $i++) {
	
	$kid_stmt = $db->stmt_init();
	$kid_stmt->prepare(SQL_SELECT_RANDOM_CHILDREN);
	$kid_stmt->bind_param("i", $random);

	$random = mt_rand(0, $count - 1);
	$kid_stmt->execute();
	$kid_stmt->bind_result($letter, $tmpl_id, $id);
	$kid_stmt->fetch();
	$kid_stmt->close();
	
	$_SESSION['target'] .= $letter;
	$_SESSION['child_ids'][] = $id;
	$_SESSION['template_ids'][] = $tmpl_id;
	
	$pts_stmt = $db->stmt_init();
	$pts_stmt->prepare(SQL_SELECT_POINTS_FOR_CHILD);
	$pts_stmt->bind_param("i", $id);
	
	$pts_stmt->execute();
	$pts_stmt->bind_result($x, $y, $s);	
	//$j = 0;
	while($pts_stmt->fetch()){
		$kids[$i][] = array(x => $x, y => $y, s => $s);
		//$j++;
	} 
	
	$pts_stmt->close();
}

//var_dump($kids);
*/
echo <<< XML
<?xml version="1.0" encoding="UTF-8"?>
XML
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<style>
body {
	font-family: Helvetica, Nimbus Sans, sans-serif;
}

.input {
	font-size: <?= GFE_PX_SIZE ?>px;
	width: <?=GFE_NUM_LETTERS?>em;
}

#page {
	width: <?= (GFE_PX_SIZE * (GFE_NUM_LETTERS + 1)) ?>px;
	text-align: justify;
	font-size: 15px;
	margin: 10px auto;
}

#input {
	width: <?= (GFE_PX_SIZE * (GFE_NUM_LETTERS)) + 6 ?>px;
	text-align: right;
	margin: 0 auto;
	padding: 20px 0;
}
</style>
<script src="<?= jquery_files ?>" type="text/javascript"></script>
<script src="jquery.gfonted.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready( function() {
	var letters = <?=json_encode($kids)?>;
	
	//$("#glyph").gfonted_draw_glyph(letters.x, 200);
	
	$("#text").gfonted_draw_string(letters, <?= GFE_PX_SIZE ?>);
});
</script>
</head>
<body>
<div id="page">
<!--<div id="glyph"></div>-->

<p>
<?= GFE_STRINGS_CHARACTER_SEQUENCE_WAS, $_SESSION['target'] ?><br />
<?= GFE_STRINGS_CHARACTER_SEQUENCE_WASNT, $_SESSION['test'] ?><br />
</p>

<p>
<a href="./"><?= GFE_STRINGS_TRY_AGAIN ?></a>
</p>

<h2><?= GFE_STRINGS_CHILDREN_TESTED ?></h2>
<ul>
<?
foreach($_SESSION['child_ids'] as $kid) {?>
	<li><a href="child_viewer.php?c=<?= $kid ?>"><?= $kid ?></a></li>
<?} 
?>
</ul>

<h2><?= GFE_STRINGS_PARENTS ?></h2>
<ul>
<? foreach($_SESSION['parent_ids'] as $tmpl) {?>

	<li><ul>
	<?
	foreach($tmpl as $parent) {?>
		<li><a href="child_viewer.php?c=<?= $parent ?>"><?= $parent ?></a></li>
	<? }
	?>
	</ul></li>

<?}
?>
</ul>

<h2><?= GFE_STRINGS_OFFSPRING ?></h2>
<ul>
<? foreach($_SESSION['offspring_ids'] as $tmpl) {?>

	<li><ul>
	<?
	foreach($tmpl as $baby) {?>
		<li><a href="child_viewer.php?c=<?= $baby ?>"><?= $baby ?></a></li>
	<? }
	?>
	</ul></li>

<?}
?>
</ul>

<pre>
<?/*
var_dump($_SESSION); 
*/
?>
</pre>

</div>

</body>
</html>
