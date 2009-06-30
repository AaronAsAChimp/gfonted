<?php
//header("Content-Type: application/xhtml+xml");
session_start();

require_once("config.php");

require_once(facebook_api_files);

echo <<< XML
<?xml version="1.0" encoding="UTF-8"?>
XML
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<script src="<?= jquery_files ?>" type="text/javascript"></script>
</head>
<body>

<form method="POST" action="utility/populate_letter.php">
	<label for="utf_codepoint"><?= GFE_STRINGS_UTF_CODEPOINT ?></label><input id="utf_codepoint" name="utf_codepoint" type="text" maxlength="1" value="A" /><br />
	<label for="segments"><?= GFE_STRINGS_NUMBER_SEGMENTS ?></label><input id="segments" name="segments" type="text" maxlength="3" value="10" /><br />
	<label for="max_children"><?= GFE_STRINGS_MAXIMUM_CHILDREN ?></label><input id="max_children" name="max_children" type="text" maxlength="3" value="26" /><br />
	<input type="submit" />
</form>

</body>
</html>
