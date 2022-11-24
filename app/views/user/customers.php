<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Customers</h4>
            <?php echo wLinkDefault(_route('user:createCustomer'), 'Create')?>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dataTable">
                    <thead>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $key => $row) :?>
                            <tr>
                                <td><?php echo $row->full_name?></td>
                                <td><?php echo $row->full_address?></td>
                                <td>
                                    <a href="<?php echo _route('user:showCustomer', $row->customer_id)?>">Show</a> | 
                                    <a href="<?php echo _route('user:editCustomer', $row->customer_id)?>">Edit</a>
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