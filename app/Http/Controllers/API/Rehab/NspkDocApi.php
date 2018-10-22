<?php

/**
    * @SWG\Get(
    *   path="/nspk",
    *   tags={"Rehab Nspk"},
    *   summary="Get List Data Rehab Nspk",
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
    *   path="/nspk/{id}",
    *   tags={"Rehab Nspk"},
    *   summary="Get List Data Rehab Nspk by id",
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
    *   path="/nspk",
    *   tags={"Rehab Nspk"},
    *   summary="Create Data Rehab Nspk",
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
    *     name="kode_direktorat",
    *     in="formData",
    *     description="Kode direktorat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_pembuatan",
    *     in="formData",
    *     description="Tanggal pembuatan (Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_nspk",
    *     in="formData",
    *     description="Nama Nspk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nomor_nsdpk",
    *     in="formData",
    *     description="No nsdpk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="peruntukan",
    *     in="formData",
    *     description="Peruntukan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="file_nspk",
    *     in="formData",
    *     description="File nspk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_aktif",
    *     in="formData",
    *     description="Status aktif",
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
    *     description="Periode tahun",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="periode_bulan",
    *     in="formData",
    *     description="Periode bulan",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="periode_tanggal",
    *     in="formData",
    *     description="Periode tanggal",
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
    *   path="/nspk/{id}",
    *   tags={"Rehab Nspk"},
    *   summary="Update Data Rehab Nspk",
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
    *     name="kode_direktorat",
    *     in="formData",
    *     description="Kode direktorat",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_pembuatan",
    *     in="formData",
    *     description="Tanggal pembuatan (Y-m-d)",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_nspk",
    *     in="formData",
    *     description="Nama Nspk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nomor_nsdpk",
    *     in="formData",
    *     description="No nsdpk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="peruntukan",
    *     in="formData",
    *     description="Peruntukan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="file_nspk",
    *     in="formData",
    *     description="File nspk",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_aktif",
    *     in="formData",
    *     description="Status aktif",
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
    *     description="Periode tahun",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="periode_bulan",
    *     in="formData",
    *     description="Periode bulan",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="periode_tanggal",
    *     in="formData",
    *     description="Periode tanggal",
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
    *   path="/nspk/{id}",
    *   tags={"Rehab Nspk"},
    *   summary="Delete Data Rehab nspk By id",
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