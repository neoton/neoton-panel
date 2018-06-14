<?php

function processAddition()
{/*$endpoint_name,
					  $endpoint_ip,
					  $endpoint_port,
					  $endpoint_mount,
					  $endpoint_password,
					  $endpoint_playlist,
					  $endpoint_schedule*/

	$dataCorrect = true;

	$endpoint_schedule = (int)$_POST['endpoint-schedule'];
	if (!scheduleExists($endpoint_schedule))
	{
		$dataCorrect = false;
		addFurtherMessage(_('This schedule does not exist'));
	}

	$endpoint_name = trim($_POST['endpoint-name']);
	if (empty($endpoint_name) || mb_strlen($endpoint_name, 'UTF-8') > 64)
	{
		$dataCorrect = false;
		addFurtherMessage(_('Bad endpoint name'));
	}

	$endpoint_ip = trim($_POST['endpoint-ip']);
	if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $endpoint_ip) !== 1)
	{
		$dataCorrect = false;
		addFurtherMessage(_('Bad endpoint IP address'));
	}

	$endpoint_mount = trim($_POST['endpoint-mount']);
	if (preg_match('/^[a-zA-Z0-9\-\_\.]{1,15}$/', $endpoint_mount) !== 1)
	{
		$dataCorrect = false;
		addFurtherMessage(_('Bad mount point name'));
	}

	$endpoint_password = trim($_POST['endpoint-password']);
	if (empty($endpoint_password) || mb_strlen($endpoint_password, 'UTF-8') > 128)
	{
		$dataCorrect = false;
		addFurtherMessage(_('Password cannot be empty or longer than 128 symbols'));
	}

	$endpoint_port = (int)$_POST['endpoint-port'];
	if ($endpoint_port <= 0 || $endpoint_port > 65535)
	{
		$dataCorrect = false;
		addFurtherMessage(_('Bad inbound port'));
	}

	if ($dataCorrect)
	{
		$eid = addEndpoint($endpoint_name,
					$endpoint_ip,
					$endpoint_port,
					$endpoint_mount,
					$endpoint_password,
					$endpoint_schedule
				);

		saveLiquidsoapScripts($eid);
		addDeferredCommand('cs_reloadEndpoints');
	}

	header ('HTTP/1.1 302 Added');
	header ('Location: /endpoints');
	die();
}


function processRemoval()
{
	$endpoint_id = (int)$_GET['endpoint-id'];
	if (!endpointExists($endpoint_id))
	{
		$dataCorrect = false;
		addFurtherMessage(_('This endpoint does not exist'));
	}

	removeEndpoint($endpoint_id);
	addDeferredCommand('cs_reloadEndpoints');

	// TODO: remove endpoint's liquidsoap script
	header ('HTTP/1.1 302 Removed');
	header ('Location: /endpoints');
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


$content['title'] = _('Endpoints');
$content['description'] = _('Manage endpoints: add, edit, remove, connect/disconnect broadcasting endpoints');
$content['endpoints'] = getEndpoints();
$content['playlists'] = getPlaylists();
$content['schedule'] = getSchedules();

display('endpoints', $content);