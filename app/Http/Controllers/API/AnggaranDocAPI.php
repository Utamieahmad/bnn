<?php

/**
    * @SWG\Get(
    *   path="/anggaran",
    *   tags={"Anggaran"},
    *   summary="Get List Data Anggaran",
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
    *   path="/anggaran/{id}",
    *   tags={"Anggaran"},
    *   summary="Get List Data Anggaran By id",
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
    *   path="/anggaran",
    *   tags={"Anggaran"},
    *   summary="Create Data Anggaran",
    *   operationId="create data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kode_anggaran",
    *     in="formData",
    *     description="kode anggaran",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="sasaran",
    *     in="formData",
    *     description="sasaran",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="pagu",
    *     in="formData",
    *     description="pagu",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="target_output",
    *     in="formData",
    *     description="target output",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="satuan_output",
    *     in="formData",
    *     description="satuan output",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tahun",
    *     in="formData",
    *     description="tahun",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="satker_code",
    *     in="formData",
    *     description="satker code",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="refid_anggaran",
    *     in="formData",
    *     description="Reff id anggaran",
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
    *   path="/anggaran/{id}",
    *   tags={"Anggaran"},
    *   summary="Update Data Anggaran",
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
    *     name="kode_anggaran",
    *     in="formData",
    *     description="kode anggaran",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="sasaran",
    *     in="formData",
    *     description="sasaran",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="pagu",
    *     in="formData",
    *     description="pagu",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="target_output",
    *     in="formData",
    *     description="target output",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="satuan_output",
    *     in="formData",
    *     description="satuan output",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tahun",
    *     in="formData",
    *     description="tahun",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="satker_code",
    *     in="formData",
    *     description="satker code",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="refid_anggaran",
    *     in="formData",
    *     description="Reff id anggaran",
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
    *   path="/anggaran/{id}",
    *   tags={"Anggaran"},
    *   summary="Delete Data Anggaran By id",
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