<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Add Customer</h4>
            <?php echo wLinkDefault(_route('user:customers'), 'Customers')?>
        </div>

        <div class="card-body">
            <?php
                Form::open([
                    'method' => 'post',
                ])
            ?>
            <div class="form-group">
                <div class='row mb-2'>
					<div class='col-md-3'>Platforms</div>
					<div class='col-md-9'>
                        <?php
                            Form::select('parent_id', arr_layout_keypair($platforms,['id','platform_name']), $platformId ?? '', [
                                'class' => 'form-control'
                            ])
                        ?>
                    </div>
				</div>
            </div>
            <?php echo $form->getFormItems()?>
                    
            <?php echo $address->getFormItems()?>

            <?php Form::close()?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>