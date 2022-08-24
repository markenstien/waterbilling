<?php build('content')?>
<?php Flash::show()?>
<div class="row">
	<div class="col-md-4 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
			<div class="d-flex justify-content-between align-items-baseline">
				<h6 class="card-title mb-0">Users</h6>
			</div>
			<div class="row">
				<div class="col-6 col-md-12 col-xl-5">
					<div class="d-flex align-items-baseline">
						<p class="text-success">
						<span>Within 30 days</span>
						</p>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>

	<div class="col-md-4 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
			<div class="d-flex justify-content-between align-items-baseline">
				<h6 class="card-title mb-0">Customers</h6>
			</div>
			<div class="row">
				<div class="col-6 col-md-12 col-xl-5">
					<div class="d-flex align-items-baseline">
						<p class="text-success">
						<span>Item Variants</span>
						</p>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>

	<div class="col-md-4 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
			<div class="d-flex justify-content-between align-items-baseline">
				<h6 class="card-title mb-0">Water Stations</h6>
			</div>
			<div class="row">
				<div class="col-6 col-md-12 col-xl-5">
					<div class="d-flex align-items-baseline">
						<p class="text-success">
						<span>Active</span>
						</p>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
<?php endbuild()?>
<?php loadTo()?>