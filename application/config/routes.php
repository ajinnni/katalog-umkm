<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// -------------------------------------------------------
// DEFAULT — hanya satu, di atas
// -------------------------------------------------------
$route['default_controller']   = 'auth/login';
$route['404_override']         = '';
$route['translate_uri_dashes'] = TRUE; // aktifkan agar dash → underscore otomatis

// -------------------------------------------------------
// AUTH
// -------------------------------------------------------
$route['login']           = 'Auth/login';
$route['proses-login']    = 'Auth/proses_login';
$route['register']        = 'Auth/register';
$route['proses-register'] = 'Auth/proses_register';
$route['verifikasi-otp']  = 'Auth/verifikasi_otp';
$route['proses-otp']      = 'Auth/proses_otp';
$route['resend-otp']      = 'Auth/resend_otp';
$route['logout']          = 'Auth/logout';

// -------------------------------------------------------
// LANDING PAGE & SEARCH
// -------------------------------------------------------
$route['produk']          = 'Home/produk';
$route['produk/(:any)']   = 'Home/detail_produk/$1';
$route['toko/(:any)']     = 'Home/toko/$1';
$route['cart']            = 'Home/cart';
$route['checkout']        = 'Home/checkout';

// -------------------------------------------------------
// ADMIN
// -------------------------------------------------------
$route['admin']                    = 'Admin/index';
$route['admin/umkm/tambah']        = 'Admin/tambah_umkm';
$route['admin/umkm/simpan']        = 'Admin/simpan_umkm';
$route['admin/umkm/(:num)/edit']   = 'Admin/edit_umkm/$1';
$route['admin/umkm/(:num)/update'] = 'Admin/update_umkm/$1';
$route['admin/umkm/(:num)/hapus']  = 'Admin/hapus_umkm/$1';
$route['admin/umkm/(:num)/toggle'] = 'Admin/toggle_umkm/$1';
$route['admin/umkm']               = 'Admin/kelola_umkm';
$route['admin/users/tambah']       = 'Admin/tambah_user';
$route['admin/users/simpan']       = 'Admin/simpan_user';
$route['admin/users/(:num)/edit']  = 'Admin/edit_user/$1';
$route['admin/users/(:num)/update']= 'Admin/update_user/$1';
$route['admin/users/(:num)/hapus'] = 'Admin/hapus_user/$1';
$route['admin/users']              = 'Admin/kelola_users';
$route['admin/laporan']            = 'Admin/laporan';
$route['admin/laporan/export'] = 'Admin/export_laporan';

// -------------------------------------------------------
// UMKM
// -------------------------------------------------------
$route['umkm/dashboard']              = 'Umkm/dashboard';
$route['umkm/produk/tambah']          = 'Umkm/tambah_produk';
$route['umkm/produk/simpan']          = 'Umkm/simpan_produk';
$route['umkm/produk/(:num)/edit']     = 'Umkm/edit_produk/$1';
$route['umkm/produk/(:num)/update']   = 'Umkm/update_produk/$1';
$route['umkm/produk/(:num)/hapus']    = 'Umkm/hapus_produk/$1';
$route['umkm/produk']                 = 'Umkm/kelola_produk';
$route['umkm/pesanan/(:num)/status']  = 'Umkm/update_status_pesanan/$1';
$route['umkm/laporan']                = 'Umkm/laporan';
$route['umkm/daftar-toko'] = 'Umkm/daftar_toko';
$route['umkm/simpan-toko'] = 'Umkm/simpan_toko';

// -------------------------------------------------------
// USER (default fallback — taruh PALING BAWAH)
// -------------------------------------------------------
$route['user'] = 'User/index';
$route['user/riwayat'] = 'User/riwayat';