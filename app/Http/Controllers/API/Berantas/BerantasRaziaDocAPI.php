<?php

/**
 * @SWG\Post(
 *   path="/listberantasrazia",
 *   tags={"Berantas Razia"},
 *   summary="List Berantas Razia",
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
  *   path="/berantasrazia",
  *   tags={"Berantas Razia"},
  *   summary="Get List Berantas Razia",
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
   *   path="/berantasrazia/{id}",
   *   tags={"Berantas Razia"},
   *   summary="Get Berantas Razia by id",
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
    *   path="/berantasrazia",
    *   tags={"Berantas Razia"},
    *   summary="Store data Berantas Razia",
    *   operationId="Store data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_razia",
    *     in="formData",
    *     description="tgl_razia",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="lokasi",
    *     in="formData",
    *     description="lokasi",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="koordinat",
    *     in="formData",
    *     description="koordinat",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="uraian_singkat",
    *     in="formData",
    *     description="uraian_singkat",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_dirazia",
    *     in="formData",
    *     description="jumlah_dirazia",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_terindikasi",
    *     in="formData",
    *     description="jumlah_terindikasi",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="jumlah_ditemukan",
    *     in="formData",
    *     description="jumlah_ditemukan",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="keterangan_lainnya",
    *     in="formData",
    *     description="keterangan_lainnya",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="foto1",
    *     in="formData",
    *     description="foto1",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="foto2",
    *     in="formData",
    *     description="foto2",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="foto3",
    *     in="formData",
    *     description="foto3",
    *     required=false,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="id_instansi",
    *     in="formData",
    *     description="id_instansi",
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
      * @SWG\Put(
      *   path="/berantasrazia/{id}",
      *   tags={"Berantas Razia"},
      *   summary="update Berantas Razia by id",
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
      *     name="tgl_razia",
      *     in="formData",
      *     description="tgl_razia",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="lokasi",
      *     in="formData",
      *     description="lokasi",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="koordinat",
      *     in="formData",
      *     description="koordinat",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="uraian_singkat",
      *     in="formData",
      *     description="uraian_singkat",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="jumlah_dirazia",
      *     in="formData",
      *     description="jumlah_dirazia",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="jumlah_terindikasi",
      *     in="formData",
      *     description="jumlah_terindikasi",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="jumlah_ditemukan",
      *     in="formData",
      *     description="jumlah_ditemukan",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="keterangan_lainnya",
      *     in="formData",
      *     description="keterangan_lainnya",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="foto1",
      *     in="formData",
      *     description="foto1",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="foto2",
      *     in="formData",
      *     description="foto2",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="foto3",
      *     in="formData",
      *     description="foto3",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="id_instansi",
      *     in="formData",
      *     description="id_instansi",
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
      * @SWG\Delete(
      *   path="/berantasrazia/{id}",
      *   tags={"Berantas Razia"},
      *   summary="Delete Berantas Razia by id",
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
