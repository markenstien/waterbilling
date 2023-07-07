<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Transaction</h4>
            <?php Flash::show()?>
        </div>
        
        <div class="card-body">
            <?php if(empty($transactions)) :?>
                <p class="text-center">You have no transactions at the moment.</p>
            <?php else:?>
                <div class="table-responsive">
                    <table class="table table-bordered dataTable">
                        <thead>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Action</th>
                            <th>Amount</th>
                            <th>Staff</th>
                            <th>Water Station</th>
                        </thead>

                        <tbody>
                            <?php foreach($transactions as $key => $row) :?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->customer_name?></td>
                                    <td><?php echo $row->parent_key?></td>
                                    <td><?php echo amountHTML($row->amount)?></td>
                                    <td><?php echo $row->staff_name?></td>
                                    <td><?php echo $row->platform_name?></td>
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