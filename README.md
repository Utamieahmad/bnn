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

Penambahan librari pdf:
1. url tutorial : https://codebriefly.com/laravel-5-export-to-pdf-laravel-dompdf/
2. update composer --> "composer update"

fixing data pada tabel sin_lookup_values
1. Lingkungan PendidikanInstitusi Swasta --> Lingkungan Pendidikan
query SELECT => SELECT * from sin_lookup_values WHERE lookup_name like '%Lingkungan PendidikanInstitusi Swasta%'
