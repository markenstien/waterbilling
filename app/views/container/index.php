<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Containers</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Label</th>
                        <th>Owner</th>
                        <th>Water Station</th>
                    </thead>

                    <tbody>
                        <?php foreach($containers as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->container_label?></td>
                                <td><?php echo wLinkDefault(_route('user:showCustomer',$row->cx_id),$row->full_name)?></td>
                                <td><?php echo wLinkDefault(_route('platform:show', $row->platform_id), $row->platform_name)?></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>