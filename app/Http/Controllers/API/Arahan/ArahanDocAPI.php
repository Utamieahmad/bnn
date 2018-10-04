<?php

/**
 * @SWG\Post(
 *   path="/arahan",
 *   tags={"Arahan"},
 *   summary="List Arahan",
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
