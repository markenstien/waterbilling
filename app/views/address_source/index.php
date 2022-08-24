<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Address Sources</h4>
            <?php echo wLinkDefault(_route('adrs-src:createOrEdit'),'Add new')?>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>ABBR</th>
                    </thead>

                    <tbody>
                        <?php foreach($address_sources as $key => $row): ?>
                            <tr>
                                <td><?php echo ++$key?></td>
                                <td><?php echo $row->type?></td>
                                <td><?php echo $row->value?></td>
                                <td><?php echo $row->abbr?></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>