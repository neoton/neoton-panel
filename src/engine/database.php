<?php
require_once ('engine/pgsql.php');

/* Checkers: check object existnece */
function playlistExists($playlist_id)
{
	$res = sqlQuery('SELECT COUNT(*) as cnt FROM playlist WHERE playlist_id = ?', $playlist_id)->fetch();
	return ((int)$res['cnt'] > 0);
}

function endpointExists($endpoint_id)
{
	$res = sqlQuery('SELECT COUNT(*) as cnt FROM endpoint WHERE endpoint_id = ?', $endpoint_id)->fetch();
	return ((int)$res['cnt'] > 0);
}

function trackExists($track_id)
{
	$res = sqlQuery('SELECT COUNT(*) as cnt FROM track WHERE track_id = ?', $track_id)->fetch();
	return ((int)$res['cnt'] > 0);
}

function playlistTrackExists($playlist_id, $track_id)
{
	$res = sqlQuery('SELECT COUNT(*) as cnt FROM playlist_tracks WHERE playlist_id = ? AND track_id = ?', $playlist_id, $track_id)->fetch();
	return ((int)$res['cnt'] > 0);
}

function scheduleExists($schedule_id)
{
	$res = sqlQuery('SELECT COUNT(*) as cnt FROM schedule WHERE schedule_id = ?', $schedule_id)->fetch();
	return ((int)$res['cnt'] > 0);
}

function sttExists($stt_id)
{
	$res = sqlQuery('SELECT COUNT(*) as cnt FROM schedule_timetable WHERE stt_id = ?', $stt_id)->fetch();
	return ((int)$res['cnt'] > 0);
}

/* Fetchers: get the info from the database */

function getEndpoints()
{
	return sqlQuery('select * from endpoint inner join schedule on endpoint.endpoint_schedule = schedule.schedule_id order by endpoint_id asc')->fetchAll();
}

function getEndpointById($endpoint_id)
{
	return sqlQuery('select * from endpoint where endpoint_id = ? order by endpoint_id asc', $endpoint_id)->fetch();
}

// *** Playlists

function getPlaylists()
{
	return sqlQuery('select * from playlist')->fetchAll();
}

function getPlaylistTracks ($playlist_id)
{
	return sqlQuery('select * from playlist_tracks inner join track on playlist_tracks.track_id = track.track_id where playlist_id = ?', $playlist_id)->fetchAll();
}

function getPlaylistsForSchedule ($schedule_id)
{
	return sqlQuery('select distinct stt_playlist_id as playlist_id from schedule_timetable where stt_schedule_id = ?', $schedule_id)->fetchAll();
}

// *** Schedules

function getSchedules()
{
	return sqlQuery('select * from schedule')->fetchAll();
}

function getScheduleTimetable ($schedule_id, $isForLiquidsoap = false)
{
	if ($isForLiquidsoap)
		$criteria = 'to_char(stt_time_start, \'HH24hMImSSs\') as stt_time_start, to_char(stt_time_end, \'HH24hMImSSs\') as stt_time_end, stt_playlist_id';
	else
		$criteria = '*, playlist_name as stt_playlist_name';

	return sqlQuery('SELECT '.$criteria.' from schedule_timetable inner join playlist on schedule_timetable.stt_playlist_id = playlist.playlist_id where stt_schedule_id = ? order by stt_time_start asc', $schedule_id)->fetchAll();
}

// *** Accounts
function getUserInfo ($acct_id)
{
	return sqlQuery('select * from account where acct_id = ?', $acct_id);
}

function checkUserAuth($acct_login, $acct_password)
{
	$res = sqlQuery('select count(*) as cnt, acct_id from account where acct_login = ? and acct_password = ? group by acct_id', $acct_login, $acct_password)->fetch();

	return ($res['cnt'] === 1) ? $res['acct_id'] : false;
}

// *** Tracks (library)
function getTracks ()
{
	return sqlQuery('select * from track')->fetchAll();
}

function getTrackById($track_id)
{
	return sqlQuery('select * from track where track_id = ?', $track_id)->fetch();
}


/* Editors: edit the entries in the database */

// *** Endpoints

function addEndpoint ($endpoint_name,
					  $endpoint_ip,
					  $endpoint_port,
					  $endpoint_mount,
					  $endpoint_password,
					  $endpoint_schedule)
{
	sqlQuery('INSERT INTO endpoint (endpoint_name, endpoint_ip, endpoint_port, endpoint_mount, endpoint_password, endpoint_schedule) VALUES (?, ?, ?, ?, ?, ?)', 
			$endpoint_name,
			$endpoint_ip,
			$endpoint_port,
			$endpoint_mount,
			$endpoint_password,
			$endpoint_schedule);

	return getLastInsertId('seq_endpoint_id');
}

