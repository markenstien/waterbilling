<?php build('content') ?>
    <div class="card">
        <?php Flash::show()?>
        <div class="card-body">
            <section class="mt-2 mb-2">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Customer Details</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Name : </td>
                                    <td><?php echo $customer->full_name?></td>
                                </tr>
                                <tr>
                                    <td>Balance : </td>
                                    <td><?php echo $balance?></td>
                                </tr>
                                <tr>
                                    <td>Points : </td>
                                    <td><?php echo $customer->meta->points?></td>
                                </tr>
                                <tr>
                                    <td>Address : </td>
                                    <td><?php echo $customer->full_address?></td>
                                </tr>
                                <?php if(!isEqual(whoIs('user_type'), 'customer')) :?>
                                <tr>
                                    <td>Water Station : </td>
                                    <td><?php echo wLinkDefault(_route('platform:show', $customer->platform_id), $customer->platform_name)?></td>
                                </tr>
                                <?php endif?>
                            </table>
                        </div>
                    </div>
                    <?php if(!isEqual(whoIs('user_type'), 'customer')) :?>
                        <div class="col-md-6">
                            <h4>Containers</h4>
                            <?php echo $containerForm->getForm()?>
                        </div>
                    <?php endif?>
                </div>
            </section>
            
            <section>
                <h4>Containers</h4>
                <table class="table table-bordered">
                    <thead>
                        <th>Label</th>
                        <th>Container Type</th>
                    </thead>

                    <tbody>
                        <?php foreach($containers as $key => $row) :?>
                            <tr>
                                <td><?php echo $row->container_label?></td>
                                <td><?php echo $row->container_type?></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>