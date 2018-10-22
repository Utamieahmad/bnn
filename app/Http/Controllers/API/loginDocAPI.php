<?php

/**
 * @SWG\Post(
 *   path="/login",
 *   tags={"Login"},
 *   summary="Login user",
 *   operationId="getToken",
 *   @SWG\Parameter(
 *     name="email",
 *     in="formData",
 *     description="Email user.",
 *     required=true,
 *     type="string"
 *   ),
 *   @SWG\Parameter(
 *     name="password",
 *     in="formData",
 *     description="Password user.",
 *     required=true,
 *     type="string"
 *   ),
 *   @SWG\Info(
 *     title="API Login"
 *   ),
 *   @SWG\Response(response=200, description="successful operation"),
 *   @SWG\Response(response=406, description="not acceptable"),
 *   @SWG\Response(response=500, description="internal server error")
 * )
 *
 */