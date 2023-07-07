<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Payment</h4>
            <?php echo wLinkDefault(_route('payment:index'), 'Back to Payments')?>
        </div>

        <div class="card-body">
            <?php Flash::show()?>
            <table class="table table-bordered">
                <tr>
                    <td>Reference: </td>
                    <td>#<?php echo $payment->reference?></td>
                </tr>
                <tr>
                    <td>Method</td>
                    <td><?php echo $payment->payment_method?></td>
                </tr>
                <tr>
                    <td>Approval Status</td>
                    <td><?php echo $payment->approval_status?></td>
                </tr>
                <?php if(isEqual($payment->approval_status,'approved')) :?>
                    <tr>
                        <td>Approved By</td>
                        <td><?php echo $payment->approver_name?></td>
                    </tr>
                    <tr>
                        <td>Approved Date</td>
                        <td><?php echo $payment->approval_date?></td>
                    </tr>
                <?php endif?>
                
                <?php if(isEqual($payment->payment_method, 'ONLINE')) :?>
                    <tr>
                        <td>External Reference: </td>
                        <td>#<?php echo $payment->external_reference?></td>
                    </tr>
                    <tr>
                        <td>Organization: </td>
                        <td><?php echo $payment->organization?></td>
                    </tr>
                    <tr>
                        <td>Account Number: </td>
                        <td><?php echo $payment->account_number?></td>
                    </tr>
                <?php endif?>
                <tr>
                    <td>Amount: </td>
                    <td><?php echo $payment->amount?></td>
                </tr>


                <?php if(!authPropCheck('customer','user_type')) :?>
                    <?php if(isEqual($payment->approval_status,'pending')) :?>
                        <?php echo wLinkDefault(_route('payment:approve', $payment->id), 'Approve Payment', [
                            'class' => 'btn btn-success btn-lg',
                            'icon' => 'check'
                        ])?> 

                        &nbsp;
                        &nbsp;
                        <?php echo wLinkDefault(_route('payment:decline', $payment->id), 'Decline Payment', [
                            'class' => 'btn btn-secondary btn-lg',
                            'icon' => 'check'
                        ])?>
                    <?php endif?>
                <?php endif?>
            </table>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>