function editEndpoint ($endpoint_id, 

					  $endpoint_name,
					  $endpoint_ip,
					  $endpoint_port,
					  $endpoint_mount,
					  $endpoint_password)
{
	return sqlQuery('UPDATE endpoint SET endpoint_name = ?, endpoint_ip = ?, endpoint_port = ?, endpoint_mount = ?, endpoint_password = ? WHERE endpoint_id = ?', 
			$endpoint_name,
			$endpoint_ip,
			$endpoint_port,
			$endpoint_mount,
			$endpoint_password,
			$endpoint_id);
}

function setEndpointSchedule ($endpoint_id, $schedule_id)
{
	return sqlQuery('UPDATE endpoint SET endpoint_schedule = ? WHERE endpoint_id = ?', 
			$schedule_id,
			$endpoint_id);
}


function removeEndpoint ($endpoint_id)
{
	return sqlQuery('DELETE FROM endpoint WHERE endpoint_id = ?', 
			$endpoint_id);
}


// *** Playlists

function addPlaylist($playlist_name)
{
	sqlQuery('INSERT INTO playlist (playlist_name) VALUES (?)', 
			$playlist_name);

	return getLastInsertId('seq_playlist_id');
}

function addPlaylistTrack ($playlist_id, $track_id)
{
	return sqlQuery('INSERT INTO playlist_tracks (playlist_id, track_id) VALUES (?, ?)', 
			$playlist_id, $track_id);
}

function removePlaylistTrack ($playlist_id, $track_id)
{
	return sqlQuery('DELETE FROM playlist_tracks WHERE playlist_id = ? and track_id = ?', 
			$playlist_id, $track_id);
}

function removePlaylist ($playlist_id)
{
	return sqlQuery('DELETE FROM playlist WHERE playlist_id = ?', 
			$playlist_id);
}

// *** Schedules

function addSchedule($schedule_name)
{
	sqlQuery('INSERT INTO schedule (schedule_name) VALUES (?)', 
			$schedule_name);

	return getLastInsertId('seq_schedule_id');
}

function setScheduleDow ($schedule_id, $schedule_active_dow)
{
	return sqlQuery('UPDATE schedule SET schedule_active_dow = ? WHERE schedule_id = ?', 
			$schedule_active_dow,
			$schedule_id);
}

function addScheduleTime ($schedule_id, $time_start, $time_end, $playlist_id)
{
	sqlQuery('INSERT INTO schedule_timetable (stt_schedule_id, stt_time_start, stt_time_end, stt_playlist_id) VALUES (?, ?, ?, ?)', 
			$schedule_id, $time_start, $time_end, $playlist_id);

	return getLastInsertId('seq_stt_id');
}

function removeScheduleTime ($stt_id)
{
	return sqlQuery('DELETE FROM schedule_timetable WHERE stt_id = ?', 
			$stt_id);
}

function removeSchedule ($schedule_id)
{
	return sqlQuery('DELETE FROM schedule WHERE schedule_id = ?', 
			$schedule_id);
}

// *** Tracks

function addTrack ($track_filename, $track_artist, $track_title, $track_owner)
{
	sqlQuery('INSERT INTO track (track_filename, track_artist, track_title, track_owner) VALUES (?, ?, ?, ?)', 
			$track_filename, $track_artist, $track_title, $track_owner);

	return getLastInsertId('seq_track_id');
}

function editTrack ($track_id, $track_artist, $track_title)
{
	return sqlQuery('UPDATE track SET track_artist = ?, track_title = ? WHERE track_id = ?', 
			$track_artist, $track_title, $track_id);
}

function removeTrack ($track_id)
{
	return sqlQuery('DELETE FROM track WHERE track_id = ?', 
			$track_id);
}

function searchTrack ($query)
{
	$query = mb_strtolower('%'.$query.'%', 'UTF-8');
	return sqlQuery('SELECT * FROM track WHERE lower(track_artist) like ? OR lower(track_title) like ?', $query, $query)->fetchAll();
}

// *** Accounts

function addUser ($acct_login, $acct_password, $acct_broadcast_password, $acct_role)
{
	return sqlQuery('INSERT INTO account (acct_login, acct_password, acct_broadcast_password, acct_role) VALUES (?, ?, ?, ?)', 
			$acct_login, $acct_password, $acct_broadcast_password, $acct_role);
}

function editUser ($acct_id, $acct_broadcast_password, $acct_role)
{
	return sqlQuery('UPDATE account SET acct_broadcast_password = ?, acct_role = ? WHERE acct_id = ?', 
			$acct_broadcast_password, $acct_role, $acct_id);
}

function setUserPassword ($acct_id, $acct_password)
{
	return sqlQuery('UPDATE account SET acct_password = ? WHERE acct_id = ?', 
			$acct_password, $acct_id);
}

function setUserNonce ($acct_id, $acct_nonce)
{
	return sqlQuery('UPDATE account SET acct_nonce = ? WHERE acct_id = ?', 
			$acct_nonce, $acct_id);
}

function removeUser ($acct_id)
{
	return sqlQuery('DELETE FROM account WHERE acct_id = ?', 
			$acct_id);
}