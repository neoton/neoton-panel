var
	controlConnection = null;

function cs_log (mesg, level)
{
	level = level || "debug";

	switch (level)
	{
		case "error":
			console.error(mesg);
			break;

		case "warn":
			console.warn(mesg);
			break;

		case "debug":
		default:
			console.debug(mesg);
			break;
	}
	
}

function cs_init(cs_settings)
{

	if (!('WebSocket' in window))
	{
		cs_error (199, LANG.no_websockets);
		return;
	}

	controlConnection = new WebSocket((cs_settings.secure ? 'wss' : 'ws')+'://'+cs_settings.server+':'+cs_settings.port);

	controlConnection.onmessage = function (ev) { cs_processCommand(ev.data); };
	controlConnection.onclose = function (ev) { cs_onClose(ev.code, (ev.reason) ? ev.reason : LANG.websocket_disconnected); };
	controlConnection.onopen = function (ev) { cs_send("CONTROL"); };
}

function cs_onClose(code, reason)
{
	switch (code)
	{
		case 1000:
		case 1001:

			break;

		default:
			cs_error(code, reason);
	}
}

function cs_send (command)
{
	if (controlConnection)
	{
		cs_log("CS_SEND: "+command);

		controlConnection.send(command);
		return true;
	}
		else
	{
		cs_error(103, LANG.websocket_not_connected);
		return false;
	}
}

function cs_error(code, mesg)
{
	showToast(LANG.error+" #"+code+": "+mesg, "error");
}

function cs_processCommand(commandLine)
{
	cs_log("CS_RECV: "+commandLine);
	
	var commands = commandLine.split (' '),
		fulltext = commandLine.substr(commandLine.indexOf('#')+1),
		mainCmd = commands[0];

	switch (mainCmd)
	{
		case 'AUTH' :
			switch (commands[1])
			{
				case "OK" :
					cs_log("Successfully registered on the control server");
					cs_executeDeferred();
					break;

				case "ERROR" :
					showToast(LANG.registration_error, "error");
					cs_log("Control server registration error: "+fulltext, "error");
					break;

				default :
					cs_send("AUTH "+currentAcct.id+" "+sha256(commands[1]+sha256(currentAcct.nonce)));
					break;
			}
			break;

		case 'RESTART' :
			switch (commands[1])
			{
				case "OK" :
					showToast(LANG.streaming_servers_restarted);
					break;

				case "ERROR" :
					showToast(LANG.could_not_restart, "error");
					cs_log("Could not restart endpoints: "+fulltext, "error");
					break;
			}
			break;


		case 'RELOAD' :
			switch (commands[1])
			{
				case "OK" :
					showToast(LANG.endpoints_reloaded);
					break;

				case "ERROR" :
					showToast(LANG.endpoint_reload_error+": "+fulltext, "error");
					break;
			}
			break;
	}
}

function cs_restartEndpoints (endpointId)
{
	endpointId = endpointId || '*';
	cs_send("RESTART "+endpointId);
}

function cs_reloadEndpoints()
{
	cs_send("RELOAD");
}

function cs_executeDeferred()
{
	if (typeof deferredCommands !== 'undefined' && deferredCommands.length > 0)
	{
		for (var i = 0; i < deferredCommands.length; i++)
			deferredCommands[i]();
	}
}