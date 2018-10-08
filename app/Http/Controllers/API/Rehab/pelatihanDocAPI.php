<?php

/**
    * @SWG\Get(
    *   path="/pelatihan",
    *   tags={"Rehab Pelatihan"},
    *   summary="Get List Data Rehab pelatihan",
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
    *   path="/pelatihan/{id}",
    *   tags={"Rehab Pelatihan"},
    *   summary="Get List Data Rehab pelatihan by id",
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
    *   path="/pelatihan",
    *   tags={"Rehab Pelatihan"},
    *   summary="Create Data Rehab pelatihan",
    *   operationId="create data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kategori",
    *     in="formData",
    *     description="Kategori",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_pelaksana",
    *     in="formData",
    *     description="No rujukan",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nomor_sprint",
    *     in="formData",
    *     description="Nomor Sprint",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_sprint",
    *     in="formData",
    *     description="Tanggal Sprint",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_dilaksanakan_mulai",
    *     in="formData",
    *     description="Tanggal mulai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_dilaksanakan_selesai",
    *     in="formData",
    *     description="Tanggal selesai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat",
    *     in="formData",
    *     description="Tempat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_idprovinsi",
    *     in="formData",
    *     description="Id Provinsi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_idkabkota",
    *     in="formData",
    *     description="Id kota",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_kodepos",
    *     in="formData",
    *     description="Kode pos",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_narasumber",
    *     in="formData",
    *     description="Jumlah narasumber",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tema",
    *     in="formData",
    *     description="Tema",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="file_materi",
    *     in="formData",
    *     description="File materi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_aktif",
    *     in="formData",
    *     description="Status",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="uraian_singkat",
    *     in="formData",
    *     description="Uraian Singkat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="koordinat",
    *     in="formData",
    *     description="Koordinat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="total_peserta",
    *     in="formData",
    *     description="Total peserta",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="foto1",
    *     in="formData",
    *     description="Foto",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="foto2",
    *     in="formData",
    *     description="Foto 2",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="foto3",
    *     in="formData",
    *     description="Foto 3",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_kegiatan",
    *     in="formData",
    *     description="Jenis kegiatan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_peserta",
    *     in="formData",
    *     description="Jumlah peserta",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status",
    *     in="formData",
    *     description="Status",
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
    *   path="/pelatihan/{id}",
    *   tags={"Rehab Pelatihan"},
    *   summary="Update Data Rehab pelatihan",
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
    *     name="kategori",
    *     in="formData",
    *     description="Kategori",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_pelaksana",
    *     in="formData",
    *     description="No rujukan",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nomor_sprint",
    *     in="formData",
    *     description="Nomor Sprint",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_sprint",
    *     in="formData",
    *     description="Tanggal Sprint",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_dilaksanakan_mulai",
    *     in="formData",
    *     description="Tanggal mulai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_dilaksanakan_selesai",
    *     in="formData",
    *     description="Tanggal selesai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat",
    *     in="formData",
    *     description="Tempat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_idprovinsi",
    *     in="formData",
    *     description="Id Provinsi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_idkabkota",
    *     in="formData",
    *     description="Id kota",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_kodepos",
    *     in="formData",
    *     description="Kode pos",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_narasumber",
    *     in="formData",
    *     description="Jumlah narasumber",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tema",
    *     in="formData",
    *     description="Tema",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="file_materi",
    *     in="formData",
    *     description="File materi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_aktif",
    *     in="formData",
    *     description="Status",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="uraian_singkat",
    *     in="formData",
    *     description="Uraian Singkat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="koordinat",
    *     in="formData",
    *     description="Koordinat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="total_peserta",
    *     in="formData",
    *     description="Total peserta",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="foto1",
    *     in="formData",
    *     description="Foto",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="foto2",
    *     in="formData",
    *     description="Foto 2",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="foto3",
    *     in="formData",
    *     description="Foto 3",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_kegiatan",
    *     in="formData",
    *     description="Jenis kegiatan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_peserta",
    *     in="formData",
    *     description="Jumlah peserta",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status",
    *     in="formData",
    *     description="Status",
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
    *   path="/pelatihan/{id}",
    *   tags={"Rehab Pelatihan"},
    *   summary="Delete Data Pelatihan By id",
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
    *   path="/listpelatihan",
    *   tags={"Rehab Pelatihan"},
    *   summary="Get Data Rehab pelatihan by request",
    *   operationId="get data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="pelaksana",
    *     in="formData",
    *     description="Pelaksana",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="start_from",
    *     in="formData",
    *     description="start from",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="start_to",
    *     in="formData",
    *     description="start to",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="end_from",
    *     in="formData",
    *     description="end from",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="end_to",
    *     in="formData",
    *     description="End to",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_from",
    *     in="formData",
    *     description="Jumlah from",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_to",
    *     in="formData",
    *     description="Jumlah to",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tema",
    *     in="formData",
    *     description="Tema",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nomor_sprint",
    *     in="formData",
    *     description="Nomor sprint",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="lokasi",
    *     in="formData",
    *     description="Lokasi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status",
    *     in="formData",
    *     description="Status",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kategori",
    *     in="formData",
    *     description="Kategori",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_wilayah",
    *     in="formData",
    *     description="Id wilayah",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="limit",
    *     in="formData",
    *     description="Limit",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="page",
    *     in="formData",
    *     description="Page",
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