<?php
	// copy this file to config.php and add your
	//    database username and password
	
	require_once("strings.en-us.php");
	
	// database config
	define('db_user', '*****');
	define('db_pass', '*****');
	define('db_lctn', 'localhost');
	define('db_db', 'gfonted');
	
	// facebook config
	define('facebook_api_key', '*****');
	define('facebook_api_secret', '*****');
	
	define('facebook_api_files', 'lib/facebook/facebook.php');
	
	// jQuery config
	define('jquery_files', 'lib/jQuery/jquery-1.3.2.min.js');
	
	// constants
	define("GFE_MUTATION_JITTER", 0.06);

	define("GFE_MAX_SIZE", 0.1);
	define("GFE_MIN_SIZE", 0.01);

	define("GFE_MAX_X",  1 - GFE_MAX_SIZE);
	define("GFE_MIN_X",  GFE_MAX_SIZE);

	define("GFE_MAX_Y",  1 - GFE_MAX_SIZE);
	define("GFE_MIN_Y",  GFE_MAX_SIZE);
	
	define("GFE_NUM_LETTERS", 5);
	define("GFE_PX_SIZE", 75);
	
	define("GFE_NUM_PARENTS", 5);
	define("GFE_DEATH_RATE", 5);
?>
