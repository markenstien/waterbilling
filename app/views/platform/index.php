<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Water Stations</h4>
            <?php echo wLinkDefault(_route('platform:create'), 'Create')?>
        </div>
        <div class="card-body">
            <?php Flash::show()?>
            <div class="table-responsive">
                <table class="table table-bordered dataTable">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach($platforms as $key => $row) :?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->platform_name?></td>
                                <td><?php echo $row->contact_number?></td>
                                <td>
                                    <a href="<?php echo _route('platform:edit', $row->id)?>">Edit</a>
                                    <a href="<?php echo _route('platform:show', $row->id)?>">Show</a>
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