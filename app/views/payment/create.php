<?php build('content') ?>
    <div class="card col-md-5 col-sm-12">
        <div class="card-header">
            <h4 class="card-title">Create Payment</h4>
            <?php Flash::show()?>
        </div>

        <div class="card-body">
            <?php
                Form::open([
                    'method' => 'post',
                    'action' => _route('payment:create')
                ]);

                Form::hidden('customer_id', $customer->customer_id);
                Form::hidden('parent_id', $parentId);
                // Form::hidden()
            ?>
            <div class="table-responsive">
                <table class="table table-bordered" style="text-align: center">
                    <tr>
                        <td colspan="2">
                            <h2><?php echo $customer->full_name?></h2>
                            <h1><?php echo amountHTML(amountConvert($amountToPay, 'ADD'))?></h1>
                            <span>Balance</span>
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

                    <!-- CASH PAYMENT FOR ADMIN AND PLATFORM ONLY -->
                    <?php if(!isEqual(whoIs('user_type'), 'customer')) :?>
                        <tr>
                            <td colspan="2">
                                <button name="btn_cash" class="btn btn-primary form-control" type="submit" role="submit">CASH</button>
                            </td>
                        </tr>
                    <?php endif?>

                    <tr>
                        <td colspan="2">
                            <div class="mb-2">
                                <?php
                                    Form::label('Reference Number');
                                    Form::text('gcash_reference', '' , [
                                        'class' => 'form-control',
                                        'placeholder' => 'Complete Reference or last 5 digits'
                                    ])
                                ?>
                            </div>
                            <div class="mb-2">
                                <?php
                                    Form::label('Mobile Number');
                                    Form::text('mobile_number', '' , [
                                        'class' => 'form-control',
                                        'placeholder' => 'Complete Account Number or last 4 digits'
                                    ])
                                ?>
                            </div>
                            <button class="btn btn-primary form-control" id="gCashPayment" name="btn_gcash" type="submit" role="submit">GCASH</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p>You Currently Have (<?php echo $customer->meta->points?>) points</p>
                            <?php Form::hidden('points', $customer->meta->points)?>
                            <button class="btn btn-primary form-control" id="redeemPayment" 
                                name="btn_redeem" type="submit" role="submit" <?php echo $customer->meta->points >= $paymentServicePointAccepted ? '' : 'disabled'?>>REDEEM/TOPUP</button>
                        </td>
                    </tr>
                    <?php if(!isEqual(whoIs('user_type'), 'customer')) :?>
                        <tr>
                            <td colspan="2">
                                <a href="<?php echo _route('transaction:index')?>" class="btn btn-warning form-control">Pay Later</a>
                            </td>
                        </tr>
                    <?php endif?>
                </table>
            </div>
            <?php
                Form::close();
            ?>
        </div>
    </div>
<?php endbuild()?>

<?php loadTo()?>