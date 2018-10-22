<?php

/**
 * @SWG\Post(
 *   path="/listberantasrazia",
 *   tags={"Berantas Razia"},
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
    *   path="/berantasrazia",
    *   tags={"Berantas Razia"},
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
   *   path="/berantasrazia/{id}",
   *   tags={"Berantas Razia"},
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
    *   path="/berantasrazia",
    *   tags={"Berantas Razia"},
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
      *     name="tgl_razia",
      *     in="formData",
      *     description="tgl_razia",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="lokasi",
      *     in="formData",
      *     description="lokasi",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="koordinat",
      *     in="formData",
      *     description="koordinat",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="uraian_singkat",
      *     in="formData",
      *     description="uraian_singkat",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="jumlah_dirazia",
      *     in="formData",
      *     description="jumlah_dirazia",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="jumlah_terindikasi",
      *     in="formData",
      *     description="jumlah_terindikasi",
      *     required=false,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="jumlah_ditemukan",
      *     in="formData",
      *     description="jumlah_ditemukan",
      *     required=false,
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
      *   path="/berantasrazia/{id}",
      *   tags={"Berantas Razia"},
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
