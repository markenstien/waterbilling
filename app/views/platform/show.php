<?php build('content')?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Platform : <?php echo $platform->platform_name?></h4>
        </div>
        
        <div class="card-body">
            
            <section>
                <h4>Customer</h4>
                Total : <?php echo count($customers)?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Containers</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach($customers as $key => $row):?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $row->full_name?></td>
                                    <td><?php echo $row->full_address?></td>
                                    <td>N/A</td>
                                    <td>
                                        <a href="#">Edit</a>
                                        <a href="<?php echo _route('user:showCustomer',$row->id)?>">Show</a>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>