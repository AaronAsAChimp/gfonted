<?php
$test = filter_input(INPUT_POST, 'test', FILTER_SANITIZE_STRING);
session_start();

require_once("../config.php");
require_once("sql.php");
require_once("mutation_engine.php");

$db = new mysqli(db_lctn, db_user, db_pass, db_db);
$me = new Mutation_Engine();

$_SESSION['test']  = $test;

// basic fitness check
for($i = 0; $i < GFE_NUM_LETTERS; $i++) {
	$correct = $test[$i] == $_SESSION['target'][$i];
	$id = $_SESSION['child_ids'][$i];
	$template_id = $_SESSION['template_ids'][$i];
	
	$stmt = $db->prepare(SQL_INSERT_CORRECT);
	$stmt->bind_param("is",$id, $correct);
	$stmt->execute();
	$stmt->close();
	
	$stmt = $db->prepare(SQL_UPDATE_FITNESS);
	$stmt->bind_param("ii",$id, $id);
	$stmt->execute();
	$stmt->close();

	$me->euthanize($template_id);
	$me->conceive($template_id);
	
	//if($correct) {
	//}
}

header("Location:../birth.php");
?>
