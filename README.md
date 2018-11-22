bnn
bug database :
1. tambahkan column created_by di tabel sirena_t_pasien_assesment
2. primary di tabel sirena_t_pasien_assesment tidak auto inc dan bisa null
3. tambahkan column created_by, create_date, updated_by dan update_date berantas_razia
4. tambahkan column created_by table irtama_ptl


penambahan kolom untuk menambahkan menu deputi cegah -> aktivitas sebaran
1. tambahkan data di tabel rbac_menu_appl
2. tambahkan data di tabel rbac_role_group


Penambahan kolom untuk tabel hukerkerjasama_lainnya
1. tambahkan field kolom dengan nama updated_by type int4
2. tambahkan field kolom dengan nama updated_at type timestamp

Penambahan sequence untuk menu master data
SELECT setval('tr_media_id_seq', 30, false);
SELECT setval('settama_lookup_id_settama_lookup_seq', 100, false);

Perubahan GuzzleException:
1. 'verify'          => true, -> 'verify'          => false,


ubah query view di v_berantas_kasus_tersangka :
SELECT a.tersangka_id, a.kasus_id, a.tersangka_nama, a.tersangka_alamat, a.kode_jenis_kelamin, sex.lookup_name AS nama_jenis_kelamin, a.tersangka_tempat_lahir, a.tersangka_usia, a.kode_kelompok_usia, kelompok_usia.lookup_name AS nama_kelompok_usia, a.tersangka_tanggal_lahir, a.kode_pendidikan_akhir, pendidikan.lookup_name AS nama_pendidikan_akhir, a.kode_pekerjaan, pekerjaan.lookup_title AS nama_pekerjaan, a.kode_warga_negara, warga_negara.lookup_name AS nama_warga_negara, a.kode_peran_tersangka, tr_peran.nm_peran AS nama_peran, a.created_by, a.create_date, a.updated_by, a.update_date, a.no_identitas, a.kode_negara, negara.nm_negara AS nama_negara, a.kode_jenisidentitas, a.alamatktp_idprovinsi, a.alamatktp_idkabkota, a.alamatktp_kodepos, a.alamatdomisili, a.alamatdomisili_idprovinsi, a.alamatdomisili_idkabkota, a.alamatdomisili_kodepos, a.alamatlainnya, a.alamatlainnya_idprovinsi, a.alamatlainnya_idkabkota, a.alamatlainnya_kodepos, jenis_identitas.lookup_name AS nama_jenis_identitas, a.pasal, a.tersangka_nama_alias, a.deleted_at, pekerjaan_lengkap.nm_kerja FROM (((((((((berantas_kasus_tersangka a LEFT JOIN sin_lookup_values sex ON (((a.kode_jenis_kelamin)::text = (sex.lookup_value)::text))) LEFT JOIN sin_lookup_values kelompok_usia ON (((a.kode_kelompok_usia)::text = (kelompok_usia.lookup_value)::text))) LEFT JOIN sin_lookup_values pendidikan ON (((a.kode_pendidikan_akhir)::text = (pendidikan.lookup_value)::text))) LEFT JOIN sin_lookup_values pekerjaan ON (((a.kode_pekerjaan)::text = (pekerjaan.lookup_value)::text))) LEFT JOIN sin_lookup_values warga_negara ON (((a.kode_warga_negara)::text = (warga_negara.lookup_value)::text))) LEFT JOIN sin_lookup_values jenis_identitas ON (((a.kode_jenisidentitas)::text = (jenis_identitas.lookup_value)::text))) LEFT JOIN tr_peran ON (((a.kode_peran_tersangka)::bpchar = tr_peran.kd_peran))) LEFT JOIN tr_negara negara ON (((a.kode_negara)::bpchar = negara.kd_negara))) LEFT JOIN tr_pekerjaan pekerjaan_lengkap ON (((a.kode_pekerjaan)::text = (pekerjaan_lengkap.singkatan)::text)));

ubah di tabel tr_pekerjaan nm_kerja = seniman, singkatan = SNM