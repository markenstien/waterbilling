<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Customers</h4>
            <?php echo wLinkDefault(_route('user:createCustomer'), 'Create')?> | 
            <?php echo wLinkDefault('#', 'Filter', [
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#exampleModal'
            ])?>

            <?php if(!empty($_GET['filter_option'])) :?>
                <a href="?">Remove Filter</a>
            <?php endif?>
        </div>

        <div class="card-body">
            <?php Flash::show()?>
            <div class="table-responsive">
                <table class="table table-bordered dataTable">
                    <thead>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Balance</th>
                        <th>Alert</th>
                        <th>Points</th>
                        <th>Platform</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->full_name?></td>
                                <td><?php echo $row->full_address?></td>
                                <td><?php echo $row->balance?></td>
                                <td>
                                    <?php if($row->balance < 0) :?>
                                        <span class="badge bg-info">
                                            <?php echo wLinkDefault(_route('api-sms:send', null, [
                                                'payload' => seal([
                                                    'message' => $row->full_name . ' You have an unpaid '.abs($row->balance) .' amount to '.COMPANY_NAME. ' Please pay imediately',
                                                    'number' => $row->phone_number
                                                ]),
                                            ]),'Alert Balance Through SMS', [
                                                'title' => $row->phone_number
                                            ])?>
                                        </span>
                                    <?php else:?>
                                        <label>Nothing to Alert</label>
                                    <?php endif?>
                                </td>
                                <td><?php echo $row->points?></td>
                                <td><?php echo wLinkDefault(_route('platform:show', $row->platform_id), $row->platform_name) ?></td>
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Customer Filter Modal</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
          </div>
          <div class="modal-body">
            <?php echo $_formCommon->start([
                'method' => 'get'
            ]);?>
                <div class="form-group">
                    <?php
                        Form::label('Filter Customer');
                        Form::select('filter_option', [
                            'with_balance' => 'User With Remaining Balance',
                            'with_points' => 'User With points'
                        ],'', ['class' => 'form-control']);
                    ?>
                </div>

                <div class="form-group mt-2">
                    <?php Form::submit('', 'Apply Filter')?>
                </div>
            <?php echo $_formCommon->start();?>
          </div>
        </div>
      </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>