<?php

function processAddition()
{

	$checkIsOk = true;

	$track_artist = trim($_POST['track-artist']);
	if (empty($track_artist) || mb_strlen($track_artist, 'UTF-8') > 64)
	{
		$checkIsOk = false;
		addFurtherMessage(_('Artist name can not be empty or longer than 64 characters'));
	}
		

	$track_title = trim($_POST['track-title']);
	if (empty($track_title) || mb_strlen($track_title, 'UTF-8') > 64)
	{
		$checkIsOk = false;
		addFurtherMessage(_('Title can not be empty or longer than 64 characters'));
	}


	$track_filename =  mkpasswd(32).'.mp3';
	if (!move_uploaded_file($_FILES['track-file']['tmp_name'], AUDIO_STORAGE.'/'.$track_filename))
	{
	    $checkIsOk = false;
	    addFurtherMessage(_('Could not save the file'));
	}

	if ($checkIsOk)
		addTrack($track_filename, $track_artist, $track_title, $_SESSION['user_id']);
	
	header ('HTTP/1.1 302 Added');
	header ('Location: /library');
	die();
}

function processRemoval()
{

	$track_id = (int)$_GET['track-id'];

	if (trackExists($track_id))
	{
		$track = getTrackById($track_id);
		unlink($track['track_filename']);
		removeTrack($track_id);
	}
	else
		addFurtherMessage(_('This track does not exist'));

	header ('HTTP/1.1 302 Removed');
	header ('Location: /library');
	die();
}

function processSearch()
{
	$query = $_GET['q'];

	$content = Array(
		'tracks' => searchTrack ($query),
		'query' => htmlspecialchars($query),
		'title' => _('Search for a track'),
		'description' => _('Search tracks in the media library')
	);

	display ('library.search', $content);
}

switch ($route[1])
{
	case 'add' :
		processAddition();
		break;

	case 'remove' :
		processRemoval();
		break;

	case 'search' :
		processSearch();
		break;
}


$content['title'] = _('Library');
$content['description'] = _('Media library management: add, remove, edit metadata of the audio tracks');
$content['tracks'] = getTracks();

display('library', $content);