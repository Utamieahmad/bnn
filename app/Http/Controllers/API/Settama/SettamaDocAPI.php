<?php

/**
    * @SWG\Get(
    *   path="/settama",
    *   tags={"Settama"},
    *   summary="Get List Data Settama",
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
    *   path="/settama/{id}",
    *   tags={"Settama"},
    *   summary="Get List Data Settama By id",
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
    *     description="get data by id",
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
    *   path="/settama",
    *   tags={"Settama"},
    *   summary="Create Data Settama",
    *   operationId="create data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jns_rujukan",
    *     in="formData",
    *     description="Jenis rujukan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_rujukan",
    *     in="formData",
    *     description="No rujukan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_mulai",
    *     in="formData",
    *     description="Tanggal mulai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_selesai",
    *     in="formData",
    *     description="Tanggal selesai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="pelaksana",
    *     in="formData",
    *     description="pelaksana",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="bagian",
    *     in="formData",
    *     description="Bagian",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_kegiatan",
    *     in="formData",
    *     description="Jenis kegiatan",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_kegiatan",
    *     in="formData",
    *     description="Tempat kegiatan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tujuan_kegiatan",
    *     in="formData",
    *     description="Tujuan kegiatan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="sumber_anggaran",
    *     in="formData",
    *     description="Sumber anggaran",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="meta_peserta",
    *     in="formData",
    *     description="meta peserta",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="file_laporan",
    *     in="formData",
    *     description="File laporan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="anggaran_id",
    *     in="formData",
    *     description="Id anggaran",
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
    *   path="/settama/{id}",
    *   tags={"Settama"},
    *   summary="Update Data Settama",
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
    *     name="jns_rujukan",
    *     in="formData",
    *     description="Jenis rujukan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="no_rujukan",
    *     in="formData",
    *     description="No rujukan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_mulai",
    *     in="formData",
    *     description="Tanggal mulai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_selesai",
    *     in="formData",
    *     description="Tanggal selesai",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="pelaksana",
    *     in="formData",
    *     description="pelaksana",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="bagian",
    *     in="formData",
    *     description="Bagian",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="jenis_kegiatan",
    *     in="formData",
    *     description="Jenis kegiatan",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="tempat_kegiatan",
    *     in="formData",
    *     description="Tempat kegiatan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tujuan_kegiatan",
    *     in="formData",
    *     description="Tujuan kegiatan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="sumber_anggaran",
    *     in="formData",
    *     description="Sumber anggaran",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="meta_peserta",
    *     in="formData",
    *     description="meta peserta",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="file_laporan",
    *     in="formData",
    *     description="File laporan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="anggaran_id",
    *     in="formData",
    *     description="Id anggaran",
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
    *   path="/settama/{id}",
    *   tags={"Settama"},
    *   summary="Delete Data Settama By id",
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