<?php build('content') ?>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Address Source</h4>
            <?php echo wLinkDefault(_route('adrs-src:index'),'List')?>
        </div>

        <div class="card-body">
            <?php
                Form::open([
                    'action' => _route('adrs-src:createOrEdit')
                ])
            ?>
            
            <div class="form-group mt-2">
                <?php
                    Form::label('Type');
                    Form::select('type',Module::all('address_source'),'',['class' => 'form-control', 'required' => true])
                ?>
            </div>

            <div class="form-group mt-2">
                <?php
                    Form::label('Name');
                    Form::text('value','',['class' => 'form-control', 'required' => true])
                ?>
            </div>

            <div class="form-group">
                <?php
                    Form::label('ABBR');
                    Form::text('abbr','',['class' => 'form-control','required' => true])
                ?>
            </div>

            <div class="form-group">
                <?php Form::submit('', 'Save')?>
            </div>

            <?php Form::close()?>
        </div>
    </div>
<?php endbuild()?>
<?php loadTo()?>