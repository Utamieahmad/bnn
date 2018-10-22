<?php

/**
    * @SWG\Get(
    *   path="/pasiensirena",
    *   tags={"Rehab Pasien Sirena"},
    *   summary="Get List Data Rehab pasien sirena",
    *   operationId="get data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="page",
    *     in="query",
    *     description="page",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="limit",
    *     in="query",
    *     description="limit data",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
*/

/**
    * @SWG\Get(
    *   path="/pasiensirena/{id}",
    *   tags={"Rehab Pasien Sirena"},
    *   summary="Get List Data Rehab pasien sirena by id",
    *   operationId="get data by id",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id",
    *     in="path",
    *     description="id data",
    *     required=true,
    *     type="integer"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
*/

/**
    * @SWG\Post(
    *   path="/pasiensirena",
    *   tags={"Rehab Pasien Sirena"},
    *   summary="Create Data Rehab pasien sirena",
    *   operationId="create data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="idx_assesment",
    *     in="formData",
    *     description="idx assesment",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="idx_pasien",
    *     in="formData",
    *     description="idx pasien",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nama",
    *     in="formData",
    *     description="Nama pasien",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_kelamin",
    *     in="formData",
    *     description="Jenis kelamin",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="golongan_darah",
    *     in="formData",
    *     description="golongan darah",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_lahir",
    *     in="formData",
    *     description="Tempat lahir",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_lahir",
    *     in="formData",
    *     description="Tanggal lahir(Y-m-d H:i:s)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="agama",
    *     in="formData",
    *     description="Agama",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_nikah",
    *     in="formData",
    *     description="Status nikah",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="no_identitas",
    *     in="formData",
    *     description="No identitas",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_identitas",
    *     in="formData",
    *     description="Jenis identitas",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nik",
    *     in="formData",
    *     description="NIK",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="alamat",
    *     in="formData",
    *     description="Alamat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_telp",
    *     in="formData",
    *     description="No telepon",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_hp",
    *     in="formData",
    *     description="No Hp",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="usia",
    *     in="formData",
    *     description="Usia",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="pendidikan",
    *     in="formData",
    *     description="Pendidikan",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="pendidikan_akhir",
    *     in="formData",
    *     description="Pendidikan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="pekerjaan",
    *     in="formData",
    *     description="Pekerjaan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_rekam_medis",
    *     in="formData",
    *     description="No rekam medis",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_registrasi",
    *     in="formData",
    *     description="No registrasi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_registrasi",
    *     in="formData",
    *     description="Tanggal registrasi(Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="warga_negara",
    *     in="formData",
    *     description="Warga negara",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="negara",
    *     in="formData",
    *     description="Negara",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_propinsi",
    *     in="formData",
    *     description="Kode provinsi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_kabupaten",
    *     in="formData",
    *     description="Kode kabupaten",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="umur",
    *     in="formData",
    *     description="Umur",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_pos",
    *     in="formData",
    *     description="kode pos",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="sumber_biaya",
    *     in="formData",
    *     description="Sumber biaya",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="sumber_pasien",
    *     in="formData",
    *     description="Sumber pasien",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_wilayah",
    *     in="formData",
    *     description="Kode wilayah",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_wilayah_propinsi",
    *     in="formData",
    *     description="kode wilayah provinsi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jns_org",
    *     in="formData",
    *     description="Jenis organisasi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_bnn",
    *     in="formData",
    *     description="kode BNN",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_rehab",
    *     in="formData",
    *     description="Tempat rehab",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_tempat_rehab",
    *     in="formData",
    *     description="kode tempat rehab",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kode_pos",
    *     in="formData",
    *     description="kode pos",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="rt_rw",
    *     in="formData",
    *     description="RT RW",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="suku",
    *     in="formData",
    *     description="Suku",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="ayah",
    *     in="formData",
    *     description="Nama ayah",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="ibu",
    *     in="formData",
    *     description="Nama ibu",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_rehab",
    *     in="formData",
    *     description="status rehab",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_proses",
    *     in="formData",
    *     description="Status proses",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_check_doc",
    *     in="formData",
    *     description="Status check dokter",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_rawat",
    *     in="formData",
    *     description="Status rawat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_rawat_inap",
    *     in="formData",
    *     description="Status rawat inap",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="klg_nama",
    *     in="formData",
    *     description="nama keluarga",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="klg_hubungan",
    *     in="formData",
    *     description="hubungan keluarga",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="klg_alamat",
    *     in="formData",
    *     description="Alamat keluarga",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="klg_telp",
    *     in="formData",
    *     description="Telepon keluarga",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_rekam_medis",
    *     in="formData",
    *     description="Tanggal rekam medis (Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_mulai_rehab",
    *     in="formData",
    *     description="Tanggal mulai rehab(Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="inst_rujuk",
    *     in="formData",
    *     description="Inst rujuk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="rujuk_rehab",
    *     in="formData",
    *     description="Rehab rujuk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_mulai_pasca",
    *     in="formData",
    *     description="Tanggal mulai pasca(Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="inst_pasca",
    *     in="formData",
    *     description="Inst pasca",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="rujuk_pasca",
    *     in="formData",
    *     description="Pasca rujuk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="inst_lanjut",
    *     in="formData",
    *     description="Inst lanjut",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="rujuk_lanjut",
    *     in="formData",
    *     description="Rujuk lanjut",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jns_treat",
    *     in="formData",
    *     description="Jenis treat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_assestment",
    *     in="formData",
    *     description="Tanggal assestment (Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_balai",
    *     in="formData",
    *     description="Kode balai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="outcome_pasien",
    *     in="formData",
    *     description="Outcome pasien",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="active_pasien",
    *     in="formData",
    *     description="Active pasien",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jns_pendaftar",
    *     in="formData",
    *     description="Jenis pendaftaran",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="flag_pasien",
    *     in="formData",
    *     description="Flag pasien",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_rm",
    *     in="formData",
    *     description="Status RM",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_program",
    *     in="formData",
    *     description="Status program",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
*/

