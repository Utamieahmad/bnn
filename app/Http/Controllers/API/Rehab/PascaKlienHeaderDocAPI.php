<?php

/**
    * @SWG\Get(
    *   path="/pascaklienheader",
    *   tags={"Rehab Pasca klien Header"},
    *   summary="Get List Data Rehab pasca klien header",
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
    *   path="/pascaklienheader/{id}",
    *   tags={"Rehab Pasca klien Header"},
    *   summary="Get List Data Rehab pasca klien header by id",
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
    *   path="/pascaklienheader",
    *   tags={"Rehab Pasca klien Header"},
    *   summary="Create Data Rehab pasca klien header",
    *   operationId="create data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_instansi",
    *     in="formData",
    *     description="ID instansi",
    *     required=true,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="id_provinsi",
    *     in="formData",
    *     description="ID provinsi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_pelaksanaan_mulai",
    *     in="formData",
    *     description="Tanggal mulai pelaksanaan (Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_pelaksanaan_selesai",
    *     in="formData",
    *     description="Tanggal selesai pelaksanaan (Y-m-d)",
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
    *   @SWG\Parameter(
    *     name="periode",
    *     in="formData",
    *     description="Periode",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="periode_tahun",
    *     in="formData",
    *     description="Tahun periode",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="periode_bulan",
    *     in="formData",
    *     description="Bulan periode",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_klien",
    *     in="formData",
    *     description="Jumlah klien",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="kode_jenis_layanan",
    *     in="formData",
    *     description="Kode jenis layanan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_uang_klaim",
    *     in="formData",
    *     description="Jumlah uang klaim",
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
    *   path="/pascaklienheader/{id}",
    *   tags={"Rehab Pasca klien Header"},
    *   summary="Update Data Rehab pasca klien header",
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
    *     name="id_instansi",
    *     in="formData",
    *     description="ID instansi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="id_provinsi",
    *     in="formData",
    *     description="ID provinsi",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_pelaksanaan_mulai",
    *     in="formData",
    *     description="Tanggal mulai pelaksanaan (Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_pelaksanaan_selesai",
    *     in="formData",
    *     description="Tanggal selesai pelaksanaan (Y-m-d)",
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
    *   @SWG\Parameter(
    *     name="periode",
    *     in="formData",
    *     description="Periode",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="periode_tahun",
    *     in="formData",
    *     description="Tahun periode",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="periode_bulan",
    *     in="formData",
    *     description="Bulan periode",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_klien",
    *     in="formData",
    *     description="Jumlah klien",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="kode_jenis_layanan",
    *     in="formData",
    *     description="Kode jenis layanan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_uang_klaim",
    *     in="formData",
    *     description="Jumlah uang klaim",
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
    *   path="/pascaklienheader/{id}",
    *   tags={"Rehab Pasca klien Header"},
    *   summary="Delete Data pasca klien header By id",
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