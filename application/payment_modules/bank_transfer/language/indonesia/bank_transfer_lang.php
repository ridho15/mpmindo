<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// heading
$lang['heading_title'] = 'Transfer Bank';
$lang['heading_subtitle'] = 'Modul pembayaran dengan transfer bank';

// button
$lang['button_back'] = 'Kembali';
$lang['button_process'] = 'Selesai';

// label
$lang['label_title'] = 'Judul';
$lang['label_title_help'] = 'Judul akan ditampilkan di halaman pembayaran dan juga email tagihan ke customer';
$lang['label_instruction'] = 'Instruksi Pembayaran';
$lang['label_instruction_help'] = 'Instruksi yang akan ditampilkan di halaman pembayaran dan juga email tagihan ke customer';
$lang['label_unique_code'] = 'Kode Unik';
$lang['label_unique_code_help'] = 'Generate 3 digit kode unik di nominal yang ditagihkan ke customer';
$lang['label_confirmation'] = 'Konfirmasi Pembayaran';
$lang['label_confirmation_help'] = 'Aktifkan juga fitur form konfirmasi pembayaran';
$lang['label_bank_account'] = 'Rekening Bank';
$lang['label_bank_account_help'] = 'Pilih rekening mana saja yang dapat digunakan untuk opsi pilihan ke customer. Anda dapat menambah atau memperbarui rekening bank <a href="'.admin_url('bank_accounts').'">di sini</a></span>';

// error
$lang['error_process'] = 'Kesalahan ketika melakukan pembayaran. Silakan hubungi Customer Service!';