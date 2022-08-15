<?php
    namespace Form;

    use Core\Form;
    use Services\AddressSourceService;

    load(['Form'],CORE);
    load(['AddressSourceService'],SERVICES);

    class AddressForm extends Form{
        public $prefix = 'address';

        public function __construct()
        {
            parent::__construct();

            $this->source = model('AddressSourceModel');
            $this->addStreet();
            $this->addHouseNumber();
            $this->addBarangay();
            $this->addBarangay();
            $this->addCity();

            $this->customSubmit('Save');
        }
        // getRow($name , $attributes = [])
        /**
         * Override
         */
        public function getRow($name, $attributes = []) {
            return parent::getRow($name, $attributes);
        }
        public function getCol($name, $attributes = []) {
            return parent::getCol($name, $attributes);
        }

        public function addHouseNumber(){
            $this->add([
                'type' => 'text',
                'name' => "$this->prefix[house_number]",
                'required' => true,
                'class' => 'form-control',
                'options' => [
                    'label' => 'House Number'
                ]
            ]);
        }

        public function addStreet() {
            $this->add([
                'type' => 'select',
                'name' => "{$this->prefix}[street_id]",
                'required' => true,
                'class' => 'form-control',
                'options' => [
                    'label' => 'Street',
                    'option_values' => $this->source->fetchArray(AddressSourceService::STREET,['id', 'abbr@value'])
                ]
            ]);
        }

        public function addBarangay(){
            $this->add([
                'type' => 'select',
                'name' => "$this->prefix[barangay]",
                'required' => true,
                'class' => 'form-control',
                'options' => [
                    'label' => 'Barangay',
                    'option_values' => $this->source->fetchArray(AddressSourceService::BARANGAY)
                ]
            ]);
        }

        public function addCity(){
            $this->add([
                'type' => 'select',
                'name' => "$this->prefix[city]",
                'required' => true,
                'class' => 'form-control',
                'options' => [
                    'label' => 'City',
                    'option_values' => $this->source->fetchArray(AddressSourceService::CITY)
                ]
            ]);
        }

        public function addBuilding(){

        }

        public function addCorner() {

        }
        
        public function addBlock() {

        }
    }