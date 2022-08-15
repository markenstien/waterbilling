<?php

    class ContainerController extends Controller{

        public function __construct()
        {
            $this->model = model('ContainerModel');
        }

        public function index() {
            $this->data['containers'] = $this->model->getList();
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