<?php

/**
      * @SWG\Get(
      *   path="/entripasien",
      *   tags={"Rehab Entri Pasien"},
      *   summary="Entri Pasien",
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
      *   path="/entripasien/{id}",
      *   tags={"Rehab Entri Pasien"},
      *   summary="Entri Pasien",
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
      *     description="ID data",
      *     required=true,
      *     type="integer"
      *   ),
      *   @SWG\Response(response=200, description="successful operation"),
      *   @SWG\Response(response=406, description="not acceptable"),
      *   @SWG\Response(response=500, description="internal server error")
      * )
      *
      */