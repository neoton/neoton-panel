<?php

function processAddition()
{
	$pls_name = trim($_POST['playlist-name']);

	if (!empty($pls_name))
		addPlaylist($pls_name);
	else
		addFurtherMessage(_('Playlist name can not be empty'));

	header ('HTTP/1.1 302 Added');
	header ('Location: /playlists');
	die();
}

function processRemoval()
{
	$playlist_id = (int)$_GET['playlist-id'];

	if (playlistExists($playlist_id))
		removePlaylist($playlist_id);
	else
		addFurtherMessage(_('This playlist does not exist'));

	
	header ('HTTP/1.1 302 Removed');
	header ('Location: /playlists');
	die();
}

switch ($route[1])
{
	case 'add' :
		processAddition();
		break;

	case 'remove' :
		processRemoval();
		break;
}

$content['title'] = _('Playlists');
$content['description'] = _('Playlists management: add, remove, edit playlists');
$content['playlists'] = getPlaylists();
$content['tracks'] = getTracks();

foreach ($content['playlists'] as $key => $sched)
{
	$content['playlists'][$key]['tracks'] = getPlaylistTracks($sched['playlist_id']);
}

display('playlists', $content);