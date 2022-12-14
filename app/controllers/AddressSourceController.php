<?php

    class AddressSourceController extends Controller
    {
        public function __construct()
        {
            $this->model = model('AddressSourceModel');
        }

        public function index() {
            $this->data['address_sources'] = $this->model->all(null,'type asc');
            return $this->view('address_source/index', $this->data);
        }

        public function createOrEdit() {
            $request = request()->inputs();

            if(isSubmitted()) {
                $this->model->createOrUpdate($request);
            }
            return $this->view('address_source/create_or_edit');
        }
    }