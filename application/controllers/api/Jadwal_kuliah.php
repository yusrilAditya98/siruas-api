<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Jadwal_kuliah extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Jadwal_model', 'jadwal');
        // $this->load->model('Dosen_model', 'dosen');
    }

    public function index_get()
    {
        $id = $this->get('id_jadwal_kuliah');
        if ($id == null) {
            $jadwal = $this->jadwal->getJadwal();
        } else {
            $jadwal = $this->ruangan->getJadwal($id);
        }

        if ($jadwal) {
            $this->set_response([
                'status' => true,
                'data' => $jadwal,
                'message' => 'Data jadwal berhasil ditemukan'
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'Data jadwal tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
