<?php

function mkpasswd ($len = 64)
{
	$SNChars = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
	$SNCCount = strlen($SNChars);
	$s = '';
	while (strlen($s) < $len)
	{
		$s .= $SNChars[rand(0, $SNCCount-1)];
	}
	return $s;
}

function enhashPassword ($username, $password)
{
	return hash('sha256', $password.AUTH_SALT.$username);
}

function addDeferredCommand($command)
{
	if (!isset($_SESSION['deferred_commands']))
		$_SESSION['deferred_commands'] = Array();

	$_SESSION['deferred_commands'][] = $command;
}

function getDeferredCommands()
{
	$cmds = $_SESSION['deferred_commands'];
	unset($_SESSION['deferred_commands']);

	return $cmds;
}


function sanitizeOutput (&$array) 
{
    foreach ($array as &$value)
    {
        if (!is_array($value)) { $value = htmlspecialchars($value); }
        else { sanitizeOutput($value); }
    }
}