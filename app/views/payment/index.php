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
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>External Reference</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach($payments as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->reference?></td>
                                <td><?php echo $row->amount?></td>
                                <td><?php echo $row->payment_method?></td>
                                <td><?php echo $row->created_at?></td>
                                <td><?php echo $row->payment_reference ?? 'N/A'?></td>
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