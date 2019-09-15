<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Peminjaman extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ruangan_model', 'ruangan');
    }

    public function index_get()
    {
        $id = $this->get('id_peminjaman');
        if ($id == null) {
            $peminjaman = $this->ruangan->getPeminjaman();
        } else {
            $peminjaman = $this->ruangan->getPeminjaman($id);
        }

        if ($peminjaman) {
            $this->set_response([
                'status' => true,
                'data' => $peminjaman,
                'message' => 'Data peminjaman berhasil ditemukan'
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id_peminjaman');
        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'id tidak ada',
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ($this->ruangan->deletePeminjaman($id) > 0) {
                $this->db->delete('peminjaman_non_rutin', ['id_peminjaman_non_rutin' => $id]);
                $this->db->delete('detail_peminjaman_non_rutin', ['id_peminjaman_non_rutin' => $id]);
                $this->response([
                    'status' => true,
                    'data' => $id,
                    'message' => 'Data peminjaman berhasil dihapus'
                ], REST_Controller::HTTP_NO_CONTENT);
            } else {
                // Not Found
                $this->set_response([
                    'status' => false,
                    'message' => 'id peminjaman tidak ditemukan'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function index_post()
    {

        if ($this->post('id_peminjaman')) {
            $id_peminjaman_non_rutin = $this->post('id_peminjaman');
        } else {
            $kode_tgl = str_replace("-", "", $this->post('tanggal_pemakaian'));
            $random = (rand(10, 99));
            $id_peminjaman_non_rutin = $kode_tgl . "" . $this->post('jam_mulai_peminjaman') . "" .  $this->post('jam_selesai_peminjaman') . "" . $this->post('penyelengara') . "" . $random;
        }

        $data['peminjaman'] = [
            'id_peminjaman' => $id_peminjaman_non_rutin,
            'id_peminjam' => $this->post('id_peminjam'),
            'tanggal_peminjaman' => $this->post('tanggal_peminjaman'),
            'jenis_peminjaman' => $this->post('jenis_peminjaman'),
            'status_peminjaman' => $this->post('status_peminjaman')
        ];
        $data['peminjaman_non_rutin'] = [
            'id_peminjaman_non_rutin' => $id_peminjaman_non_rutin,
            'id_peminjam' => $this->post('id_peminjam'),
            'penyelenggara' => $this->post('penyelenggara'),
            'nama_agenda' => $this->post('nama_agenda'),
            'jam_mulai_peminjaman' => $this->post('jam_mulai_peminjaman'),
            'jam_selesai_peminjaman' => $this->post('jam_selesai_peminjaman'),
            'tanggal_peminjaman' => $this->post('tanggal_peminjaman'),
            'tanggal_pemakaian' => $this->post('tanggal_pemakaian'),
            'keterangan' => $this->post('keterangan'),
            'status' => $this->post('status'),
            'kategori' => $this->post('kategori'),
            'nip_validator' => $this->post('nip_validator'),
            'status_wakadek' => $this->post('status_wakadek'),
        ];

        $data['ruangan'] = [
            'id_peminjaman_non_rutin' => $id_peminjaman_non_rutin,
            'id_peminjam' => $this->post('id_peminjam'),
            'id_ruangan' => $this->post('id_ruangan'),
            'id_jam_kuliah' => $this->post('id_jam_kuliah')
        ];


        $cek = $this->db->get_where('peminjaman', ['id_peminjaman' => $this->post('id_peminjaman')])->num_rows();
        if ($cek < 1) {
            if ($this->ruangan->postPeminjaman($data['peminjaman']) > 0) {
                // OK
                $this->ruangan->postPeminjamanNonRutin($data['peminjaman_non_rutin']);
                $this->ruangan->postDetailPeminjamanNonRutin($data['ruangan']);
                $this->response([
                    'status' => true,
                    'message' => 'Data peminjaman berhasil ditambah'
                ], REST_Controller::HTTP_CREATED);
            } else {
                // Not Found
                $this->set_response([
                    'status' => false,
                    'message' => 'gagal menambahkan data baru.',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            if ($this->ruangan->updatePeminjaman($data['peminjaman']) > 0 ||  $this->ruangan->updatePeminjamanNonRutin($data['peminjaman_non_rutin']) > 0 || $this->ruangan->updateDetailPeminjamanNonRutin($data['ruangan']) > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Data peminjaman berhasil diperbaharui'
                ], REST_Controller::HTTP_CREATED);
            } else {
                // Not Found
                $this->set_response([
                    'status' => false,
                    'message' => 'data gagal diperbaharui',
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
}
