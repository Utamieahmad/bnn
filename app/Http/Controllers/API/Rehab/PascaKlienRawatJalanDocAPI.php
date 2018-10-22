<?php

/**
    * @SWG\Get(
    *   path="/pascaklienjalan",
    *   tags={"Rehab Pasca Klien Rawat Jalan"},
    *   summary="Get List Data Rehab klien rawat jalan",
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
    *   path="/pascaklienjalan/{id}",
    *   tags={"Rehab Pasca Klien Rawat Jalan"},
    *   summary="Get List Data Rehab klien rawat jalan by id",
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
    *   path="/pascaklienjalan",
    *   tags={"Rehab Pasca Klien Rawat Jalan"},
    *   summary="Create Data Rehab pasien rawat jalan",
    *   operationId="create data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_header",
    *     in="formData",
    *     description="ID header",
    *     required=true,
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
    *     name="no_identitas",
    *     in="formData",
    *     description="Nomor identitas",
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
    *     name="kode_jeniskelamin",
    *     in="formData",
    *     description="Kode jenis kelamin",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_hape",
    *     in="formData",
    *     description="No hp",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="email",
    *     in="formData",
    *     description="Email",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_01",
    *     in="formData",
    *     description="Jenis layanan 01",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_02",
    *     in="formData",
    *     description="Jenis layanan 02",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_03",
    *     in="formData",
    *     description="Jenis layanan 03",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_04_01",
    *     in="formData",
    *     description="Jenis layanan 04 01",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_05",
    *     in="formData",
    *     description="Jenis layanan 05",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_06_01",
    *     in="formData",
    *     description="Jenis layanan 06 01",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_lainnya",
    *     in="formData",
    *     description="Jenis layanan lainnya",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_lainnya_jumlah",
    *     in="formData",
    *     description="Jumlah Jenis layanan lainnya",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_04_02",
    *     in="formData",
    *     description="Jenis layanan 04 02",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_04_03",
    *     in="formData",
    *     description="Jenis layanan 04 03",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_06_02",
    *     in="formData",
    *     description="Jenis layanan 06 02",
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
    * @SWG\Put(
    *   path="/pascaklienjalan/{id}",
    *   tags={"Rehab Pasca Klien Rawat Jalan"},
    *   summary="Update Data Rehab pasien rawat jalan",
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
    *     name="id_header",
    *     in="formData",
    *     description="ID header",
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
    *     name="no_identitas",
    *     in="formData",
    *     description="Nomor identitas",
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
    *     name="kode_jeniskelamin",
    *     in="formData",
    *     description="Kode jenis kelamin",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_hape",
    *     in="formData",
    *     description="No hp",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="email",
    *     in="formData",
    *     description="Email",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_01",
    *     in="formData",
    *     description="Jenis layanan 01",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_02",
    *     in="formData",
    *     description="Jenis layanan 02",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_03",
    *     in="formData",
    *     description="Jenis layanan 03",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_04_01",
    *     in="formData",
    *     description="Jenis layanan 04 01",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_05",
    *     in="formData",
    *     description="Jenis layanan 05",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_06_01",
    *     in="formData",
    *     description="Jenis layanan 06 01",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_lainnya",
    *     in="formData",
    *     description="Jenis layanan lainnya",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_lainnya_jumlah",
    *     in="formData",
    *     description="Jumlah Jenis layanan lainnya",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_04_02",
    *     in="formData",
    *     description="Jenis layanan 04 02",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_04_03",
    *     in="formData",
    *     description="Jenis layanan 04 03",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_layanan_06_02",
    *     in="formData",
    *     description="Jenis layanan 06 02",
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
    * @SWG\Delete(
    *   path="/pascaklienjalan/{id}",
    *   tags={"Rehab Pasca Klien Rawat Jalan"},
    *   summary="Delete Data pasien rawat jalan By id",
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