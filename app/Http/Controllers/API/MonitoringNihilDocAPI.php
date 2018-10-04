<?php

/**
    * @SWG\Get(
    *   path="/monitoringnihil",
    *   tags={"Monitoring Nihil"},
    *   summary="Get List Data Monitoring Nihil",
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
    *   path="/monitoringnihil/{id}",
    *   tags={"Monitoring Nihil"},
    *   summary="Get List Data Monitoring Nihil By id",
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
    *   path="/monitoringnihil",
    *   tags={"Monitoring Nihil"},
    *   summary="Create Data Monitoring Nihil",
    *   operationId="create data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="created_by",
    *     in="formData",
    *     description="created by",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="created_by_username",
    *     in="formData",
    *     description="created by username",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_kegiatan",
    *     in="formData",
    *     description="nama kegiatan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_pelaksanaan",
    *     in="formData",
    *     description="tanggal pelaksana",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="keterangan",
    *     in="formData",
    *     description="keterangan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="idpelaksana",
    *     in="formData",
    *     description="id pelaksana",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_entri",
    *     in="formData",
    *     description="status entri",
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
    *   path="/monitoringnihil/{id}",
    *   tags={"Monitoring Nihil"},
    *   summary="Update Data Monitoring Nihil",
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
    *     name="updated_by",
    *     in="formData",
    *     description="updated by",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="updated_by_username",
    *     in="formData",
    *     description="updated by username",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_kegiatan",
    *     in="formData",
    *     description="nama kegiatan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_pelaksanaan",
    *     in="formData",
    *     description="tanggal pelaksana",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="keterangan",
    *     in="formData",
    *     description="keterangan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="idpelaksana",
    *     in="formData",
    *     description="id pelaksana",
    *     required=false,
    *     type="integer"
    *   ),
    *   @SWG\Parameter(
    *     name="status_entri",
    *     in="formData",
    *     description="status entri",
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
    *   path="/monitoringnihil/{id}",
    *   tags={"Monitoring Nihil"},
    *   summary="Delete Data Monitoring Nihil By id",
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