<?php

/**
    * @SWG\Get(
    *   path="/pelatihanpeserta",
    *   tags={"Rehab Pelatihan Peserta"},
    *   summary="Get List Data rehab pelatihan peserta",
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
    *   path="/pelatihanpeserta/{id}",
    *   tags={"Rehab Pelatihan Peserta"},
    *   summary="Get List Data Rehab pelatihan peserta By id",
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
    *   path="/pelatihanpeserta",
    *   tags={"Rehab Pelatihan Peserta"},
    *   summary="Create Data Rehab pelatihan peserta",
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
    *     description="ID Header",
    *     required=true,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_peserta",
    *     in="formData",
    *     description="Nama peserta",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nomor_identitas",
    *     in="formData",
    *     description="No identitas",
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
    *     name="nomor_hp",
    *     in="formData",
    *     description="Nomor hp",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="email_peserta",
    *     in="formData",
    *     description="Email peserta",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="asal_instansilembaga",
    *     in="formData",
    *     description="Asal instansi lembaga",
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
    *     name="alamat",
    *     in="formData",
    *     description="Alamat",
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
    *   path="/pelatihanpeserta/{id}",
    *   tags={"Rehab Pelatihan Peserta"},
    *   summary="Update Data Rehab pelatihan peserta",
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
    *     description="ID Header",
    *     required=true,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_peserta",
    *     in="formData",
    *     description="Nama peserta",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nomor_identitas",
    *     in="formData",
    *     description="No identitas",
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
    *     name="nomor_hp",
    *     in="formData",
    *     description="Nomor hp",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="email_peserta",
    *     in="formData",
    *     description="Email peserta",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="asal_instansilembaga",
    *     in="formData",
    *     description="Asal instansi lembaga",
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
    *     name="alamat",
    *     in="formData",
    *     description="Alamat",
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
    *   path="/pelatihanpeserta/{id_detail}",
    *   tags={"Rehab Pelatihan Peserta"},
    *   summary="Delete Data pelatihan peserta By id",
    *   operationId="delete data by id",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_detail",
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
    * @SWG\Get(
    *   path="/single_pelatihan_rehabilitasi/{parent_id}",
    *   tags={"Rehab Pelatihan Peserta"},
    *   summary="Get List Data Rehab pelatihan peserta By id parent",
    *   operationId="get data by id parent",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="parent_id",
    *     in="path",
    *     description="id parent data",
    *     required=true,
    *     type="integer"
    *   ),
    *   @SWG\Response(response=200, description="successful operation"),
    *   @SWG\Response(response=406, description="not acceptable"),
    *   @SWG\Response(response=500, description="internal server error")
    * )
    *
*/