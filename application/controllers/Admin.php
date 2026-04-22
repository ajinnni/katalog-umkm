<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Auth_Controller {  // ✅ fix: ganti MY_Controller → Auth_Controller

    public function __construct() {
        parent::__construct();

        $this->load->model(['User_model', 'Umkm_model']);
        $this->load->library(['form_validation', 'upload']);

        // ✅ Guard sudah ada di Auth_Controller, tapi double check role
        if ($this->session->userdata('role') !== 'admin') {
            redirect('login');
            exit;
        }
    }

    // ✅ fix: hapus method index() yang lama, pakai yang ini aja
    public function index() {
        $data['title'] = 'Dashboard Admin';
        $data['user']  = [
            'nama'  => $this->session->userdata('nama'),
            'no_wa' => $this->session->userdata('no_wa'),
        ];

        // ✅ fix: pakai User_model biar konsisten, hindari query langsung
        $data['total_user']  = $this->User_model->count_by_role('user');
        $data['total_umkm']  = $this->User_model->count_by_role('umkm');
        $data['total_admin'] = $this->User_model->count_by_role('admin');

        // ✅ Cek dulu tabelnya ada, kalau belum ada set 0
        $data['total_produk']  = $this->db->table_exists('produk')
            ? $this->db->count_all('produk')
            : 0;
        $data['total_pesanan'] = $this->db->table_exists('pesanan')
            ? $this->db->count_all('pesanan')
            : 0;

        $this->render('admin/dashboard', $data);
    }

    // ✅ fix: hapus method dashboard() karena duplikat dengan index()

    // =========================
    // UMKM MANAGEMENT
    // =========================
    public function kelola_umkm() {
        $data['title']     = 'Kelola UMKM';
        $data['list_umkm'] = $this->Umkm_model->get_all();
        $this->render('admin/umkm/index', $data);
    }

    public function tambah_umkm() {
        $data['title']  = 'Tambah UMKM';
        $data['owners'] = $this->Umkm_model->get_available_owners();
        $this->render('admin/umkm/form', $data);
    }

    public function simpan_umkm() {
        $this->form_validation->set_rules('nama_toko', 'Nama Toko', 'required|trim');
        $this->form_validation->set_rules('user_id', 'Pemilik', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/umkm');
            return;
        }

        $foto = NULL;
        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->_upload_foto();
            if (!$foto) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('admin/umkm');
                return;
            }
        }

        $this->Umkm_model->create([
            'user_id'    => $this->input->post('user_id'),
            'nama_toko'  => $this->input->post('nama_toko', TRUE),
            'deskripsi'  => $this->input->post('deskripsi', TRUE),
            'alamat'     => $this->input->post('alamat', TRUE),
            'no_wa_toko' => format_wa($this->input->post('no_wa_toko', TRUE)),
            'foto'       => $foto,
            'is_active'  => 1,
        ]);

        $this->session->set_flashdata('success', 'UMKM berhasil ditambahkan.');
        redirect('admin/umkm');
    }

    public function edit_umkm($id) {
        $umkm = $this->Umkm_model->get($id);
        if (!$umkm) show_404();

        $data['title']  = 'Edit UMKM';
        $data['umkm']   = $umkm;
        $data['owners'] = $this->Umkm_model->get_available_owners();
        $this->render('admin/umkm/form', $data);
    }

    public function update_umkm($id) {
        $umkm = $this->Umkm_model->get($id);
        if (!$umkm) show_404();

        $this->form_validation->set_rules('nama_toko', 'Nama Toko', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/umkm/' . $id . '/edit');
            return;
        }

        $update = [
            'nama_toko'  => $this->input->post('nama_toko', TRUE),
            'deskripsi'  => $this->input->post('deskripsi', TRUE),
            'alamat'     => $this->input->post('alamat', TRUE),
            'no_wa_toko' => format_wa($this->input->post('no_wa_toko', TRUE)),
        ];

        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->_upload_foto();
            if ($foto) {
                if ($umkm->foto && file_exists(FCPATH . 'uploads/umkm/' . $umkm->foto)) {
                    unlink(FCPATH . 'uploads/umkm/' . $umkm->foto);
                }
                $update['foto'] = $foto;
            }
        }

        $this->Umkm_model->update($id, $update);
        $this->session->set_flashdata('success', 'UMKM berhasil diperbarui.');
        redirect('admin/umkm');
    }

    public function hapus_umkm($id) {
        $umkm = $this->Umkm_model->get($id);
        if (!$umkm) show_404();

        if ($umkm->foto && file_exists(FCPATH . 'uploads/umkm/' . $umkm->foto)) {
            unlink(FCPATH . 'uploads/umkm/' . $umkm->foto);
        }

        $this->Umkm_model->delete($id);
        $this->session->set_flashdata('success', 'UMKM berhasil dihapus.');
        redirect('admin/umkm');
    }

    public function toggle_umkm($id) {
        $this->Umkm_model->toggle_active($id);
        $this->session->set_flashdata('success', 'Status UMKM diperbarui.');
        redirect('admin/umkm');
    }

    // =========================
    // USERS MANAGEMENT
    // =========================
    public function kelola_users() {
        $data['title'] = 'Data Pengguna';
        $data['users'] = $this->User_model->get_all();
        $this->render('admin/users/index', $data);
    }

    public function tambah_user() {
        $data['title'] = 'Tambah Pengguna';
        $this->render('admin/users/form', $data);
    }

    public function simpan_user() {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_wa', 'No WA', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,umkm,user]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/users/tambah');
            return;
        }

        $no_wa = format_wa($this->input->post('no_wa', TRUE));

        if ($this->User_model->get_by_wa($no_wa)) {
            $this->session->set_flashdata('error', 'Nomor sudah terdaftar.');
            redirect('admin/users/tambah');
            return;
        }

        $this->User_model->create([
            'nama'        => $this->input->post('nama', TRUE),
            'no_wa'       => $no_wa,
            'role'        => $this->input->post('role', TRUE),
            'is_verified' => 1,
        ]);

        $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan.');
        redirect('admin/users');
    }

    public function hapus_user($id) {
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Tidak bisa hapus akun sendiri.');
            redirect('admin/users');
            return;
        }

        $this->User_model->delete($id);
        $this->session->set_flashdata('success', 'User berhasil dihapus.');
        redirect('admin/users');
    }

    // =========================
    // PRIVATE UPLOAD
    // =========================
    private function _upload_foto() {
        $config['upload_path']   = './uploads/umkm/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = time() . '_' . $_FILES['foto']['name'];
        $config['overwrite']     = true;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('foto')) {
            return $this->upload->data('file_name');
        }

        return false;
    }

    public function laporan() {
        $this->load->model(['Pesanan_model', 'Umkm_model']);
 
        // ── Ringkasan pesanan ──
        $total_pesanan      = $this->db->count_all('pesanan');
        $total_omzet        = (float) $this->db->select_sum('total_harga')->where('status', 'selesai')->get('pesanan')->row()->total_harga;
        $pesanan_bulan_ini  = $this->db->where('MONTH(created_at)', date('m'))->where('YEAR(created_at)', date('Y'))->count_all_results('pesanan');
 
        // ── Status pesanan ──
        $status_pesanan = $this->db
            ->select('status, COUNT(*) as jumlah')
            ->group_by('status')
            ->get('pesanan')->result();
 
        // ── Pesanan per bulan tahun ini ──
        $pesanan_per_bulan_raw = $this->db
            ->select("DATE_FORMAT(created_at, '%b') as bulan, MONTH(created_at) as bln_num, COUNT(*) as jumlah, SUM(CASE WHEN status='selesai' THEN total_harga ELSE 0 END) as omzet")
            ->where('YEAR(created_at)', date('Y'))
            ->group_by('MONTH(created_at)')
            ->order_by('MONTH(created_at)', 'ASC')
            ->get('pesanan')->result();
 
        // Pastikan 12 bulan semua ada
        $bulan_labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $bulan_map    = [];
        foreach ($pesanan_per_bulan_raw as $b) {
            $bulan_map[(int)$b->bln_num] = $b;
        }
        $pesanan_per_bulan = [];
        for ($m = 1; $m <= 12; $m++) {
            $pesanan_per_bulan[] = (object)[
                'bulan'  => $bulan_labels[$m - 1],
                'jumlah' => isset($bulan_map[$m]) ? (int)$bulan_map[$m]->jumlah : 0,
                'omzet'  => isset($bulan_map[$m]) ? (float)$bulan_map[$m]->omzet : 0,
            ];
        }
 
        // ── UMKM ──
        $total_umkm_aktif   = $this->db->where('is_active', 1)->count_all_results('umkm');
        $total_umkm_pending = $this->db->where('is_active', 0)->count_all_results('umkm');
        $umkm_pending       = $this->Umkm_model->get_pending();
 
        // ── Top 5 UMKM by omzet ──
        $top_umkm = $this->db
            ->select('umkm.id, umkm.nama_toko, users.nama as nama_pemilik, COUNT(pesanan.id) as total_pesanan, SUM(pesanan.total_harga) as total_omzet')
            ->from('pesanan')
            ->join('umkm',  'umkm.id = pesanan.umkm_id',   'left')
            ->join('users', 'users.id = umkm.user_id',     'left')
            ->where('pesanan.status', 'selesai')
            ->group_by('pesanan.umkm_id')
            ->order_by('total_omzet', 'DESC')
            ->limit(5)
            ->get()->result();
 
        // ── Top 5 produk terlaris ──
        $top_produk = $this->db
            ->select('detail_pesanan.nama_produk, detail_pesanan.harga_satuan, SUM(detail_pesanan.qty) as total_terjual, umkm.nama_toko')
            ->from('detail_pesanan')
            ->join('pesanan', 'pesanan.id = detail_pesanan.pesanan_id', 'left')
            ->join('umkm',    'umkm.id = pesanan.umkm_id',             'left')
            ->where('pesanan.status', 'selesai')
            ->group_by('detail_pesanan.nama_produk')
            ->order_by('total_terjual', 'DESC')
            ->limit(5)
            ->get()->result();
 
        // ── Produk ──
        $total_produk       = $this->db->count_all('produk');
        $total_produk_aktif = $this->db->where('is_active', 1)->count_all_results('produk');
 
        // ── User ──
        $total_user       = $this->User_model->count_by_role('user');
        $total_umkm_user  = $this->User_model->count_by_role('umkm');
        $total_admin      = $this->User_model->count_by_role('admin');
        $total_semua_user = $total_user + $total_umkm_user + $total_admin;
        $total_user_pct   = $total_semua_user > 0 ? round(($total_user  / $total_semua_user) * 100) : 0;
        $total_umkm_pct   = $total_semua_user > 0 ? round(($total_umkm_user / $total_semua_user) * 100) : 0;
        $total_admin_pct  = $total_semua_user > 0 ? round(($total_admin / $total_semua_user) * 100) : 0;
        $user_baru_bulan_ini = $this->db
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->count_all_results('users');
 
        // ── Pesanan terbaru ──
        $pesanan_terbaru = $this->db
            ->select('pesanan.*, umkm.nama_toko')
            ->from('pesanan')
            ->join('umkm', 'umkm.id = pesanan.umkm_id', 'left')
            ->order_by('pesanan.created_at', 'DESC')
            ->limit(20)
            ->get()->result();
 
        $this->render('admin/laporan', [
            'title'              => 'Laporan',
            // pesanan
            'total_pesanan'      => $total_pesanan,
            'total_omzet'        => $total_omzet,
            'pesanan_bulan_ini'  => $pesanan_bulan_ini,
            'status_pesanan'     => $status_pesanan,
            'pesanan_per_bulan'  => $pesanan_per_bulan,
            'pesanan_terbaru'    => $pesanan_terbaru,
            // umkm
            'total_umkm_aktif'   => $total_umkm_aktif,
            'total_umkm_pending' => $total_umkm_pending,
            'umkm_pending'       => $umkm_pending,
            'top_umkm'           => $top_umkm,
            // produk
            'total_produk'       => $total_produk,
            'total_produk_aktif' => $total_produk_aktif,
            'top_produk'         => $top_produk,
            // user
            'total_user'         => $total_user,
            'total_umkm_user'    => $total_umkm_user,
            'total_admin'        => $total_admin,
            'total_semua_user'   => $total_semua_user,
            'total_user_pct'     => $total_user_pct,
            'total_umkm_pct'     => $total_umkm_pct,
            'total_admin_pct'    => $total_admin_pct,
            'user_baru_bulan_ini'=> $user_baru_bulan_ini,
        ]);
    }
 
    // =========================
    // EXPORT CSV
    // =========================
    public function export_laporan() {
        $this->load->model('Pesanan_model');
 
        $pesanan = $this->db
            ->select('pesanan.kode_pesanan, pesanan.nama_pemesan, pesanan.no_wa_pemesan, pesanan.alamat_pengiriman, pesanan.total_harga, pesanan.status, pesanan.created_at, umkm.nama_toko')
            ->from('pesanan')
            ->join('umkm', 'umkm.id = pesanan.umkm_id', 'left')
            ->order_by('pesanan.created_at', 'DESC')
            ->get()->result();
 
        $filename = 'laporan_pesanan_' . date('Ymd_His') . '.csv';
 
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
 
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
 
        fputcsv($output, ['Kode Pesanan', 'Nama Pemesan', 'No WA', 'Alamat', 'Total Harga', 'Status', 'Nama Toko', 'Tanggal']);
 
        foreach ($pesanan as $p) {
            fputcsv($output, [
                $p->kode_pesanan,
                $p->nama_pemesan,
                $p->no_wa_pemesan,
                $p->alamat_pengiriman,
                $p->total_harga,
                $p->status,
                $p->nama_toko ?? '-',
                date('d/m/Y H:i', strtotime($p->created_at)),
            ]);
        }
 
        fclose($output);
        exit;
    }
}