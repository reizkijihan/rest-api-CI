<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Pegawai extends REST_Controller
{
        public function __construct()
        {
            parent::__construct();

            $this->load->model('Pegawai_model', 'pegawai');

            $this->methods['index_get']['limit'] = 50;
            $this->methods['index_put']['limit'] = 50;
            $this->methods['index_post']['limit'] = 50;
            $this->methods['index_delete']['limit'] = 50;
        }
    public function index_get() 
    {
        $id= $this->get('id');
        if ($id == null) {
            $pegawai = $this->pegawai->getPegawai();
        } else {
            $pegawai = $this->pegawai->getPegawai($id);
        }
        
        if ($pegawai) {
            $this->response([
                'status' => true,
                'data' => $pegawai
            ], REST_Controller::HTTP_OK);
         } else {
             $this->response([
                 'status' => false,
                 'message' => 'id not found'
             ], REST_Controller::HTTP_NOT_FOUND);

         }
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        if($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if( $this->pegawai->deletePegawai($id) > 0 ) {
                $this->response([
                    'status' => true,
                    'data' => $id,
                    'message' => 'deleted'
                ], REST_Controller::HTTP_NO_CONTENT);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'id not found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_post()
    {
        $data = [
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'notlp' => $this->post('notlp'),
            'pekerjaan' => $this->post('pekerjaan')
        ];

        if($this->pegawai->createPegawai($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'penyimpanan data berhasil dilakukan!'
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'failed to create new data'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $data = [
        'nama' => $this->put('nama'),
        'email' => $this->put('email'),
        'notlp' => $this->put('notlp'),
        'pekerjaan' => $this->put('pekerjaan')
    ];

    if($this->pegawai->updatePegawai($data, $id) > 0) {
        $this->response([
            'status' => true,
            'message' => 'data berhasil terupdate!'
        ], REST_Controller::HTTP_NO_CONTENT);
    } else {
        $this->response([
            'status' => false,
            'message' => 'failed to update data'
        ], REST_Controller::HTTP_BAD_REQUEST);
    }
}
}
