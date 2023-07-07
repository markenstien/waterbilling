<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Payments</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dataTable">
                    <thead>
                        <th>#</th>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>External Reference</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach($payments as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->reference?></td>
                                <td><?php echo $row->full_name?></td>
                                <td><?php echo $row->amount?></td>
                                <td><?php echo $row->payment_method?></td>
                                <td><?php echo $row->payment_reference ?? 'N/A'?></td>
                                <td><?php echo $row->approval_status?></td>
                                <td><?php echo $row->created_at?></td>
                                <td>
                                    <?php echo wLinkDefault(_route('payment:show', $row->id), 'Show Payment')?>
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