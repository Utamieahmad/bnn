<?php

/**
    * @SWG\Get(
    *   path="/penilaianlembaga",
    *   tags={"Rehab Penilaian Lembaga"},
    *   summary="Get List Data Penilaian Lembaga",
    *   operationId="get data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
*/

/**
    * @SWG\Get(
    *   path="/penilaianlembaga/{id}",
    *   tags={"Rehab Penilaian Lembaga"},
    *   summary="Get List Data Penilaian Lembaga by id",
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
    *   path="/penilaianlembaga",
    *   tags={"Rehab Penilaian Lembaga"},
    *   summary="Create Data Penilaian Lembaga",
    *   operationId="create data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_lembaga",
    *     in="formData",
    *     description="id lembaga",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nama",
    *     in="formData",
    *     description="Nama",
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
    *     name="alamat_kodepos",
    *     in="formData",
    *     description="Kode Pos",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="alamat_idprovinsi",
    *     in="formData",
    *     description="id provinsi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="alamat_idkabkota",
    *     in="formData",
    *     description="id kota",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_04_a",
    *     in="formData",
    *     description="Nilai 04 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_04_b",
    *     in="formData",
    *     description="Nilai 04 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_04_c",
    *     in="formData",
    *     description="Nilai 04 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_a",
    *     in="formData",
    *     description="Nilai 05 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_b",
    *     in="formData",
    *     description="Nilai 05 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_c",
    *     in="formData",
    *     description="Nilai 05 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_d",
    *     in="formData",
    *     description="Nilai 05 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_e",
    *     in="formData",
    *     description="Nilai 05 E",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_f",
    *     in="formData",
    *     description="Nilai 05 F",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_a",
    *     in="formData",
    *     description="Nilai 06 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_b",
    *     in="formData",
    *     description="Nilai 06 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_c",
    *     in="formData",
    *     description="Nilai 06 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_d",
    *     in="formData",
    *     description="Nilai 06 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_e",
    *     in="formData",
    *     description="Nilai 06 E",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_f",
    *     in="formData",
    *     description="Nilai 06 F",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_g",
    *     in="formData",
    *     description="Nilai 06 G",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_a",
    *     in="formData",
    *     description="Nilai 07 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_b",
    *     in="formData",
    *     description="Nilai 07 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_c",
    *     in="formData",
    *     description="Nilai 07 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_d",
    *     in="formData",
    *     description="Nilai 07 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_e",
    *     in="formData",
    *     description="Nilai 07 E",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_f",
    *     in="formData",
    *     description="Nilai 07 F",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_g",
    *     in="formData",
    *     description="Nilai 07 G",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_h",
    *     in="formData",
    *     description="Nilai 07 H",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_i",
    *     in="formData",
    *     description="Nilai 07 I",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_08_a",
    *     in="formData",
    *     description="Nilai 08 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_08_b",
    *     in="formData",
    *     description="Nilai 08 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_08_c",
    *     in="formData",
    *     description="Nilai 08 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_08_d",
    *     in="formData",
    *     description="Nilai 08 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_09_a",
    *     in="formData",
    *     description="Nilai 09 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_09_b",
    *     in="formData",
    *     description="Nilai 09 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_09_c",
    *     in="formData",
    *     description="Nilai 09 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_09_d",
    *     in="formData",
    *     description="Nilai 09 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_a",
    *     in="formData",
    *     description="Nilai 10 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_b",
    *     in="formData",
    *     description="Nilai 10 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_c",
    *     in="formData",
    *     description="Nilai 10 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_d",
    *     in="formData",
    *     description="Nilai 10 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_e",
    *     in="formData",
    *     description="Nilai 10 E",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_f",
    *     in="formData",
    *     description="Nilai 10 F",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_g",
    *     in="formData",
    *     description="Nilai 10 G",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_h",
    *     in="formData",
    *     description="Nilai 10 H",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_i",
    *     in="formData",
    *     description="Nilai 10 I",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_j",
    *     in="formData",
    *     description="Nilai 10 J",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_k",
    *     in="formData",
    *     description="Nilai 10 K",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_a",
    *     in="formData",
    *     description="Nilai 11 A",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_b",
    *     in="formData",
    *     description="Nilai 11 B",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_c",
    *     in="formData",
    *     description="Nilai 11 C",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_d",
    *     in="formData",
    *     description="Nilai 11 D",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_e",
    *     in="formData",
    *     description="Nilai 11 E",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_yang_direhab",
    *     in="formData",
    *     description="Jumlah pecandu yang direhab",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_selesai",
    *     in="formData",
    *     description="Jumlah pecandu selesai",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_tidakselesai_kabur",
    *     in="formData",
    *     description="Jumlah pecandu yang tidak selsai kabur",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_tidakselesai_pulang",
    *     in="formData",
    *     description="Jumlah pecandu yang tidak selsai pulang",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_tidakselesai_dirujuk",
    *     in="formData",
    *     description="Jumlah pecandu yang tidak selsai dirujuk",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_asalpecandu_razia",
    *     in="formData",
    *     description="Jumlah pecandu razia",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_asalpecandu_rujukanlembaga",
    *     in="formData",
    *     description="Jumlah pecandu rujukan lembaga",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_asalpecandu_datangsendiri",
    *     in="formData",
    *     description="Jumlah pecandu datang sendiri",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_aktif",
    *     in="formData",
    *     description="Status aktif",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tipe",
    *     in="formData",
    *     description="Tipe",
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
    *   path="/penilaianlembaga/{id}",
    *   tags={"Rehab Penilaian Lembaga"},
    *   summary="Update Data Penilaian Lembaga",
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
    *     name="id_lembaga",
    *     in="formData",
    *     description="id lembaga",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nama",
    *     in="formData",
    *     description="Nama",
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
    *     name="alamat_kodepos",
    *     in="formData",
    *     description="Kode Pos",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="alamat_idprovinsi",
    *     in="formData",
    *     description="id provinsi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="alamat_idkabkota",
    *     in="formData",
    *     description="id kota",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_04_a",
    *     in="formData",
    *     description="Nilai 04 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_04_b",
    *     in="formData",
    *     description="Nilai 04 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_04_c",
    *     in="formData",
    *     description="Nilai 04 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_a",
    *     in="formData",
    *     description="Nilai 05 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_b",
    *     in="formData",
    *     description="Nilai 05 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_c",
    *     in="formData",
    *     description="Nilai 05 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_d",
    *     in="formData",
    *     description="Nilai 05 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_e",
    *     in="formData",
    *     description="Nilai 05 E",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_05_f",
    *     in="formData",
    *     description="Nilai 05 F",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_a",
    *     in="formData",
    *     description="Nilai 06 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_b",
    *     in="formData",
    *     description="Nilai 06 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_c",
    *     in="formData",
    *     description="Nilai 06 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_d",
    *     in="formData",
    *     description="Nilai 06 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_e",
    *     in="formData",
    *     description="Nilai 06 E",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_f",
    *     in="formData",
    *     description="Nilai 06 F",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_06_g",
    *     in="formData",
    *     description="Nilai 06 G",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_a",
    *     in="formData",
    *     description="Nilai 07 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_b",
    *     in="formData",
    *     description="Nilai 07 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_c",
    *     in="formData",
    *     description="Nilai 07 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_d",
    *     in="formData",
    *     description="Nilai 07 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_e",
    *     in="formData",
    *     description="Nilai 07 E",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_f",
    *     in="formData",
    *     description="Nilai 07 F",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_g",
    *     in="formData",
    *     description="Nilai 07 G",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_h",
    *     in="formData",
    *     description="Nilai 07 H",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_07_i",
    *     in="formData",
    *     description="Nilai 07 I",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_08_a",
    *     in="formData",
    *     description="Nilai 08 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_08_b",
    *     in="formData",
    *     description="Nilai 08 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_08_c",
    *     in="formData",
    *     description="Nilai 08 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_08_d",
    *     in="formData",
    *     description="Nilai 08 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_09_a",
    *     in="formData",
    *     description="Nilai 09 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_09_b",
    *     in="formData",
    *     description="Nilai 09 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_09_c",
    *     in="formData",
    *     description="Nilai 09 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_09_d",
    *     in="formData",
    *     description="Nilai 09 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_a",
    *     in="formData",
    *     description="Nilai 10 A",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_b",
    *     in="formData",
    *     description="Nilai 10 B",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_c",
    *     in="formData",
    *     description="Nilai 10 C",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_d",
    *     in="formData",
    *     description="Nilai 10 D",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_e",
    *     in="formData",
    *     description="Nilai 10 E",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_f",
    *     in="formData",
    *     description="Nilai 10 F",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_g",
    *     in="formData",
    *     description="Nilai 10 G",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_h",
    *     in="formData",
    *     description="Nilai 10 H",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_i",
    *     in="formData",
    *     description="Nilai 10 I",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_j",
    *     in="formData",
    *     description="Nilai 10 J",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_10_k",
    *     in="formData",
    *     description="Nilai 10 K",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_a",
    *     in="formData",
    *     description="Nilai 11 A",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_b",
    *     in="formData",
    *     description="Nilai 11 B",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_c",
    *     in="formData",
    *     description="Nilai 11 C",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_d",
    *     in="formData",
    *     description="Nilai 11 D",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nilai_11_e",
    *     in="formData",
    *     description="Nilai 11 E",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_yang_direhab",
    *     in="formData",
    *     description="Jumlah pecandu yang direhab",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_selesai",
    *     in="formData",
    *     description="Jumlah pecandu selesai",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_tidakselesai_kabur",
    *     in="formData",
    *     description="Jumlah pecandu yang tidak selsai kabur",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_tidakselesai_pulang",
    *     in="formData",
    *     description="Jumlah pecandu yang tidak selsai pulang",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_pecandu_tidakselesai_dirujuk",
    *     in="formData",
    *     description="Jumlah pecandu yang tidak selsai dirujuk",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_asalpecandu_razia",
    *     in="formData",
    *     description="Jumlah pecandu razia",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_asalpecandu_rujukanlembaga",
    *     in="formData",
    *     description="Jumlah pecandu rujukan lembaga",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_asalpecandu_datangsendiri",
    *     in="formData",
    *     description="Jumlah pecandu datang sendiri",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_aktif",
    *     in="formData",
    *     description="Status aktif",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tipe",
    *     in="formData",
    *     description="Tipe",
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
    *   path="/penilaianlembaga/{id}",
    *   tags={"Rehab Penilaian Lembaga"},
    *   summary="Delete Data penilian lembaga By id",
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


/**
    * @SWG\Post(
    *   path="/penilaian_lembaga_rehabilitasi",
    *   tags={"Rehab Penilaian Lembaga"},
    *   summary="Get List Data Penilaian Lembaga by id",
    *   operationId="get data by id",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="limit",
    *     in="formData",
    *     description="Limit",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="page",
    *     in="formData",
    *     description="Page",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
*/