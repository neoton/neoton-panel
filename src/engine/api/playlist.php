<?php

function processTrackAddition()
{
	if (playlistExists((int)$_POST['playlist-id']) && trackExists((int)$_POST['track-id']))
	{
		if (playlistTrackExists((int)$_POST['playlist-id'], (int)$_POST['track-id']))
			json_respond(1, "This combination does already exist");
		else
		{
			addPlaylistTrack((int)$_POST['playlist-id'], (int)$_POST['track-id']);

			savePlaylist((int)$_POST['playlist-id']);
			json_respond(0, "OK");
		}
	}
	else
		json_respond(2, "No such playlist or track");
}

function processTrackRemoval()
{
	if (playlistTrackExists((int)$_POST['playlist-id'], (int)$_POST['track-id']))
	{
		removePlaylistTrack((int)$_POST['playlist-id'], (int)$_POST['track-id']);
		savePlaylist((int)$_POST['playlist-id']);
		
		json_respond(0, "OK");
	}
	else
		json_respond(1, "No such combination of playlist and track");
}

array_shift($route); // remove /playlist/
switch ($route[0])
{
	case 'addTrack' :
		processTrackAddition();
		break;

	case 'removeTrack' :
		processTrackRemoval();
		break;

	default:
		json_respond (-1, 'Bad API request');
}