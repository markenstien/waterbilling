<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Add Stations</h4>
            <?php echo wLinkDefault(_route('platform:index'), 'Stations')?>
            <?php Flash::show()?>
        </div>

        <div class="card-body">
            <?php echo $platformForm->getForm()?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>