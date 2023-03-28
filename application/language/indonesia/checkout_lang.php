<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Heading 
$lang['heading_title']                  = 'Checkout';

// Text
$lang['text_cart']                      = 'Keranjang Belanja';
$lang['text_checkout_option']           = 'Opsi Checkout';
$lang['text_checkout_account']          = 'Akun &amp; Alamat Penagihan';
$lang['text_checkout_payment_address']  = 'Alamat Penagihan';
$lang['text_checkout_shipping_address'] = 'Alamat Pengiriman';
$lang['text_checkout_shipping_method']  = 'Metode Pengiriman';
$lang['text_checkout_payment_method']   = 'Metode Pembayaran';
$lang['text_checkout_confirm']          = 'Konfirmasi Pesanan';
$lang['text_modify']                    = 'Ubah &raquo;';
$lang['text_new_customer']              = 'Pelanggan Baru';
$lang['text_returning_customer']        = 'Sudah punya akun?';
$lang['text_checkout']                  = 'Pilih salah satu cara di bawah ini';
$lang['text_i_am_returning_customer']   = 'Jika sudah punya akun, silakan login';
$lang['text_register']                  = 'Daftar Akun';
$lang['text_guest']                     = 'Checkout Sebagai Tamu';
$lang['text_register_account']          = '<b>Catatan : </b>Dengan memiliki akun, kamu dapat berbelanja lebih cepat, memantau perkembangan status pesanan, dan melihat kembali pesanan-pesanan yang pernah kamu lakukan sebelumnya';
$lang['text_forgotten']                 = 'Lupa Password?';
$lang['text_your_details']              = 'Biodata';
$lang['text_your_address']              = 'Alamat Penagihan';
$lang['text_your_password']             = 'Password';
$lang['text_agree']                     = 'Dengan ini saya menyetujui <a class="colorbox" href="%s" alt="%s"><b>%s</b></a> dan <a class="colorbox" href="%s" alt="%s"><b>%s</b></a> yang berlaku.';
$lang['text_address_new']               = 'Saya ingin menggunakan alamat baru';
$lang['text_address_existing']          = 'Saya ingin menggunakan alamat yang sudah ada';
$lang['text_shipping_method']           = 'Pilih metode pengiriman favorit kamu';
$lang['text_payment_method']            = 'Pilih memilih metode pembayaran yang paling mudah menurut kamu';
$lang['text_comments']                  = 'Catatan tentang pesanan kamu';
$lang['text_male']						= 'Pria';
$lang['text_female']					= 'Wanita';

// Column
$lang['column_name']                    = 'Product Name';
$lang['column_model']                   = 'Model';
$lang['column_quantity']                = 'Quantity';
$lang['column_price']                   = 'Price';
$lang['column_total']                   = 'Total';

// Entry
$lang['entry_email_address']            = 'Alamat Email';
$lang['entry_email']                    = 'Email';
$lang['entry_password']                 = 'Password';
$lang['entry_confirm']                  = 'Ulangi Password';
$lang['entry_name']                     = 'Nama';
$lang['entry_firstname']                = 'Nama Depan';
$lang['entry_lastname']                 = 'Nama Belakang';
$lang['entry_fullname']                 = 'Nama Lengkap';
$lang['entry_gender']                   = 'Jenis Kelamin';
$lang['entry_birthday']                 = 'Tanggal Lahir';
$lang['entry_telephone']                = 'No. Telepon';
$lang['entry_handphone']                = 'No. Handphone';
$lang['entry_fax']                      = 'Fax';
$lang['entry_company']                  = 'Perusahaan';
$lang['entry_customer_group']           = 'Grup Pelanggan';
$lang['entry_company_id']               = 'SIUP';
$lang['entry_tax_id']                   = 'NPWP';
$lang['entry_address']                  = 'Alamat';
$lang['entry_postcode']                 = 'Kode Pos';
$lang['entry_city']                     = 'Kota';
$lang['entry_country']                  = 'Negara';
$lang['entry_province']                 = 'Propinsi';
$lang['entry_regency']                  = 'Kabupaten/Kota';
$lang['entry_district']                 = 'Kecamatan';
$lang['entry_village']                  = 'Kelurahan';
$lang['entry_newsletter']               = 'Saya ingin selalu berlangganan newsletter';
$lang['entry_shipping'] 	            = 'Alamat pengiriman sama dengan alamat penagihan';
$lang['entry_tax_id']                   = 'Nomor NPWP';

// Error
$lang['error_warning']                  = 'There was a problem while trying to process your order! If the problem persists please try selecting a different payment method or you can contact the store owner by <a href="%s">clicking here</a>.';
$lang['error_login']                    = '<b>Kesalahan :</b> Email atau password tidak sesuai!';
$lang['error_approved']                 = 'Warning: Your account requires approval before you can login.'; 
$lang['error_exists']                   = 'Email ini sudah terdaftar!';
$lang['error_firstname']                = 'Nama Depan harus terdiri dari 3 sampai 32 karakter!';
$lang['error_lastname']                 = 'Nama Belakang harus terdiri dari 3 sampai 32 karakter!';
$lang['error_email']                    = 'E-Mail Address does not appear to be valid!';
$lang['error_telephone']                = 'Telephone must be between 3 and 32 characters!';
$lang['error_password']                 = 'Password must be between 3 and 20 characters!';
$lang['error_confirm']                  = 'Password confirmation does not match password!';
$lang['error_company_id']               = 'Company ID required!';
$lang['error_tax_id']                   = 'Tax ID required!';
$lang['error_vat']                      = 'VAT number is invalid!';
$lang['error_address']                  = 'Alamat harus terdiri dari 3 sampai 128 karakter!';
$lang['error_city']                     = 'City must be between 2 and 128 characters!';
$lang['error_postcode']                 = 'Postcode must be between 2 and 10 characters!';
$lang['error_country']                  = 'Please select a country!';
$lang['error_zone']                     = 'Please select a region / state!';
$lang['error_agree']                    = 'Warning: You must agree to the %s!';
$lang['error_shipping']                 = 'Warning: Shipping method required!';
$lang['error_no_shipping']              = 'Warning: No Shipping options are available. Please <a href="%s">contact us</a> for assistance!';
$lang['error_payment']                  = 'Warning: Payment method required!';
$lang['error_no_payment']               = 'Warning: No Payment options are available. Please <a href="%s">contact us</a> for assistance!';