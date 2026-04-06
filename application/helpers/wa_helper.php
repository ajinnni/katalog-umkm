<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Format nomor WhatsApp ke standar Indonesia (62xxxx)
 */
function format_wa($no_wa) {
    // Hapus semua karakter selain angka
    $no_wa = preg_replace('/[^0-9]/', '', $no_wa);

    if (!$no_wa) return null;

    // Jika diawali 0 → ubah jadi 62
    if (substr($no_wa, 0, 1) === '0') {
        $no_wa = '62' . substr($no_wa, 1);
    }

    // Jika sudah 62 biarkan
    if (substr($no_wa, 0, 2) === '62') {
        return $no_wa;
    }

    // Selain itu anggap tidak valid
    return null;
}

/**
 * Generate link WhatsApp (wa.me)
 */
function wa_link($no_wa, $pesan = '') {
    $no_wa = format_wa($no_wa);

    if (!$no_wa) return '#';

    $pesan = urlencode($pesan);

    return "https://wa.me/{$no_wa}?text={$pesan}";
}