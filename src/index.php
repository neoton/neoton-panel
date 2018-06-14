<?php

session_start();

// Notices are unneeded in this case
// error_reporting(E_ALL & ~E_NOTICE);

require_once ('engine/enconfig.php');
require_once ('engine/lang.php');
require_once ('engine/database.php');
require_once ('engine/interface.php');
require_once ('engine/functions.php');
require_once ('engine/liquidsoap.php');

$route = explode('/', $_GET['route']);

if (empty($_SESSION['user_id']) && $route[0] != 'auth')
{
	header ('HTTP/1.1 302 Redirect');
	header ('Location: /auth');
}

if (empty($route[0]))
{
	require_once ('engine/main.php');
	die();
}

switch ($route[0])
{
	case 'auth' :
		require_once ('engine/auth.php'); 
		break;

	case 'api' :
		require_once ('engine/api.php'); 
		break;
		
	case 'library' :
		require_once ('engine/library.php'); 
		break;
		
	case 'playlists' :
		require_once ('engine/playlists.php'); 
		break;

	case 'schedule' :
		require_once ('engine/schedule.php'); 
		break;

	case 'endpoints' :
		require_once ('engine/endpoints.php'); 
		break;
	
	default :
		display404();
		break;
}

