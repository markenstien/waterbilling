<?php

    class ContainerController extends Controller{

        public function __construct()
        {
            parent::__construct();
            $this->model = model('ContainerModel');
        }

        public function index() {
            if(!authPropCheck($this->_userService::ACCESS_VENDOR_MANAGEMENT)) {
                $this->data['containers'] = $this->model->getList([
                    'where' => [
                        'platform_id' => $this->data['whoIs']->parent_id
                    ]
                ]);
			}else{
                $this->data['containers'] = $this->model->getList();
            }

            
            return $this->view('container/index', $this->data);
        }

        public function create() {
            $request = request()->inputs();
            
            if(isSubmitted()) {
                $res = $this->model->createOrUpdate($request);
                if($res) {
                    Flash::set($this->model->getMessageString());
                }else{
                    Flash::set($this->model->getErrorString(), 'danger');
                }
                
                return redirect($request['_routeTo']);
            }
        }
    }