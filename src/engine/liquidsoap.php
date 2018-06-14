<?php

function makeLiquidsoapScript ($endpoint, $timetable, $playlists)
{
	ob_start();

	$liq = array(
		'endpoint' => $endpoint,
		'timetable' => $timetable,
		'playlists' => $playlists
	);

	require 'engine/liquidsoap/template.liq';

	$script = ob_get_contents();
	ob_end_clean();
	return $script;
}

function saveLiquidsoapScripts ($endpoint_id = -1)
{

	$endpoints = Array();
	if ($endpoint_id == -1)
		$endpoints = getEndpoints();
	else
		$endpoints[] = getEndpointById($endpoint_id);


	foreach ($endpoints as $ep)
	{
		$timetable = getScheduleTimetable($ep['endpoint_schedule'], true);
		$playlists = getPlaylistsForSchedule($ep['endpoint_schedule']);
		file_put_contents(LS_SCRIPT_DIR.'/endpoint_'.$ep['endpoint_id'].'.liq',
			makeLiquidsoapScript($ep, $timetable, $playlists));
	}
}

function savePlaylist ($playlist_id)
{
	$playlistTracks = getPlaylistTracks($playlist_id);
	$tracklist = '';

	foreach ($playlistTracks as $pls)
	{
		$tracklist .= AUDIO_STORAGE.'/'.$pls['track_filename']."\n";
	}

	file_put_contents(LS_PLAYLIST_DIR.'/playlist_'.$playlist_id.'.txt', $tracklist);
}