<?php
    namespace Form;
    use Core\Form;
    load(['Form'],CORE);

    class CustomerForm extends Form {

        public function __construct()
        {
            parent::__construct();
            $this->addName();
        }
        public function addName(){
            $this->add([
                'type' => 'text',
                'name' => 'full_name',
                'options' => [
                    'label' => 'Full Name'
                ],
                'class' => 'form-control',
                'required' => true
            ]);
        }

    }