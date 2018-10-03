<?php

return [
	 /*
    |--------------------------------------------------------------------------
    |	Puslitdatin : Permintaan Data Penelitian BNN
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */
	'kode_carapermintaan'=>[
		'datang_langsung'=>'Datang Langsung',
		'melalui_surat'=>'Melalui Surat',
		'melalui_email'=>'Melalui Email'
	],

	'kode_tujuan' => [
		'skripsi' => 'Skripsi',
		'penyuluhan_sosialisasi' => 'Penyuluhan/Sosialisasi',
		'tesis' => 'Tesis',
		'laporan_nasional' => 'Laporan Nasional',
		'desertasi' => 'Desertasi',
		'laporan_internasional' => 'Laporan Insternasional',
		'propinsi' => 'Propinsi',
		'lainnya' => 'Lainnya',
	],

	'bentuk_dokumen'=>[
		'buku' => "Buku",
		'soft_copy' => "Soft Copy",
		'hard_copy' => "Hard Copy",
	],
	 /*
    |--------------------------------------------------------------------------
    |	Puslitdatin : Contact Center
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */
	'kode_klarifikasi'=> [
		'pemberantasan'=>[
			'pemberantasan_bandar_narkoba'=>'Pemberantasan Bandar Narkoba',
			'pemberantasan_gudang'=>'Pemberantasan Gudang',
			'pemberantasan_kurir'=>'Pemberantasan Kurir',
			'pemberantasan_pabrik'=>'Pemberantasan Pabrik',
			'pemberantasan_pemakai_narkoba'=>'Pemberantasan Pemakai Narkoba',
			'pemberantasan_pengedar_narkoba'=>'Pemberantasan Pengedar Narkoba',
			'pemberantasan_peredaran_narkoba'=>'Pemberantasan Peredaran Narkoba',
			'pemberantasan_pesta_narkoba'=>'Pemberantasan Pesta Narkoba',
			'pemberantasan_tidak_diketahui'=>'Pemberantasan Tidak Diketahui',
			'pemberantasan_transaksi_diketahui'=>'Pemberantasan Transaksi Diketahui'
		],
		'data_informasi'=>[
			'data_informasi_lain' => 'Data Informasi Lain-Lain',
			'data_informasi_datin' => 'Data Informasi Meminta Disambungkan ke DATIN untuk meminta info',
			'data_informasi_data' => 'Data Informasi Data Penggunaan Narkoba',
		],
		'dumas_ittama'=>[
			'dumas_ittama' => 'DUMAS Ittama Infomasi Anggota BNN yang menyalahi wewenang '
		],
		'humas'=>[
			'humas_awancara' => 'Humas Wawancara',
            'humas_kerjasama_BNN' => 'Humas ngin Bekerja sama dengan BNN',
            'humas_lain' => 'Humas Lain-lain',
            'humas_informasi' => 'Humas Meminta Informasi tentang Majalah Sinar'
		],
		'informasi_umum'=>[
			'iu_partisipasi_dengan_bnn' => 'Informasi Umum Berpartisipasi Dengan BNN',
            'iu_konfirmasi_undangan' => 'Informasi Umum Konfirmasi Undangan',
            'iu_konsultasi' => 'Informasi Umum Konsultasi',
            'iu_penegak_umum' => 'Informasi Umum Informasi Tentang Penegak Hukum',
            'iu_tes_narkoba' => 'Informasi Umum Membuat Surat bebas narkoba & Biaya Tes Narkoba',
            'iu_satker' => 'Informasi Umum Disambungkan ke Satker Terkait',
            'iu_jam_besuk' => 'Informasi Umum Informasi Jam Besuk Tahanan',
            'iu_kontak' => 'Informasi Umum Alamat Kontak BNN, BNNP dan BNNK',
            'iu_penerimaan_cpns' => 'Informasi Umum Penerimaan CPNS',
            'iu_kritik_saran' => 'Informasi Umum Saran, Kritik dan Apresiasi Syarat Pelaporan',
            'iu_website' => 'Informasi Umum Tidak dapat Mengakses Website',
            'iu_kepuasan_masyarakat' => 'Informasi Umum Tingkat Kepuasan Masyarakat',
            'iu_lain' => 'Informasi Umum Lain-lain'
		],
		'pencegahan'=>[
			'pencegahan_narkoba' => 'Pencegahan Informasi Tentang Pencegahan Narkoba',
            'pencegahan_penyuluhan_narkoba' => 'Pencegahan Informasi Tentang Penuluhan Narkoba',
            'pencegahan_relawan_anti_narkoba' => 'Pencegahan Ingin Menjadi Relawan Anti Narkoba',
            'pencegahan_penyuluhan_narkoba' => 'Pencegahan Meminta Media Untuk Penyuluhan Narkoba',
            'pencegahan_narasumber' => 'Pencegahan Meminta Narasumber Pencegahan Narkoba',
            'pencegahan_lain' => 'Pencegahan Lain-lain'
		],

		'rehabilitasi'=>[
			'rehab_informasi_biaya' => 'Rehabilitasi Informasi Biaya Rehabilitasi',
            'rehab_informasi_ipwl' => 'Rehabilitasi Informasi IPWL',
            'rehab_persyaratan' => 'Rehabilitasi Persyaratan Rehabilitasi',
            'rehab_tempat_rehabilitasi' => 'Rehabilitasi Tempat Rehabilitasi',
            'rehab_konsultasi' => 'Rehabilitasi Konsultasi Pemakai/Rehabilitasi',
            'rehab_kontak' => 'Rehabilitasi Kontak Rehabilitasi',
            'rehab_pengidap_hiv' => 'Rehabilitasi Info Pengidap HIV',
            'rehab_permintaan' => 'Rehabilitasi Permintaan Rehabilitasi',
		]
	],

	'label_kode_klarifikasi'=>[
		'pemberantasan'=>'Pemberantasan',
		'data_informasi'=>'Data Informasi',
		'dumas_ittama'=> 'DUMAS Ittama',
		'humas'=> 'Humas',
		'informasi_umum' => 'Informasi Umum',
		'pencegahan' => 'Pencegahan',
		'rehabilitasi' => 'Rehabilitasi'

	],
	'media_contact'=>[
		'Datang Langsung' => 'Datang Langsung',
        'SMS' => 'SMS',
        'Whatsapp' => 'Whatsapp',
        'Call' => 'Call',
        'Email' => 'Email',
        'BBM' => 'BBM',
        'Facebook' => 'Facebook',
        'Twitter' => 'Twitter',
        'VoiceMail' => 'Voice Mail'
	],

	 /*
    |--------------------------------------------------------------------------
    |	 Balai Laboratorium Narkoba : Pengujian Bahan Sediaan, Spesimen Biologi dan Toksikologi
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */
    'jenis_kasus'=>[
    	'Penyalahguna' => 'Penyalahguna',
    	'Peredaran Gelap' => 'Peredaran Gelap'
    ],

    'golongan'=>[
    	'3a'=> 'Penata Muda (III/a)',
    	'3b'=> 'Penata Muda Tk.I (III/b)',
    	'3c'=> 'Penata (III/c)',
    	'4a'=> 'Pembina (IV/a)',
    	'4b'=> 'Pembina Tk. I (IV/b)',
    	'4d'=> 'Pembina Utama Madya (IV/d)',
    	'4e'=> 'Pembina Utama (IV/e)'
    ],

    'jenis_barang_bukti' => [
 		'01' => 'URINE',
        '02' => 'DARAH',
        '03' => 'RAMBUT',
        '04' => 'DAUN',
        '05' => 'BATANG',
        '06' => 'BIJI',
        '07' => 'CAIRAN',
        '08' => 'PADATAN',
        '09' => 'RAMBUT',
        '10' => 'DAUN',
        '11' => 'BATANG',
        '12' => 'BIJI',
        '13' => 'CAIRAN',
        '14' => 'KAPSUL',
        '15' => 'KRISTAL',
        '16' => 'TABLET',
        '17' => 'SERBUK',
        '18' => 'SEDOTAN',
        '19' => 'LAIN2',
    ],
    'satuan' => [
        'Butir' => 'Butir',
        'Bungkus' => 'Bungkus',
        'Buah' => 'Buah',
        'Dll' => 'Dll',
    ],
    'laporan_hasil' => [
        'Mengandung MDMA' => 'Mengandung MDMA',
        'Mengandung MA' => 'Mengandung MA',
        'Dll' => 'Dll'
    ],
     /*
    |--------------------------------------------------------------------------
    |    DAYAMAS
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */
    'kepemilikan_tanah' => [
        'Milik_Pribadi' =>'Milik Pribadi',
        'Milik_Orang_Lain' =>'Milik Orang Lain',
        'Milik_Pemda' =>'Milik Pemda',
    ],
    'lahan_komoditi' => [
        'Cabe' => 'Cabe',
        'Kunyit' => 'Kunyit',
        'Jabon' => 'Jabon',
        'Kopi' => 'Kopi',
        'Coklat' => 'Coklat',
        'Ternak/Ikan' => 'Ternak/Ikan',
    ],
    'penghasilan_kotor' => [
        'penghasilan_01' => 'Rp. 1.000.000 - Rp. 1.900.000',
        'penghasilan_02' => 'Rp. 2.000.000 - Rp. 2.900.000',
        'penghasilan_03' => 'Rp. 3.000.000 - Rp. 3.900.000',
        'penghasilan_04' => 'Rp. 4.000.000 - Rp. 4.900.000',
        'penghasilan_05' => 'Rp. 5.000.000 - Rp. 5.900.000',
        'penghasilan_06' => 'Lebih dari Rp. 6.000.000'
    ],
    'lahan_profesi' => [
        'Penanam' => 'Penanam',
        'Pemanen' => 'Pemanen',
        'Penampung' => 'Penampung',
        'Pengedar' => 'Pengedar',
        'Penghubung' => 'Penghubung',
    ],
    'penyelenggara' => [
        'BNN' => 'BNN',
        'BNNP' => 'BNNP',
        'BNNK' => 'BNNK',
        'Pemda' => 'Pemda',
        'Polri' => 'Polri',
        'BUMN' => 'BUMN',
        'Swasta' => 'Swasta',
        'LSM' => 'LSM'
    ],
    'profesi_pelatihan' => [
        'Menjahit' => 'Menjahit',
        'Salon' => 'Salon',
        'Security' => 'Security',
        'Usaha_Kue' => 'Usaha Kue',
        'Service_HP' => 'Service HP',
        'Sablon' => 'Sablon',
        'Kerajinan_Asesoris' => 'Kerajinan Asesoris',
        'Service_Laptop' => 'Service Laptop',
        'Service_Elektronik' => 'Service Elektronik',
        'Bengkel' => 'Bengkel',
        'Service_AC' => 'Service AC',
        'Merangkai_Bunga' => 'Merangkai Bunga',
        'Kuliner' => 'Kuliner',
        'Kain_Perca' => 'Kain Perca',
        'Handy_Craft' => 'Handy Craft',
    ],

    'jenis_geografis' => [
        'Pegunungan' => 'Pegunungan',
        'Pedesaan' => 'Pedesaan',
        'Pesisir' => 'Pesisir',
        'Perkotaan' => 'Perkotaan',
        'Perbatasan_Antar_Negara' => 'Perbatasan Antar Negara',
    ],
    'kriminalitas' => [
        'Perampokan' => 'Perampokan',
        'Pembunuhan' => 'Pembunuhan',
        'Perjudian' => 'Perjudian',
        'Perkosaan' => 'Perkosaan',
        'Curanmor' => 'Curanmor',
        'Tawuran' => 'Tawuran',
        'Pengedar_Narkoba' => 'Pengedar Narkoba',
        'Curas_Curat' => 'Curas/Curat',
        'KDRT' => 'KDRT'
    ],
    'jumlah_tersangka' => [
        '5-10_Orang' => '5-10 Orang',
        '11-20_Orang' => '11-20 Orang',
        '21-30_Orang' => '21-30 Orang',
        'lebih_31_Orang' => '> 31 Orang',
    ],
    'monev_profesi' => [
        'Pengedar/Kuris' => 'Pengedar/Kuris',
        'Pengguna' => 'Pengguna',
        'Penjual' => 'Penjual',
        'Pemasok' => 'Pemasok',
        'Penampung' => 'Penampung',
        'Penghubung' => 'Penghubung',
    ],
    'instansi' => [
        'INSTITUSI_PEMERINTAH' => 'Institusi Pemerintah',
        'INSTITUSI_SWASTA' => 'Institusi Swasta',
        'LINGKUNGAN_PENDIDIKAN' => 'Lingkungan Pendidikan',
        'LINGKUNGAN_MASYARAKAT' => 'Lingkungan Masyarakat'
    ],
    'bentuk_kegiatan' => [
        'Pendampingan' => 'Pendampingan',
        'Pelatihan' => 'Pelatihan',
        'Pembinaan' => 'Pembinaan',
        'Pemodalan' => 'Pemodalan',
        'Bantuan_CSR' => 'Bantuan CSR',
        'Bantuan_Jasa' => 'Bantuan Jasa',
        'Bantuan_Barang' => 'Bantuan Barang',
        'Penjangkauan_Pecandu' => 'Penjangkauan Pecandu',
        'Audiensi' => 'Audiensi',
    ],
    'pangkat' => [
        '3a' => 'Penata Muda (III/a)',
        '3b' => 'Penata Muda Tk.I (III/b)',
        '3c' => 'Penata (III/c)',
        '3d' => 'Penata Tk. I (III/d)',
        '4a' => 'Pembina (IV/a)',
        '4b' => 'Pembina Tk. I (IV/b)',
        '4d' => 'Pembina Utama Madya (IV/c)',
        '4d' => 'Pembina Utama Madya (IV/d)',
        '4e' => 'Pembina Utama (IV/e)',
    ],
    'sasaran'=>[
        'pemerintah' => 'Pemerintah',
        'pendidikan' => 'Pendidikan',
        'swasta' => 'Swasta',
        'masyarakat' => 'Masyarakat'
    ],
    'sumber_anggaran'=>[
        'dipa'=> 'Dipa',
        'non_dipa'=> 'Non Dipa',
    ],
    'alv_sasaran'=>[
        'pemerintah' => 'Pemerintah',
        'swasta' => 'Swasta',
        'masyarakat' => 'Masyarakat'
    ],
    'sasaran_supervisi'=>[
        'masyarakat' => 'Masyarakat',
        'swasta' => 'Swasta',
        'pendidikan' => 'Pendidikan'
    ],
    'hasil_penilaian'=>[
        'tidak_mandiri'=>'Tidak Mandiri', 
        'kurang_mandiri'=> 'Kurang Mandiri',
        'mandiri'=>'Mandiri', 
        'sangat_mandiri'=>'Sangat Mandiri'
    ],
    'jenis_lokasi'=>[
        'kota' => 'Perkotaan',
        'desa' => 'Pedesaan'
    ],
     /*
    |--------------------------------------------------------------------------
    |    REHABILITASI
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */
    'kode_direktorat'=>[
        'plrip'=>'PLRIP',
        'plrkm'=>'PLRKM',
        'pasca'=>'Pasca Rehabilitasi'
    ],
    'penilaian_lembaga'=>[
        'nilai_04_a'=>[
            1 => 'Tidak Ada',
            2 => 'Ada Tapi Tidak Lengkap',
            3 => 'Ada dan Lengkap',
        ],
        'nilai_04_b'=>[
            1 => 'Tidak Ada',
            2 => 'Ada tapi tidak rutin',
            3 => 'Ada dan rutin',
        ],
        'nilai_04_c'=>[
            1 => 'Tidak Ada',
            2 => 'Ada tapi tidak teratur',
            3 => 'Ada dan teratur',
        ],
        'nilai_05_a'=>[
            1 => 'Tidak Ada',
            2 => 'Ada, Tidak Terlatih',
            3 => 'Ada dan Terlatih',
        ],
        'nilai_05_b'=>[
            1 => 'Ada, jml kurang',
            2 => 'Ada, jml cukup, kurang terlatih',
            3 => 'Ada, jml cukup, terlatih',        
        ],
        'nilai_05_c'=>[
            1 => 'Tidak ada',
            2 => 'Ada, merangkap',
            3 => 'Ada, khusus',        
        ],
        'nilai_05_d'=>[
            1 => 'Tidak ada',
            2 => 'Ya, masih dalam proses',
            3 => 'Ya, sudah ada',       
        ],
        'nilai_05_e'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tidak terlatih',
            3 => 'Ada, terlatih',      
        ],
        'nilai_05_f'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tidak terlatih',
            3 => 'Ada, terlatih'       
        ],
        'nilai_06_a'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak diperbaharui',
            3 => 'Ada dan selalu diperbaharui',      
        ],
        'nilai_06_b'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak lengkap',
            3 => 'Ada dan lengkap',      
        ],
        'nilai_06_c'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak lengkap',
            3 => 'Ada dan lengkap',     
        ],
        'nilai_06_d'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak diisi lengkap',
            3 => 'Ada dan diisi lengkap',    
        ],
        'nilai_06_e'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak lengkap',
            3 => 'Ada dan lengkap',   
        ],
        'nilai_06_f'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak lengkap',
            3 => 'Ada dan lengkap', 
        ],
        'nilai_06_g'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi penyajian tidak sesuai',
            3 => 'Ada dan penyajian sesuai',
        ],
        'nilai_07_a'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak terjadwal',
            3 => 'Ada, Terjadwal, Ada laporan kegiatan',
        ],
        'nilai_07_b'=>[
            1 => 'Tidak ada atau tidak tertulis',
            2 => 'Ada tetapi tidak diperbaharui',
            3 => 'Ada dan diperbaharui sesuai perkembangan klien',
        ],
        'nilai_07_c'=>[
            1 => 'Tidak dilaksanakan',
            2 => 'Dilaksanakan, tetapi hanya saat awal masuk program',
            3 => 'Dilaksanakan, pada seluruh tahapan perkembangan klien: saat dan akhir program',
        ],
        'nilai_07_d'=>[
            1 => 'Tidak ada sama sekali',
            2 => 'Ada, tetapi hanya di awal program',
            3 => 'Ada sesuai perkembangan dan kebutuhan klien',
        ],
        'nilai_07_e'=>[
            1 => 'Tidak Ada',
            2 => 'Ada, tetapi tidak terjadwal',
            3 => 'Ada, terjadwal, Ada laporan kegiatan',
        ],
        'nilai_07_f'=>[
            1 => 'Tidak ada sama sekali',
            2 => 'Ada, tetapi bersifat insidentil',
            3 => 'Ada, sesuai rencana rawatan dan ada laporan hasil',
        ],
        'nilai_07_g'=>[
            1 => 'Tidak ada sama sekali',
            2 => 'Ada, tetapi bersifat insidentil',
            3 => 'Ada, sesuai rencana rawatan dan ada laporan hasil',
        ],
        'nilai_07_h'=>[
            1 => 'Tidak ada sama sekali',
            2 => 'Ada, tetapi bersifat insidentil',
            3 => 'Ada, sesuai rencana rawatan dan ada laporan hasil',
        ],
        'nilai_07_i'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tanpa dokumen perjanjian kerjasama',
            3 => 'Ada, dilengkapi dengan dokumen perjanjian kerjasama',
        ],
        'nilai_08_a'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak terjadwal',
            3 => 'Ada, Terjadwal, Ada laporan kegiatan',
        ],
        'nilai_08_b'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak rutin',
            3 => 'Ada dan rutin',
        ],
        'nilai_08_c'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi fasilitas pendukung kurang',
            3 => 'Ada dan didukung fasilitas yang memadai',
        ],
        'nilai_08_d'=>[
            1 => 'Tidak dilaksanakan',
            2 => 'Dilaksanakan tidak sesuai jadwal',
            3 => 'Dilaksanakan sesuai jadwal',
        ],
        'nilai_09_a'=>[
            1 => 'Tidak Ada',
            2 => 'Ada, tetapi merangkap digunakan untuk kegiatan lain',
            3 => 'Ada dan khusus',
        ],
        'nilai_09_b'=>[
            1 => 'Tidak Ada',
            2 => 'Ada, tetapi tidak bersih dan tidak dilengkapi dengan peralatan mandi',
            3 => 'Ada, bersih dan dilengkapi peralatan mandi',
        ],
        'nilai_09_c'=>[
            1 => 'Tidak Ada',
            2 => 'Ada tetapi tidak lengkap (hanya sebagian peralatan kesehatan)',
            3 => 'Lengkap',
        ],
        'nilai_09_d'=>[
            1 => 'Tidak ada yang memenuhi ',
            2 => 'Ada tetapi tidak semua  ruangan memenuhi standar kesehatan',
            3 => 'Ada dan semua ruangan memenuhi standar kesehatan',
        ],
        'nilai_10_a'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],  
        'nilai_10_b'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],  
        'nilai_10_c'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi peralatan kesehatan tidak lengkap',
            3 => 'Ada, dan dilengkapi dengan peralatan kesehatan',
        ], 
        'nilai_10_d'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],
        'nilai_10_e'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],  
        'nilai_10_f'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],   
        'nilai_10_g'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],  
        'nilai_10_h'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ], 
        'nilai_10_i'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],  
        'nilai_10_j'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],   
        'nilai_10_k'=>[
            1 => 'Tidak ada',
            2 => 'Ada, tetapi tidak sesuai dan tidak optimal',
            3 => 'Ada, sesuai dan optimal',
        ],  
        'nilai_11_a'=>[
            1 => 'Tidak ada',
            2 => 'Ada tetapi tidak berkala',
            3 => 'Ada dan berkala',
        ],  
        'nilai_11_b'=>[
            1 => 'Tidak dilaksanakan',
            2 => 'Dilaksanakan, tetapi tidak sesuai dengan proses',
            3 => 'Dilaksanakan dan sesuai dengan proses',
        ],
        'nilai_11_c'=>[
            1 => 'Tidak ada',
            2 => 'Ada tetapi tidak berkala',
            3 => 'Ada dan berkala',
        ], 
        'nilai_11_d'=>[
            1 => 'Tidak ada',
            2 => 'Ada tetapi tidak berkala',
            3 => 'Ada dan berkala',
        ],  
        'nilai_11_d'=>[
            1 => 'Tidak ada',
            2 => 'Ada tetapi tidak berkala',
            3 => 'Ada dan berkala',
        ],  
        'nilai_11_e'=>[
            1 => 'Tidak ada',
            2 => 'Ada tetapi tidak selalu dilaksanakan',
            3 => 'Ada dan selalu dilaksanakan',
        ] 
    ],
    'jenis_kelamin'=>[
        'L' => 'Laki-Laki',
        'P'=>'Perempuan',
        ''=>''
    ],

     /*
    |--------------------------------------------------------------------------
    |   IRTAMA : IRTAMA
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'hasil_riktu' =>[
        'Y'=>'Terbukti',
        'N'=>'Tidak Terbukti'
     ],
     /*
    |--------------------------------------------------------------------------
    |   Balai Diklat : Penambahan jenis diklat
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */    

    'jenis_diklat'=>[
        'teknis' => 'Teknis',
        'fungsional' => 'Fungsional',
        'struktural' => 'Struktural'
    ], 
     /*
    |--------------------------------------------------------------------------
    |  Balai Besar
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */
    'jenis_instansi' =>  [
        'bnn' => 'BNN',
        'ins_pemerintah' => 'Institusi Pemerintah',
        'ins_swasta' => 'Institusi Swasta',
        'ling_pendidikan' => 'Lingkungan Pendidikan',
        'ling_masyarakat' => 'Lingkungan Masyarakat'
    ],

    'jenis_kegiatan' => [
        'penelitian' => 'Penelitian',
        'residential' => 'Residential',
        'kunjungan' => 'Kunjungan'
    ],
    'jenis_kegiatan_antinarkoba'=>[
        'workshop' => 'Workshop',
        'tot' => 'Training on Trainer (TOT)'
    ],
     /*
    |--------------------------------------------------------------------------
    |  Berantas
    |--------------------------------------------------------------------------
    |
    */
    'pendidikan_akhir'=>[
        'SD' => 'SD',
        'SLTP' => 'SLTP',
        'SLTA' => 'SLTA',
        'PT' => 'Perguruan Tinggi',
        'PTSKL' => 'Perguruan Tinggi',
        'PTSKL' => 'Putus Sekolah',
        'TDSKL' => 'Tidak Sekolah',
    ],
    'kode_pekerjaan' => [
        'TNI' => 'TNI',
        'TANI' => 'TANI',
        'PNS' => 'PNS',
        'SWT' => 'Swasta',
        'WST' => 'Wiraswasta',
        'MHS' => 'Mahasiswa',
        'BRH' => 'Buruh',
        'PNG' => 'Pengangguran',
        'POL' => 'Polisi',
        'PLJ' => 'Pelajar'
    ],
    'kode_peran_tersangka'=>[
        '1'=> 'Produksi',
        '2'=> 'Distribusi',
        '3'=> 'Kultivasi',
        '4'=> 'Konsumsi'
    ],
    'kode_temuan'=>[
        'puslitdatin' => 'Kode 1',
        'sekretariat_utama' => 'Kode 2',
        'biro_umum' => 'Kode 3',
        'sarana' => 'Kode 4'
    ],
    'kode_anggaran'=>[
        'DIPA' => 'DIPA',
        'NONDIPA' => 'NON DIPA',
    ],
    'monev_sasaran'=>[
        'LINGKUNGAN_MASYARAKAT' => 'Lingkungan Masyarakat',
        'INSTITUSI_SWASTA' => 'Institusi Swasta',
        'LINGKUNGAN_PENDIDIKAN' => 'Lingkungan Pendidikan',
    ],
    'kd_jnswilayah' =>['1'=>'BNNP','2'=>'BNNK','6'=>'BNNK','5'=> 'BNN'],
    'kelompok_survey' => [
        'rumah_tangga'=> 'Rumah Tangga',
        'pelajar'=>'Pelajar / Mahasiswa',
        'pekerja'=> 'Pekerja'
    ],
    'kategori_penyalahgunaan' => [
        'coba_pakai'=> 'Coba Pakai',
        'teratur_pakai'=>'Teratur Pakai',
        'pecandu_suntik'=> 'Pecandu Suntik',
        'pecandu_nonsuntik'=> 'Pecandu Non Suntik'
    ],
    'jenis_kegiatan' => [
        'pemasangan_jaringan'=> 'Pemasangan Jaringan LAN Baru',
        'penanganan_gangguan'=> 'Penanganan Gangguan LAN dan WiFi',
    ],
    'jenis_jaringan' => [
        'lan'=> 'LAN',
        'wifi'=> 'Wifi',
    ],
];