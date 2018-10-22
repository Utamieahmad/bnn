<?php

/**
 * @SWG\Post(
 *   path="/getintelbylkn",
 *   tags={"Berantas Intel Jaringan"},
 *   summary="List Data by Page",
 *   operationId="getList",
 *   @SWG\Parameter(
 *     name="Authorization",
 *     in="header",
 *     description="Authorization Token",
 *     required=true,
 *     type="string"
 *   ),
 *   @SWG\Parameter(
 *     name="page",
 *     in="formData",
 *     description="Pagination",
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
    * @SWG\Get(
    *   path="/inteljaringan",
    *   tags={"Berantas Intel Jaringan"},
    *   summary="Get List Data",
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
   *   path="/inteljaringan/{id}",
   *   tags={"Berantas Intel Jaringan"},
   *   summary="Get Data by id",
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
    *   path="/inteljaringan",
    *   tags={"Berantas Intel Jaringan"},
    *   summary="Store data",
    *   operationId="Store data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nomor_lkn",
    *     in="formData",
    *     description="nomor_lkn",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_kasus",
    *     in="formData",
    *     description="id_kasus",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="keterlibatan_jaringan",
    *     in="formData",
    *     description="keterlibatan_jaringan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_jaringan",
    *     in="formData",
    *     description="nama_jaringan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_komandan_jaringan",
    *     in="formData",
    *     description="nama_komandan_jaringan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="kode_jenisjaringan",
    *     in="formData",
    *     description="kode_jenisjaringan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="asal_wilayah_jaringan",
    *     in="formData",
    *     description="asal_wilayah_jaringan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="asal_negara_jaringan",
    *     in="formData",
    *     description="asal_negara_jaringan",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="keterkaitan_jaringan_lain",
    *     in="formData",
    *     description="keterkaitan_jaringan_lain",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="nama_jaringan_terkait",
    *     in="formData",
    *     description="nama_jaringan_terkait",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status_aktif",
    *     in="formData",
    *     description="status_aktif",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="asal_wilayah_idprovinsi",
    *     in="formData",
    *     description="asal_wilayah_idprovinsi",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="asal_wilayah_idkabkota",
    *     in="formData",
    *     description="asal_wilayah_idkabkota",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="meta_jaringan_terkait",
    *     in="formData",
    *     description="meta_jaringan_terkait",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status",
    *     in="formData",
    *     description="status",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="file_upload",
    *     in="formData",
    *     description="file_upload",
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
      *   path="/inteljaringan/{id}",
      *   tags={"Berantas Intel Jaringan"},
      *   summary="update data by id",
      *   operationId="update data by id",
      *   @SWG\Parameter(
      *     name="id",
      *     in="path",
      *     description="update data by id",
      *     required=true,
      *     type="integer"
      *   ),
      *   @SWG\Parameter(
      *     name="Authorization",
      *     in="header",
      *     description="Authorization Token",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="nomor_lkn",
      *     in="formData",
      *     description="nomor_lkn",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="id_kasus",
      *     in="formData",
      *     description="id_kasus",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="keterlibatan_jaringan",
      *     in="formData",
      *     description="keterlibatan_jaringan",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="nama_jaringan",
      *     in="formData",
      *     description="nama_jaringan",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="nama_komandan_jaringan",
      *     in="formData",
      *     description="nama_komandan_jaringan",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="kode_jenisjaringan",
      *     in="formData",
      *     description="kode_jenisjaringan",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="asal_wilayah_jaringan",
      *     in="formData",
      *     description="asal_wilayah_jaringan",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="asal_negara_jaringan",
      *     in="formData",
      *     description="asal_negara_jaringan",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="keterkaitan_jaringan_lain",
      *     in="formData",
      *     description="keterkaitan_jaringan_lain",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="nama_jaringan_terkait",
      *     in="formData",
      *     description="nama_jaringan_terkait",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="status_aktif",
      *     in="formData",
      *     description="status_aktif",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="asal_wilayah_idprovinsi",
      *     in="formData",
      *     description="asal_wilayah_idprovinsi",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="asal_wilayah_idkabkota",
      *     in="formData",
      *     description="asal_wilayah_idkabkota",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="meta_jaringan_terkait",
      *     in="formData",
      *     description="meta_jaringan_terkait",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="status",
      *     in="formData",
      *     description="status",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="file_upload",
      *     in="formData",
      *     description="file_upload",
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
      *   path="/inteljaringan/{id}",
      *   tags={"Berantas Intel Jaringan"},
      *   summary="Delete Data by id",
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
      *     description="delete data by id",
      *     required=true,
      *     type="integer"
      *   ),
      *   @SWG\Response(response=200, description="successful operation"),
      *   @SWG\Response(response=406, description="not acceptable"),
      *   @SWG\Response(response=500, description="internal server error")
      * )
      *
      */
