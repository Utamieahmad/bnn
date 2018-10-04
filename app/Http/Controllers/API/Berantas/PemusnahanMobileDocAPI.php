<?php

/**
 * @SWG\Post(
 *   path="/pemusnahanmobile",
 *   tags={"List Pemusnahan Mobile"},
 *   summary="Post Pemusnahan Mobile",
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
 * @SWG\Post(
 *   path="/listpemusnahanmobile",
 *   tags={"List Pemusnahan Mobile"},
 *   summary="Post List Pemusnahan Mobile",
 *   operationId="postList",
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
?>