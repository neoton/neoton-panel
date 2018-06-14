<?php

array_shift($route); // remove /api/
switch ($route[0])
{
	case 'playlist' :
		require_once ('engine/api/playlist.php'); 
		break;

	case 'endpoint' :
		require_once ('engine/api/endpoint.php'); 
		break;

	case 'schedule' :
		require_once ('engine/api/schedule.php'); 
		break;

	case 'library' :
		require_once ('engine/api/library.php'); 
		break;

	default :
		json_respond (-1, 'Bad API request');
		break;
}