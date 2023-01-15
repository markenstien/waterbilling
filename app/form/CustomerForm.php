<?php
    namespace Form;
    use Core\Form;
    load(['Form'],CORE);

    class CustomerForm extends Form {

        public function __construct()
        {
            parent::__construct();
            $this->addName();
            $this->addUsername();
            $this->addPassword();
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

        public function addUsername() {
            $this->add([
                'type' => 'text',
                'name' => 'username',
                'options' => [
                    'label' => 'Username'
                ],
                'class' => 'form-control',
                'required' => true
            ]);
        }


        public function addPassword() {
            $this->add([
                'type' => 'text',
                'name' => 'password',
                'options' => [
                    'label' => 'Password'
                ],
                'class' => 'form-control',
                'required' => true
            ]);
        }

    }