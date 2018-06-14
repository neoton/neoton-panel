<?php



function processEdit()
{
	/*$endpoint_name,
	$endpoint_ip,
	$endpoint_port,
	$endpoint_mount,
	$endpoint_password*/

	$endpoint_id = (int)$_POST['endpoint-id'];
	if (!endpointExists($endpoint_id))
		json_respond(1, 'No such endpoint');

	$endpoint_name = trim($_POST['endpoint-name']);
	if (empty($endpoint_name) || mb_strlen($endpoint_name, 'UTF-8') > 64)
		json_respond(2, 'Bad endpoint name');

	$endpoint_ip = trim($_POST['endpoint-ip']);
	if (preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $endpoint_ip) !== 1)
		json_respond(3, 'Bad endpoint IP address');

	$endpoint_mount = trim($_POST['endpoint-mount']);
	if (preg_match('/^[a-zA-Z0-9-_\.]{1,15}$/', $endpoint_mount) !== 1)
		json_respond(4, 'Bad endpoint mount');

	$endpoint_password = trim($_POST['endpoint-password']);
	if (empty($endpoint_password) || mb_strlen($endpoint_password, 'UTF-8') > 128)
		json_respond(5, 'Bad endpoint password');

	$endpoint_port = (int)$_POST['endpoint-port'];
	if ($endpoint_port <= 0 || $endpoint_port > 65535)
		json_respond(6, 'Bad endpoint port');

	editEndpoint($endpoint_id,
				$endpoint_name,
				$endpoint_ip,
				$endpoint_port,
				$endpoint_mount,
				$endpoint_password);

	saveLiquidsoapScripts($endpoint_id);

	json_respond(0, 'OK');
}

function processSetSchedule()
{
	$endpoint_id = (int)$_POST['endpoint-id'];
	if (!endpointExists($endpoint_id))
		json_respond(1, 'No such endpoint');

	$schedule_id = (int)$_POST['endpoint-schedule'];
	if (!scheduleExists($schedule_id))
		json_respond(2, 'No such schedule');


	setEndpointSchedule($endpoint_id, $schedule_id);

	saveLiquidsoapScripts($endpoint_id);
	
	json_respond(0, 'OK');
}


array_shift($route); // remove /endpoint/
switch ($route[0])
{
	case 'edit' :
		processEdit();
		break;

	case 'setSchedule' :
		processSetSchedule();
		break;

	default:
		json_respond (-1, 'Bad API request');
}