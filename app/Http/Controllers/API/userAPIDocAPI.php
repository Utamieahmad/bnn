<?php

/**
 * @SWG\Get(
 *   path="/users",
 *   tags={"List User"},
 *   summary="Get List User",
 *   operationId="get data",
 *   @SWG\Response(response=200, description="successful operation"),
 *   @SWG\Response(response=406, description="not acceptable"),
 *   @SWG\Response(response=500, description="internal server error")
 * )
 *
 */

 /**
  * @SWG\Get(
  *   path="/users/{id}",
  *   tags={"List User"},
  *   summary="Get User by id",
  *   operationId="get data by id",
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
   *   path="/users",
   *   tags={"List User"},
   *   summary="List User",
   *   operationId="getList",
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
