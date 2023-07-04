<?php
    use Form\PlatformForm;
    load(['PlatformForm'],APPROOT.DS.'form');

    class PlatformController extends Controller
    {

        public function __construct()
        {
            parent::__construct();
            $this->data['platformForm'] = new PlatformForm();
            $this->model = model('PlatformModel');
        }

        public function index() {
            $platforms = $this->model->all(['is_active' => true], 'platform_name asc');
            $this->data['platforms'] = $platforms;
            return $this->view('platform/index', $this->data);
        }

        public function create() {
            $request = request()->inputs();

            if(isSubmitted()) {
                $res = $this->model->createOrUpdate($request);
                $res ? Flash::set($this->model->getMessageString()) : Flash::set($this->model->getErrorString());
                
                return redirect(_route('platform:index'));
            }
            return $this->view('platform/create', $this->data);
        }

        public function edit($id) {

            $request = request()->inputs();

            if (isSubmitted()) {
                $res = $this->model->createOrUpdate($request, $request['id']);

                if ($res) {
                    Flash::set($this->model->getMessageString());
                } else {
                    Flash::set($this->model->getErrorString());
                }
                return redirect(_route('platform:show', $id));
            }
            $platform = $this->model->get($id);

            $this->data['platformForm']->setValueObject($platform);
            $this->data['platformForm']->add([
                'type' => 'hidden',
                'name' => 'id',
                'value' => $id
            ]);

            $this->data['platformForm']->init([
                'url' => _route('platform:edit', $id)
            ]);

            $this->data['platform'] = $platform;

            return $this->view('platform/edit', $this->data);
        }

        public function show($id) {
            $platform = $this->model->get($id);
            $this->data['platform'] = $platform;
            $this->data['customers'] = $this->model->getCustomers($id);
            return $this->view('platform/show', $this->data);
        }

        public function destroy($id) {
            $this->model->update([
                'is_active' => false
            ], $id);

            Flash::set("Platform Removed");

            return redirect(_route('platform:index'));
        }
    }