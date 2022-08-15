<?php build('content') ?>
	
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Users</h4>
			<?php echo wLinkDefault(_route('user:create'), 'Create')?>
		</div>

		<div class="card-body">
			<?php Flash::show()?>

			<div class="table-responsive" style="min-height: 30vh;">
				<table class="table table-bordered dataTable">
					<thead>
						<th>Name</th>
						<th>Type</th>
						<th>Access</th>
						<th>Water Station</th>
						<th>Action</th>
					</thead>

					<tbody>
						<?php foreach( $users as $row) :?>
							<tr>
								<td><?php echo $row->lastname . ' , ' .$row->firstname?></td>
								<td><?php echo $row->user_type ?></td>
								<td><?php echo $row->access_type ?></td>
								<td><?php echo wLinkDefault(_route('platform:show', $row->parent_id), $row->platform_name) ?></td>
								<td>
									<?php 
										$anchor_items = [
											[
												'url' => _route('user:show' , $row->id),
												'text' => 'View',
												'icon' => 'eye'
											],

											[
												'url' => _route('user:edit' , $row->id),
												'text' => 'Edit',
												'icon' => 'edit'
											]
										];
									echo anchorList($anchor_items)?>
								</td>
							</tr>
						<?php endforeach?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo()?>