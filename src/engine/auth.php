<?php

if ($route[1] == 'logout')
{
	session_destroy();
	header ('HTTP/1.1 302 Logged off');
	header ('Location: /auth');
	die();
}

if ($route[1] == 'go')
{
	$login = strtolower($_POST['username']);
	$password = enhashPassword($login, $_POST['password']);

	$userId = checkUserAuth($login, $password);
	if ($userId !== false)
	{
		$nonce = mkpasswd(64);
		setUserNonce($userId, $nonce);

		$_SESSION['user_nonce'] = $nonce;
		$_SESSION['user_id'] = $userId;
		$_SESSION['user_login'] = $login;
		header ('HTTP/1.1 302 Auth OK');
		header ('Location: /');
		die();
	}
	else
	{
		displayAuth();
	}
}
else
	displayAuth();