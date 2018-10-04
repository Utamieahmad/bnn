<?php

 /**
  * @SWG\Get(
  *   path="/dpo",
  *   tags={"DPO"},
  *   summary="Get List DPO",
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
   *   path="/dpo/{id}",
   *   tags={"DPO"},
   *   summary="Get DPO by id",
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
