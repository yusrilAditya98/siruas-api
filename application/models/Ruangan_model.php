<?php

class Ruangan_model extends CI_Model
{
    public function getRuangan($id = null)
    {
        if ($id == null) {
            return $this->db->get('ruangan')->result_array();
        } else {
            return $this->db->get_where('ruangan', ['id_ruangan' => $id])->result_array();
        }
    }

    public function getPeminjaman($id = null)
    {
        if ($id == null) {
            return $this->db->get('peminjaman')->result_array();
        } else {
            return $this->db->get_where('peminjaman', ['id_peminjaman' => $id])->result_array();
        }
    }

    public function deletePeminjaman($id)
    {
        $this->db->delete('peminjaman', ['id_peminjaman' => $id]);
        return $this->db->affected_rows();
    }

    public function postPeminjaman($data)
    {
        $this->db->insert('peminjaman', $data);
        return $this->db->affected_rows();
    }
    public function postPeminjamanNonRutin($data)
    {
        $this->db->insert('peminjaman_non_rutin', $data);
        return $this->db->affected_rows();
    }
    public function postDetailPeminjamanNonRutin($data)
    {
        $this->db->insert('detail_peminjaman_non_rutin', $data);
        return $this->db->affected_rows();
    }

    public function updatePeminjaman($data)
    {
        $this->db->where('id_peminjaman', $data['id_peminjaman']);
        $this->db->update('peminjaman', $data);
        return $this->db->affected_rows();
    }
    public function updatePeminjamanNonRutin($data)
    {
        $this->db->where('id_peminjaman_non_rutin', $data['id_peminjaman_non_rutin']);
        $this->db->update('peminjaman_non_rutin', $data);
        return $this->db->affected_rows();
    }
    public function updateDetailPeminjamanNonRutin($data)
    {
        $this->db->where('id_peminjaman_non_rutin', $data['id_peminjaman_non_rutin']);
        $this->db->update('detail_peminjaman_non_rutin', $data);
        return $this->db->affected_rows();
    }

    public function cekRuangan($data)
    {
        $this->db->select('*');
        $this->db->from('peminjaman_non_rutin as p');
        $this->db->join('detail_peminjaman_non_rutin as d', 'p.id_peminjaman_non_rutin = d.id_peminjaman_non_rutin');
        for ($i = $data['jam_mulai']; $i <=  $data['jam_selesai']; $i++) {
            $this->db->where('p.jam_mulai_peminjaman', $i);
        }
        $this->db->where('p.jam_selesai_peminjaman', $data['jam_selesai']);
        $this->db->where('p.tanggal_pemakaian', $data['tanggal_pemakaian']);
        $this->db->where('d.id_ruangan', $data['id_ruangan']);

        return $this->db->get()->data_seek();
    }

    function get_status_ruangan($id)
    {
        $this->db->select('id_ruangan, jenis_ruangan');
        $this->db->from('ruangan');
        $this->db->where('id_ruangan', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function get_data_cek_ruangan_rutin($tanggal_pemakaian, $id_ruangan)
    {
        $this->db->select('id_peminjaman_rutin,id_jam_kuliah,id_ruangan,tanggal_pemakaian');
        $this->db->from('peminjaman_rutin');
        $this->db->where('tanggal_pemakaian', $tanggal_pemakaian);
        $this->db->where('id_ruangan', $id_ruangan);
        $this->db->where('peminjaman_rutin.status !=', 'tolak');
        $query = $this->db->get();
        return $query->result();
    }
    function get_data_cek_jadwal_rutin($day, $id_ruangan)
    {
        $this->db->select('id_jadwal_kuliah,id_jam_kuliah');
        $this->db->from('jadwal_kuliah');
        $this->db->where('hari', $day);
        $this->db->where('id_ruangan', $id_ruangan);
        $this->db->where('status', 'ada');
        $query = $this->db->get();
        return $query->result();
    }
    function get_data_cek_ruangan_non_rutin($tanggal_pemakaian, $id_ruangan)
    {
        $this->db->select('peminjaman_non_rutin.id_peminjaman_non_rutin, detail_peminjaman_non_rutin.id_ruangan, peminjaman_non_rutin.jam_mulai_peminjaman, peminjaman_non_rutin.jam_selesai_peminjaman, peminjaman_non_rutin.tanggal_pemakaian');
        $this->db->from('peminjaman_non_rutin');
        $this->db->join('detail_peminjaman_non_rutin', 'peminjaman_non_rutin.id_peminjaman_non_rutin = detail_peminjaman_non_rutin.id_peminjaman_non_rutin');
        $this->db->where('peminjaman_non_rutin.tanggal_pemakaian', $tanggal_pemakaian);
        $this->db->where('detail_peminjaman_non_rutin.id_ruangan', $id_ruangan);
        $this->db->where('peminjaman_non_rutin.status !=', 'tolak');
        $query = $this->db->get();
        return $query->result();
    }
}
