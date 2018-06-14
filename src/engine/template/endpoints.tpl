<div class="add-item">
	<h2 onclick="toggle('add-item-endpoint');"><?php echo(_('Add an endpoint')); ?></h2>
	<form action="/endpoints/add" method="post" class="add-item-form" id="add-item-endpoint">
		<input type="text" name="endpoint-name" placeholder="<?php echo(_('endpoint name')); ?>" required>
		<input type="text" name="endpoint-ip" placeholder="<?php echo(_('IP address')); ?>" required pattern="^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$">
		<input type="text" name="endpoint-port" placeholder="<?php echo(_('inbound port')); ?>" required pattern="^[0-9]{1,5}$">
		<input type="text" name="endpoint-mount" placeholder="<?php echo(_('mount point')); ?>" required pattern="^[a-zA-Z0-9-_\.]{1,15}$">
		<input type="text" name="endpoint-password" placeholder="<?php echo(_('password')); ?>" required>
		<label for="endpoint-schedule"><?php echo(_('Schedule')); ?>:</label>
		<select name="endpoint-schedule" required>
			<?php foreach ($content['schedule'] as $sch) : ?>
				<option value="<?php echo($sch['schedule_id']); ?>"><?php echo ($sch['schedule_name']); ?></option>
			<?php endforeach; ?>
		</select>
		<input type="submit" value="<?php echo(_('create')); ?>">
	</form>
</div>

<div class="items-list">
	<?php foreach ($content['endpoints'] as $ep): ?>
		<div class="item-track">
			<div class="item-track-name" onclick="toggle('track-tools-<?php echo ($ep['endpoint_id']); ?>')"><?php echo ($ep['endpoint_name']); ?> [#<?php echo ($ep['endpoint_id']); ?>]</div>
			<div class="item-track-tools" id="track-tools-<?php echo ($ep['endpoint_id']); ?>">
				<div class="item-action-title"><?php echo(_('Edit an endpoint')); ?></div>
				<form onsubmit="return editEndpoint(event, this)" method="post">
					<input type="text" name="endpoint-name" placeholder="<?php echo(_('endpoint name')); ?>" value="<?php echo($ep['endpoint_name']); ?>" required>
					<input type="text" name="endpoint-ip" placeholder="<?php echo(_('Ip address')); ?>" value="<?php echo($ep['endpoint_ip']); ?>" required pattern="^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$">
					<input type="text" name="endpoint-port" placeholder="<?php echo(_('inbound port')); ?>" value="<?php echo($ep['endpoint_port']); ?>" required pattern="^[0-9]{1,5}$">
					<input type="text" name="endpoint-mount" placeholder="<?php echo(_('mount point')); ?>" value="<?php echo($ep['endpoint_mount']); ?>" required pattern="^[a-zA-Z0-9-_\.]{1,15}$">
					<input type="text" name="endpoint-password" placeholder="<?php echo(_('password')); ?>" value="<?php echo($ep['endpoint_password']); ?>" required>
					<input type="hidden" name="endpoint-id" value="<?php echo($ep['endpoint_id']); ?>">
					<input type="submit" value="<?php echo(_('update')); ?>">
				</form>

				<div class="item-action-title"><?php echo(_('Set schedule')); ?></div>
				<form onsubmit="return setEndpointSchedule(event, this)" method="post">
					<select name="endpoint-schedule">
						<?php foreach ($content['schedule'] as $sch) : ?>
							<option value="<?php echo($sch['schedule_id']); ?>"<?php if($sch['schedule_id'] == $ep['endpoint_schedule']) echo (' selected="selected"');?>><?php echo ($sch['schedule_name']); ?></option>
						<?php endforeach; ?>
					</select>
					<input type="hidden" name="endpoint-id" value="<?php echo($ep['endpoint_id']); ?>">
					<input type="submit" value="<?php echo(_('set')); ?>">
				</form>
				<button class="button-remove" onclick="removeEndpoint(<?php echo ($ep['endpoint_id']); ?>); return false"><?php echo(_('remove endpoint')); ?></button>
			</div>
		</div>
	<?php endforeach; ?>
</div>