<?php
    namespace Form;
    use Core\Form;
    use Services\ContainerService;
    load(['ContainerService'],SERVICES);
    load(['Form'], CORE);

    class ContainerForm extends Form {

        public function __construct()
        {
            parent::__construct();
            $this->init([
                'url' => _route('container:create')
            ]);
            $this->addLabel();
            $this->addType();
            $this->addOwner();
            $this->addPlatform();

            $this->customSubmit('Save Container');
        }

        public function addLabel() {
            $this->add([
                'type' => 'text',
                'name' => 'container_label',
                'options' => [
                    'label' => 'Container Label',
                ],
                'required' => true
            ]);    
        }

        public function addType() {
            $this->add([
                'type' => 'select',
                'name' => 'container_type',
                'options' => [
                    'label' => 'Container Type',
                    'option_values' => [
                        ContainerService::DISPENSER,
                        ContainerService::JAG,
                    ]
                ],
                'required' => true
            ]); 
        }

        public function addOwner() {
            $this->add([
                'type' => 'hidden',
                'name' => 'customer_id'
            ]);
        }

        public function addPlatform() {
            $this->add([
                'type' => 'hidden',
                'name' => 'platform_id'
            ]);
        }
    }