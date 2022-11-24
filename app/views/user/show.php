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
						<td>Username:</td>
						<td><?php echo $user->username?></td>
					</tr>
					<tr>
						<td>User Type:</td>
						<td><?php echo $user->user_type?>@<?php echo $user->access_type?></td>
					</tr>
				</table>
			</div>
			
			<a href="<?php echo _route('user:delete', $user->id, [
				'route' => seal(_route('user:index'))
			])?>" class="btn btn-danger form-verify"> Delete user </a>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo()?>