<?php build('content') ?>
	<?php Flash::show()?>

	<div class="card">
		<div class="card-header">
			<h4 class="card-title">User Preview</h4>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<td>First Name:</td>
						<td><?php echo $user->firstname?></td>
					</tr>
					<tr>
						<td>Last Name:</td>
						<td><?php echo $user->lastname?></td>
					</tr>

					<tr>
						<td>User Type:</td>
						<td><?php echo $user->user_type?>@<?php echo $user->access_type?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo()?>