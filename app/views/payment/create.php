<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Create Payment</h4>
        </div>

        <div class="card-body">
            <?php
                Form::open([
                    'method' => 'post',
                    'action' => _route('payment:create')
                ]);

                Form::hidden('customer_id', $customer->customer_id);
            ?>
            <div class="table-responsive">
                <table class="table table-bordered" style="text-align: center">
                    <tr>
                        <td colspan="2">
                            <h2><?php echo $customer->full_name?></h2>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php echo Form::text('amount', amountConvert($amountToPay, 'ADD'), [
                                'class' => 'form-control text-center',
                                'reqiured' => true
                            ])?>
                            <div>Amount To Pay</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button name="btn_cash" type="submit" role="submit" class="btn btn-primary form-control">CASH</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-primary form-control">GCASH</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn btn-primary form-control">REDEEM/TOPUP</button>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
                Form::close();
            ?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>