<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link href="/assets/fonts.css" rel="stylesheet">
	<link href="/assets/style.css" rel="stylesheet">
	<script type="text/javascript" src="/assets/jquery.js"></script>
	<script type="text/javascript" src="/assets/sha256.min.js"></script>
	<script type="text/javascript" src="/assets/core.js"></script>
	<script type="text/javascript" src="/assets/templates.js"></script>
	<script type="text/javascript" src="/assets/controlsocket.js"></script>
	<title><?php echo($content['title']); ?> - Neoton</title>
</head>
<body>

	<script type="text/javascript">
		var LANG = <?php echo($content['js_lang']); ?>;

		var currentAcct = {
			username : '<?php echo ($_SESSION['user_login']); ?>',
			id       : '<?php echo ($_SESSION['user_id']); ?>',
			nonce    : '<?php echo ($_SESSION['user_nonce']); ?>'
		};

		currentServerTime = <?php echo($content['current_timestamp']); ?>;
		$(document).ready(serverTimeInit);

		<?php if (!empty($content['deferred_commands']))
		{
			echo('var deferredCommands = ['.implode($content['deferred_commands'], ',').'];');
		} ?>

		$(document).ready(function() {
			cs_init({
				server : '<?php echo (CS_SERVER); ?>',
				port   : <?php echo (CS_PORT); ?>,
				secure : <?php echo (CS_SECURE ? "true" : "false"); ?>
			});
		});
	</script>

	<div id="toast-messages-wrapper">
	</div>

	<div class="pd-popup" id="message">
		<div class="pd-container">
			<h1 id="message-header">:3</h1>
			<p id="message-content"></p>
			<div id="message-buttons-container">
				<a href="javascript:void(0)" id="message-prompt-ok" class="pd-button">OK</a>
				<a href="javascript:void(0)" onclick="$('#message').fadeOut(100)" class="pd-button"><?php echo(_('Cancel')); ?></a>
			</div>
		</div>
	</div>

	<div id="container">
	<header>
		<table id="table-header">
			<tr>
				<td id="logo"><a href="/"><img src="/assets/gfx/logo_small.png"></a></td>
				
				<?php foreach ($content['menu'] as $item => $properties) : ?> 
				<td class="menu-item-cell<?php if($properties['active']) echo(' active');?>">
					<a
						class="menu-item"
						href="/<?php echo($item); ?>" 
						id="menu-item-<?php echo($item); ?>"><?php echo($properties['title']); ?>
					</a>
				</td>
				<?php endforeach; ?>

				<td id="menu-spacer">
				</td>

				<td id="user-management" class="menu-item-cell<?php if($content['is_user_panel']) echo(' active');?>">
					<a
						class="menu-item"
						id="menu-item-user"
						href="#"><?php echo ($_SESSION['user_login']); ?>
						<!-- This will be a link to user management page -->	
					</a>
				</td>
			</tr>
		</table>
	</header>
	<main>
		<div class="page-title">
			<h1><?php echo($content['title']); ?></h1>
			<?php echo($content['description']); ?>
		</div>
		<?php if(!empty($content['sys_message'])): ?>
		<div class="sysmessage <?php echo($content['sys_message']['type']); ?>">
			<?php echo($content['sys_message']['text']); ?>
		</div>
		<?php endif; ?>