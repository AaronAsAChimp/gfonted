<?php
require_once("../config.php");
require_once("sql.php");
// make a child that conforms to the config parameters
function make_child_points($seg, $child) {
	$values = "";
	do {
		$x = mt_rand(GFE_MIN_X * 1000, GFE_MAX_X * 1000) / 1000;
		$y = mt_rand(GFE_MIN_Y * 1000, GFE_MAX_Y * 1000) / 1000;
		$s = mt_rand(GFE_MIN_SIZE * 1000, GFE_MAX_SIZE * 1000) / 1000;
		
		$values .= "(\"" . $child . "\", GeomFromWKB(Point(\"" . $x . "\", \"" . $y . "\")), \"" . $s . "\", \"". ($seg - 1) ."\"),";
		
		$seg--;
	} while ($seg > 0);
	
	return rtrim($values, ",");
}

$utf_codepoint = filter_input(INPUT_POST, 'utf_codepoint', FILTER_SANITIZE_STRING);
$code_range = filter_input(INPUT_POST, 'code_range', FILTER_SANITIZE_STRING);
$segments = filter_input(INPUT_POST, 'segments', FILTER_SANITIZE_NUMBER_INT);
$max_children = filter_input(INPUT_POST, 'max_children', FILTER_SANITIZE_NUMBER_INT);

$con = mysql_connect(db_lctn, db_user, db_pass);
mysql_select_db(db_db);

mysql_query("insert into Template (utf_codepoint, segments, max_children) values (\"$utf_codepoint\", \"$segments\", \"$max_children\")");
$template_id  = mysql_insert_id();

//var_dump($template_id);
$db = new mysqli(db_lctn, db_user, db_pass, db_db);
$ch_prep = $db->prepare(SQL_INSERT_CHILD);
echo $db->error;
$ch_prep->bind_param("i", $template_id);

for($i = 0; $i < $max_children; $i++) {
	//echo "insert into Children (template_id) values (\"$template_id\")";
	//mysql_query("insert into Children (template_id) values (\"$template_id\")");
	
	$ch_prep->execute();
	$child_id = $ch_prep->insert_id;

	mysql_query("insert into Points (children_id, pt, sz, o) values ". make_child_points($segments, $child_id));
	
	$db->query(sprintf(SQL_UPDATE_CHILD_BIRTH, $child_id));
}

$ch_prep->close();

header("Location: ../new_glyph.php");
?>
