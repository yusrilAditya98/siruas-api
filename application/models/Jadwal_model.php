<?php

class Jadwal_model extends CI_Model
{

    public function getJadwal($id = null)
    {
        if ($id == null) {
            $this->db->select('jadwal_kuliah.*,jam_kuliah.jam_kuliah,ruangan.ruangan,mata_kuliah.nama_matkul,pegawai.nama');
            $this->db->from('jadwal_kuliah');
            $this->db->join('jam_kuliah', 'jam_kuliah.id_jam_kuliah=jadwal_kuliah.id_jam_kuliah', 'left');
            $this->db->join('mata_kuliah', 'jadwal_kuliah.kode_matkul=mata_kuliah.kode_matkul', 'left');
            $this->db->join('ruangan', 'jadwal_kuliah.id_ruangan=ruangan.id_ruangan', 'left');
            $this->db->join('pegawai', 'pegawai.nip=jadwal_kuliah.id_dosen', 'left');
            return $this->db->get()->result_array();
        } else {
            $this->db->select('jadwal_kuliah.*,jam_kuliah.jam_kuliah,ruangan.ruangan,mata_kuliah.nama_matkul,pegawai.nama');
            $this->db->from('jadwal_kuliah');
            $this->db->join('jam_kuliah', 'jam_kuliah.id_jam_kuliah=jadwal_kuliah.id_jam_kuliah', 'left');
            $this->db->join('mata_kuliah', 'jadwal_kuliah.kode_matkul=mata_kuliah.kode_matkul', 'left');
            $this->db->join('ruangan', 'jadwal_kuliah.id_ruangan=ruangan.id_ruangan', 'left');
            $this->db->join('pegawai', 'pegawai.nip=jadwal_kuliah.id_dosen', 'left');
            $this->db->where('jadwal_kuliah', $id);
            return $this->db->get()->result_array();
        }
    }
}
