<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Containers</h4>
        </div>

        <div class="card-body">
            <?php Flash::show()?>
            <?php if(empty($containers)) :?>
                <p class="text-center">There are no containers available.</p>
            <?php else:?>
            <div class="table-responsive">
                <table class="table table-bordered dataTable">
                    <thead>
                        <th>#</th>
                        <th>Label</th>
                        <th>Owner</th>
                        <th>Water Station</th>
                        <?php if(!isEqual(whoIs('user_type'), 'customer')) :?>
                            <th>Action</th>
                        <?php endif?>
                    </thead>

                    <tbody>
                        <?php foreach($containers as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->container_label?></td>
                                <td><?php echo wLinkDefault(_route('user:showCustomer',$row->cx_id),$row->full_name)?></td>
                                <td><?php echo wLinkDefault(_route('platform:show', $row->platform_id), $row->platform_name)?></td>
                                <?php if(!isEqual(whoIs('user_type'), 'customer')) :?>
                                    <td>
                                        <a href="<?php echo _route('transaction:deliverOrPickup', null, [
                                            'id' => $row->container_id,
                                            'action' => $action['pick_up']
                                        ])?>">Pickup</a> |
                                        <a href="<?php echo _route('transaction:deliverOrPickup', null, [
                                            'id' => $row->container_id,
                                            'action' => $action['delivery']
                                        ])?>">Deliver</a> 
                                    </td>
                                <?php endif?>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
            <?php endif?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>