<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Ruangan extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ruangan_model', 'ruangan');
    }

    public function index_get()
    {
        $id = $this->get('id_ruangan');
        if ($id == null) {
            $ruangan = $this->ruangan->getRuangan();
        } else {
            $ruangan = $this->ruangan->getRuangan($id);
        }

        if ($ruangan) {
            $this->set_response([
                'status' => true,
                'data' => $ruangan,
                'message' => 'Ruangan berhasil ditemukan'
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'Ruangan tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        // $data['jam_mulai'] = $this->post('jam_mulai_peminjaman');
        // $data['jam_selesai'] = $this->post('jam_selesai_peminjaman');
        // $data['tanggal_pemakaian'] = $this->post('tanggal_pemakaian');
        // $data['id_ruangan'] = $this->post('id_ruangan');
        // if ($data['jam_mulai'] == null || $data['jam_selesai'] == null || $data['tanggal_pemakaian'] == null ||   $data['id_ruangan'] == null) {
        //     $this->set_response([
        //         'status' => false,
        //         'message' => 'data kurang lengkap'
        //     ], REST_Controller::HTTP_NOT_FOUND);
        // } else {
        //     $data['ruangan'] = $this->ruangan->cekRuangan($data);
        //     if ($data['ruangan'] == true) {
        //         $this->set_response([
        //             'status' => true,
        //             'data' => $data['ruangan'],
        //             'message' => 'Ruangan telah digunakan'
        //         ], REST_Controller::HTTP_OK);
        //     } else {
        //         $this->set_response([
        //             'status' => true,
        //             'data' => $data['ruangan'],
        //             'message' => 'ruangan kosong'
        //         ], REST_Controller::HTTP_NOT_FOUND);
        //     }
        // }

        $tanggal_pemakaian = $this->post('tanggal_pemakaian');
        $jam_mulai_pemakaian = $this->post('jam_mulai_pemakaian');
        $jam_selesai_pemakaian = $this->post('jam_selesai_pemakaian');

        $day = date('l', strtotime($tanggal_pemakaian));
        $id_ruangan_input = $this->post('id_ruangan');
        $i = 0;
        $jam_mulai_rutin[$i] = 0;
        $jam_selesai_rutin[$i] = 0;
        $result_rutin = 0;
        $result_jadwal = 0;

        $asd = 0;
        $status_jenis_ruangan = $this->ruangan->get_status_ruangan($id_ruangan_input);
        $ruang_rutin = $this->ruangan->get_data_cek_ruangan_rutin($tanggal_pemakaian, $id_ruangan_input);
        $ruang_jadwal = $this->ruangan->get_data_cek_jadwal_rutin($day, $id_ruangan_input);
        foreach ($status_jenis_ruangan as $jr) {
            $j_ruangan = $jr->jenis_ruangan;
        }
        //if($jenis_ruangan != 'rutsin'){
        foreach ($ruang_rutin as $rutin) {
            $jam_kuliah[$i] = $rutin->id_jam_kuliah;
            $id_jadwal[$i] = $rutin->id_peminjaman_rutin;
            $id_ruangan[$i] = $rutin->id_ruangan;
            $jadwal[$i] = $rutin->id_peminjaman_rutin;
            if ($id_ruangan[$i] == $id_ruangan_input) {
                if ($jam_kuliah[$i] == 1) {
                    $jam_mulai_rutin[$i] = 8;
                    $jam_selesai_rutin[$i] = 9;
                } elseif ($jam_kuliah[$i] == 2) {
                    $jam_mulai_rutin[$i] = 10;
                    $jam_selesai_rutin[$i] = 11;
                } elseif ($jam_kuliah[$i] == 3) {
                    $jam_mulai_rutin[$i] = 13;
                    $jam_selesai_rutin[$i] = 14;
                } elseif ($jam_kuliah[$i] == 4) {
                    $jam_mulai_rutin[$i] = 16;
                    $jam_selesai_rutin[$i] = 16;
                } else {
                    $jam_mulai_rutin[$i] = 19;
                    $jam_selesai_rutin[$i] = 20;
                }
                for ($x = $jam_mulai_rutin[$i]; $x <= $jam_selesai_rutin[$i]; $x++) {
                    for ($y = $jam_mulai_pemakaian; $y <= $jam_selesai_pemakaian; $y++) {
                        if ($x == $y) {
                            //echo "jam kuliah ".$i ."=". $jam_kuliah[$i]."x->".$x." // y->".$y;
                            $result_rutin++;
                            //echo ",jam kuliah ".$i ."=". $id_jadwal[$i]." x/y =".$x."/".$y."r".$id_ruangan[$i];
                        }
                    }
                }
            }
            $i++;
        }


        $i = 0;

        foreach ($ruang_jadwal as $jadwal) {
            $jam_kuliah[$i] = $jadwal->id_jam_kuliah;
            if ($jam_kuliah[$i] == 1) {
                $jam_mulai_rutin[$i] = 8;
                $jam_selesai_rutin[$i] = 9;
            } elseif ($jam_kuliah[$i] == 2) {
                $jam_mulai_rutin[$i] = 10;
                $jam_selesai_rutin[$i] = 11;
            } elseif ($jam_kuliah[$i] == 3) {
                $jam_mulai_rutin[$i] = 13;
                $jam_selesai_rutin[$i] = 14;
            } elseif ($jam_kuliah[$i] == 4) {
                $jam_mulai_rutin[$i] = 16;
                $jam_selesai_rutin[$i] = 16;
            } else {
                $jam_mulai_rutin[$i] = 19;
                $jam_selesai_rutin[$i] = 20;
            }
            for ($x = $jam_mulai_rutin[$i]; $x <= $jam_selesai_rutin[$i]; $x++) {
                for ($y = $jam_mulai_pemakaian; $y <= $jam_selesai_pemakaian; $y++) {
                    if ($x == $y) {
                        //echo "jam kuliah ".$i ."=". $jam_kuliah[$i]."x->".$x." // y->".$y;
                        $result_jadwal++;
                        //  echo $result_jadwal;
                        //echo ",jam kuliah ".$i ."=". $id_jadwal[$i]." x/y =".$x."/".$y."r".$id_ruangan[$i];
                    }
                }
            }

            $i++;
        }
        if ($result_rutin > 0 || $result_jadwal > 0) {
            $this->set_response([
                'status' => true,
                'data' => true,
                'message' => 'Ruangan telah digunakan'
            ], REST_Controller::HTTP_OK);
        } else {
            $result_non_rutin = 0;
            $ruang_non_rutin = $this->ruangan->get_data_cek_ruangan_non_rutin($tanggal_pemakaian, $id_ruangan_input);
            foreach ($ruang_non_rutin as $non_rutin) {
                $jam_mulai_peminjaman = $non_rutin->jam_mulai_peminjaman;
                $jam_selesai_peminjaman = $non_rutin->jam_selesai_peminjaman;
                for ($p = $jam_mulai_peminjaman; $p <= $jam_selesai_peminjaman; $p++) {
                    for ($q = $jam_mulai_pemakaian; $q <= $jam_selesai_pemakaian; $q++) {
                        if ($p == $q) {
                            //echo "jam kuliah ".$i ."=". $jam_kuliah[$i]."x->".$x." // y->".$y;
                            $result_non_rutin++;
                            //echo ",jam kuliah ".$i ."=". $id_jadwal[$i]." x/y =".$x."/".$y."r".$id_ruangan[$i];
                        }
                    }
                }
            }
            if ($result_non_rutin > 0) {
                $this->set_response([
                    'status' => true,
                    'data' => true,
                    'message' => 'Ruangan telah digunakan'
                ], REST_Controller::HTTP_OK);
            } else {
                $this->set_response([
                    'status' => true,
                    'data' => false,
                    'message' => 'ruangan kosong'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
}
