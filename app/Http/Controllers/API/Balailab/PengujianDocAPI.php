<?php

    /**
     * @SWG\Post(
     *   path="/pengujian",
     *   tags={"Balai Lab Pengujian"},
     *   summary="List Pengujian",
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
      *   path="/pengujian",
      *   tags={"Balai Lab Pengujian"},
      *   summary="Get List Pengujian",
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
       *   path="/pengujian/{id}",
       *   tags={"Balai Lab Pengujian"},
       *   summary="Get Pengujian by id",
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