/**
    * @SWG\Put(
    *   path="/pasiensirena/{id}",
    *   tags={"Rehab Pasien Sirena"},
    *   summary="Update Data Rehab pasien sirena",
    *   operationId="update data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id",
    *     in="path",
    *     description="id data",
    *     required=true,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="idx_assesment",
    *     in="formData",
    *     description="idx assesment",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="idx_pasien",
    *     in="formData",
    *     description="idx pasien",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nama",
    *     in="formData",
    *     description="Nama pasien",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_kelamin",
    *     in="formData",
    *     description="Jenis kelamin",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="golongan_darah",
    *     in="formData",
    *     description="golongan darah",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_lahir",
    *     in="formData",
    *     description="Tempat lahir",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_lahir",
    *     in="formData",
    *     description="Tanggal lahir(Y-m-d H:i:s)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="agama",
    *     in="formData",
    *     description="Agama",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_nikah",
    *     in="formData",
    *     description="Status nikah",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="no_identitas",
    *     in="formData",
    *     description="No identitas",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_identitas",
    *     in="formData",
    *     description="Jenis identitas",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nik",
    *     in="formData",
    *     description="NIK",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="alamat",
    *     in="formData",
    *     description="Alamat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_telp",
    *     in="formData",
    *     description="No telepon",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_hp",
    *     in="formData",
    *     description="No Hp",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="usia",
    *     in="formData",
    *     description="Usia",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="pendidikan",
    *     in="formData",
    *     description="Pendidikan",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="pendidikan_akhir",
    *     in="formData",
    *     description="Pendidikan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="pekerjaan",
    *     in="formData",
    *     description="Pekerjaan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_rekam_medis",
    *     in="formData",
    *     description="No rekam medis",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_registrasi",
    *     in="formData",
    *     description="No registrasi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_registrasi",
    *     in="formData",
    *     description="Tanggal registrasi(Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="warga_negara",
    *     in="formData",
    *     description="Warga negara",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="negara",
    *     in="formData",
    *     description="Negara",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_propinsi",
    *     in="formData",
    *     description="Kode provinsi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_kabupaten",
    *     in="formData",
    *     description="Kode kabupaten",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="umur",
    *     in="formData",
    *     description="Umur",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_pos",
    *     in="formData",
    *     description="kode pos",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="sumber_biaya",
    *     in="formData",
    *     description="Sumber biaya",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="sumber_pasien",
    *     in="formData",
    *     description="Sumber pasien",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_wilayah",
    *     in="formData",
    *     description="Kode wilayah",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_wilayah_propinsi",
    *     in="formData",
    *     description="kode wilayah provinsi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jns_org",
    *     in="formData",
    *     description="Jenis organisasi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_bnn",
    *     in="formData",
    *     description="kode BNN",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_rehab",
    *     in="formData",
    *     description="Tempat rehab",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_tempat_rehab",
    *     in="formData",
    *     description="kode tempat rehab",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kode_pos",
    *     in="formData",
    *     description="kode pos",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="rt_rw",
    *     in="formData",
    *     description="RT RW",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="suku",
    *     in="formData",
    *     description="Suku",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="ayah",
    *     in="formData",
    *     description="Nama ayah",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="ibu",
    *     in="formData",
    *     description="Nama ibu",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_rehab",
    *     in="formData",
    *     description="status rehab",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_proses",
    *     in="formData",
    *     description="Status proses",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_check_doc",
    *     in="formData",
    *     description="Status check dokter",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_rawat",
    *     in="formData",
    *     description="Status rawat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_rawat_inap",
    *     in="formData",
    *     description="Status rawat inap",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="klg_nama",
    *     in="formData",
    *     description="nama keluarga",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="klg_hubungan",
    *     in="formData",
    *     description="hubungan keluarga",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="klg_alamat",
    *     in="formData",
    *     description="Alamat keluarga",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="klg_telp",
    *     in="formData",
    *     description="Telepon keluarga",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_rekam_medis",
    *     in="formData",
    *     description="Tanggal rekam medis (Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_mulai_rehab",
    *     in="formData",
    *     description="Tanggal mulai rehab(Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="inst_rujuk",
    *     in="formData",
    *     description="Inst rujuk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="rujuk_rehab",
    *     in="formData",
    *     description="Rehab rujuk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_mulai_pasca",
    *     in="formData",
    *     description="Tanggal mulai pasca(Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="inst_pasca",
    *     in="formData",
    *     description="Inst pasca",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="rujuk_pasca",
    *     in="formData",
    *     description="Pasca rujuk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="inst_lanjut",
    *     in="formData",
    *     description="Inst lanjut",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="rujuk_lanjut",
    *     in="formData",
    *     description="Rujuk lanjut",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jns_treat",
    *     in="formData",
    *     description="Jenis treat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_assestment",
    *     in="formData",
    *     description="Tanggal assestment (Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kd_balai",
    *     in="formData",
    *     description="Kode balai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="outcome_pasien",
    *     in="formData",
    *     description="Outcome pasien",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="active_pasien",
    *     in="formData",
    *     description="Active pasien",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jns_pendaftar",
    *     in="formData",
    *     description="Jenis pendaftaran",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="flag_pasien",
    *     in="formData",
    *     description="Flag pasien",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_rm",
    *     in="formData",
    *     description="Status RM",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_program",
    *     in="formData",
    *     description="Status program",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
*/

/**
    * @SWG\Delete(
    *   path="/pasiensirena/{id}",
    *   tags={"Rehab Pasien Sirena"},
    *   summary="Delete Data Rehab pasien sirena By id",
    *   operationId="delete data by id",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id",
    *     in="path",
    *     description="id data",
    *     required=true,
    *     type="integer"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
*/