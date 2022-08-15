<?php
    namespace Form;
    use Core\Form;
    load(['Form'],CORE);

    class PlatformForm extends Form {

        public function __construct()
        {
            parent::__construct();
            
            $this->init([
                'url' => _route('platform:create')
            ]);

            $this->addName();
            $this->addContact();
            $this->customSubmit('Save');
        }

        public function addName(){
            $this->add([
                'type' => 'text',
                'name' => 'platform_name',
                'required' => true,
                'class' => 'form-control',
                'options' => [
                    'label' => 'Company Name'
                ]
            ]);
        }

        public function addContact() {
            $this->add([
                'type' => 'text',
                'name' => 'contact_number',
                'required' => true,
                'class' => 'form-control',
                'options' => [
                    'label' => 'Contact Number'
                ]
            ]);
        }
    }