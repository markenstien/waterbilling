<?php build('content')?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Platform : <?php echo $platform->platform_name?></h4>
            <?php Flash::show()?>
        </div>
        
        <div class="card-body">
            <?php echo wLinkDefault('#', 'Filter', [
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#exampleModal'
            ])?>

            <?php if(!empty($_GET['filter_option'])) :?>
                <a href="?">Remove Filter</a>
            <?php endif?>
            <section>
                <h4>Customer</h4>
                Total : <?php echo count($customers)?>
                <?php echo wLinkDefault(_route('user:createCustomer', null , [
                    'parent_id' => $platform->id
                ]), 'Create Customer')?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Balance</th>
                            <th>Alert</th>
                            <th>Point</th>
                            <th>Containers</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach($customers as $key => $row):?>
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
                                                'route' => seal(_route('platform:show', $platform->id))
                                            ]),'Alert Balance Through SMS', [
                                                'title' => $row->phone_number
                                            ])?>
                                        </span>
                                    <?php else:?>
                                        <label>Nothing to Alert</label>
                                    <?php endif?>
                                    </td>
                                    <td><?php echo $row->points?></td>
                                    <td>N/A</td>
                                    <td>
                                        <a href="<?php echo _route('user:editCustomer', $row->id)?>">Edit</a> | 
                                        <a href="<?php echo _route('user:showCustomer',$row->id)?>">Show</a> | 
                                        <a href="<?php echo _route('user:deleteCustomer',$row->id)?>">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </section>
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