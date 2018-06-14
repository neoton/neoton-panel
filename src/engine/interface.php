<?php

require_once ('menu.php');

function addFurtherMessage ($message, $type = 'info')
{
	$_SESSION['sys_message'] = $message;
	$_SESSION['sys_message_type'] = $type;
}

function json_respond ($status, $payload = Array())
{
	header('Content-Type: application/json');
	die(json_encode(Array('status' => (int)$status, 'payload' => $payload)));
}

function display ($template, $content = Array())
{
	sanitizeOutput($content);

	global $route;
	$content['menu'] = getMenu();

	if (!empty($content['menu'][$route[0]]))
		$content['menu'][$route[0]]['active'] = true;

	if (!empty($_SESSION['sys_message']))
	{
		$content['sys_message']['text'] = $_SESSION['sys_message'];
		$content['sys_message']['type'] = $_SESSION['sys_message_type'];

		unset($_SESSION['sys_message']);
		unset($_SESSION['sys_message_type']);
	}

	if (isset($_SESSION['deferred_commands']))
	{
		$content['deferred_commands'] = getDeferredCommands();
	}

	$content['current_timestamp'] = time();
	$content['js_lang'] = getLangJson();

	require_once ('engine/template/header.tpl');
	require_once ('engine/template/'.$template.'.tpl');
	require_once ('engine/template/footer.tpl');
	die();
}

function display404()
{
	$content = Array();
	$content['title'] = _('404: Not Found');
	$content['description'] = _('The path you are trying to access is invalid.');
	display('404', $content);
}


function displayAuth()
{
	$content = Array(
		'text_username' => _('username'),
		'text_password' => _('password'),
		'text_submit'   => _('go'),
		'title'         => _('Neoton: broadcast management')
	);
	
	require_once('engine/template/auth.tpl');
}

function displayError($message)
{
	header('HTTP/1.1 500 Internal Server Error');
	$content = Array();
	$content['title'] = _('System error');
	$content['error_message'] = $message;
	display('error', $content);
}