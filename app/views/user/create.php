<?php build('content') ?>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Create User</h4>
			<?php echo wLinkDefault(_route('user:index'), 'index')?>
			<?php Flash::show()?>
		</div>

		<div class="card-body">
			<?php echo $user_form->getForm()?>
		</div>
	</div>
<?php endbuild()?>
<?php loadTo()?>