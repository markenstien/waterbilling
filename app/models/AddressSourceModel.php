<?php 

    class AddressSourceModel extends Model
    {
        public $table = 'address_source';

        public $_fillables = [
            'abbr',
            'type',
            'value'
        ];
        
        public function createOrUpdate($platformData, $id = null)
        {
            return parent::createOrUpdate($platformData, $id);
        }

        public function fetchArray($type, $column = ['value', 'abbr@value']) {
            $results = parent::all([
                'type' => $type
            ],'value asc');
            
            if(!$results) {
                return [];
            }

            return arr_layout_keypair($results,$column);
        }
    }