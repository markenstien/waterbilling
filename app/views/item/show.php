<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Item Preview</h4>
            <?php Flash::show()?>
            <?php echo btnEdit(_route('item:edit', $item->id))?>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <section>
                        <h4>Details</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Name : </td>
                                    <td><?php echo $item->name?></td>
                                </tr>
                                <tr>
                                    <td>SKU : </td>
                                    <td><?php echo $item->sku?></td>
                                </tr>
                                <tr>
                                    <td>Barcode : </td>
                                    <td><?php echo empty($item->barcode) ? 'N/A' : $item->barcode?></td>
                                </tr>
                                <tr>
                                    <td>Cost Price : </td>
                                    <td><?php echo $item->cost_price?></td>
                                </tr>
                                <tr>
                                    <td>Sell Price : </td>
                                    <td><?php echo $item->sell_price?></td>
                                </tr>
                                <tr>
                                    <td>Minimum Stock : </td>
                                    <td><?php echo $item->min_stock?></td>
                                </tr>
                                <tr>
                                    <td>Maximum Stock : </td>
                                    <td><?php echo $item->max_stock?></td>
                                </tr>
                                <tr>
                                    <td>Category : </td>
                                    <td><?php echo $item->category_id?></td>
                                </tr>
                                <tr>
                                    <td>Variant : </td>
                                    <td><?php echo $item->variant?></td>
                                </tr>
                                <tr>
                                    <td>Remarks : </td>
                                    <td><?php echo $item->remarks?></td>
                                </tr>
                            </table>
                        </div>
                    </section>
                </div>
                <div class="col-md-6">
                    <h4>Images</h4>
                    <?php echo $this->_attachmentForm->getForm()?>
                    <hr>

                    <?php if(!empty($images)) :?>
                        <div class="row">
                            <?php foreach($images as $key => $row) :?>
                                <div class="col-md-6">
                                    <div>
                                        <img src="<?php echo $row->full_url?>"
                                            style="width:100%">
                                        <div><label for="#"><?php echo $row->label?></label></div>
                                        <a href="<?php echo _route('attachment:delete', $row->id)?>">Delete</a>
                                    </div>
                                </div>
                            <?php endforeach?>
                        </div>
                    <?php endif?>
                </div>
            </div>

            <div class="mt-2" style="border:1px solid #000; padding:10px">
                <section>
                    <h4>Stocks : <?php echo $item->total_stock?></h5></h4>
                    <div><?php 
                        echo wLinkDefault(_route('stock:create', [
                                'item_id' => $item->id
                        ]), 'Add Stocks'); 
                    ?></div>
                    <label for="#">Recent Movement</label>
                    <table class="table">
                        <tr>
                            <td>Origin</td>
                            <td>Description</td>
                            <td>Quantity</td>
                        </tr>
                        <tbody>
                            <?php foreach($stocks as $key => $row):?>
                                <tr>
                                    <td><?php echo $row->entry_origin?></td>
                                    <td><?php echo $row->remarks?></td>
                                    <td><?php echo $row->quantity?></td>
                                </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
<?php endbuild() ?>
<?php loadTo()?>