<?php

 /**
  * @SWG\Get(
  *   path="/arahan",
  *   tags={"Arahan"},
  *   summary="Get List Arahan",
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
   *   path="/arahan/{id}",
   *   tags={"Arahan"},
   *   summary="Get Arahan by id",
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
    *   path="/arahan",
    *   tags={"Arahan"},
    *   summary="Store data arahan",
    *   operationId="Store data",
    *   @SWG\Parameter(
    *     name="Authorization",
    *     in="header",
    *     description="Authorization Token",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_arahan",
    *     in="formData",
    *     description="tgl_arahan",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="satker",
    *     in="formData",
    *     description="satker",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="tgl_kadaluarsa",
    *     in="formData",
    *     description="tgl_kadaluarsa",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="judul_arahan",
    *     in="formData",
    *     description="judul_arahan",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="isi_arahan",
    *     in="formData",
    *     description="isi_arahan",
    *     required=true,
    *     type="string"
    *   ),
    *   @SWG\Parameter(
    *     name="status",
    *     in="formData",
    *     description="status",
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
      *   path="/arahan/{id}",
      *   tags={"Arahan"},
      *   summary="update Arahan by id",
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
      *     name="tgl_arahan",
      *     in="formData",
      *     description="tgl_arahan",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="satker",
      *     in="formData",
      *     description="satker",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="tgl_kadaluarsa",
      *     in="formData",
      *     description="tgl_kadaluarsa",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="judul_arahan",
      *     in="formData",
      *     description="judul_arahan",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="isi_arahan",
      *     in="formData",
      *     description="isi_arahan",
      *     required=true,
      *     type="string"
      *   ),
      *   @SWG\Parameter(
      *     name="status",
      *     in="formData",
      *     description="status",
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
      *   path="/arahan/{id}",
      *   tags={"Arahan"},
      *   summary="Delete Arahan by id",
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
