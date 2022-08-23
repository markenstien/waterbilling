<?php 

    class ContainerMovementModel extends Model
    {
        public $table = 'container_movements';

        public function pickUpOrDelivery($containerData, $actionTaken) {
            return parent::store([

            ]);
        }
    }