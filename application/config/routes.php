<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = 'Home';
$route['404_override']       = '';
$route['translate_uri_dashes'] = FALSE;
 
// Auth
$route['login']              = 'Auth/login';
$route['register']           = 'Auth/register';
$route['verifikasi-otp']     = 'Auth/verifikasi_otp';
$route['logout']             = 'Auth/logout';
 
// Landing page & search
$route['produk']             = 'Home/produk';
$route['produk/(:any)']      = 'Home/detail_produk/$1';
$route['toko/(:any)']        = 'Home/toko/$1';
$route['cart']               = 'Home/cart';
$route['checkout']           = 'Home/checkout';
 
// Admin
$route['admin']                      = 'Admin/dashboard';
$route['admin/umkm/tambah']          = 'Admin/tambah_umkm';
$route['admin/umkm/simpan']          = 'Admin/simpan_umkm';
$route['admin/umkm/(:num)/edit']     = 'Admin/edit_umkm/$1';
$route['admin/umkm/(:num)/update']   = 'Admin/update_umkm/$1';
$route['admin/umkm/(:num)/hapus']    = 'Admin/hapus_umkm/$1';
$route['admin/umkm/(:num)/toggle']   = 'Admin/toggle_umkm/$1';
$route['admin/umkm']                 = 'Admin/kelola_umkm';
$route['admin/users']                = 'Admin/kelola_users';
$route['admin/laporan']              = 'Admin/laporan';
$route['admin/users/tambah']         = 'Admin/tambah_user';
$route['admin/users/simpan']         = 'Admin/simpan_user';
$route['admin/users/(:num)/edit']    = 'Admin/edit_user/$1';
$route['admin/users/(:num)/update']  = 'Admin/update_user/$1';
$route['admin/users/(:num)/hapus']   = 'Admin/hapus_user/$1';
$route['admin/users']                = 'Admin/kelola_users';
 
// UMKM
$route['umkm/dashboard']          = 'Umkm/dashboard';
$route['umkm/produk']             = 'Umkm/kelola_produk';
$route['umkm/produk/tambah']      = 'Umkm/tambah_produk';
$route['umkm/produk/simpan']      = 'Umkm/simpan_produk';
$route['umkm/produk/(:num)/edit'] = 'Umkm/edit_produk/$1';
$route['umkm/produk/(:num)/hapus']= 'Umkm/hapus_produk/$1';
$route['umkm/produk/simpan']           = 'Umkm/simpan_produk';
$route['umkm/produk/(:num)/update']    = 'Umkm/update_produk/$1';