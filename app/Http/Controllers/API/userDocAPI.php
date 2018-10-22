<?php

/**
    * @SWG\Get(
    *   path="/users",
    *   tags={"User"},
    *   summary="Get List User",
    *   operationId="get data",
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
    *   path="/users/{id}",
    *   tags={"User"},
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
   *   tags={"User"},
   *   summary="Create User",
   *   operationId="postUser",
   *   @SWG\Parameter(
   *     name="group_id",
   *     in="formData",
   *     description="ID Group",
   *     required=true,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_name",
   *     in="formData",
   *     description="username",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_login",
   *     in="formData",
   *     description="user login",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_email",
   *     in="formData",
   *     description="User email",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_pass",
   *     in="formData",
   *     description="user password",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="active_flag",
   *     in="formData",
   *     description="active flag",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="description",
   *     in="formData",
   *     description="deskripsi",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="wilayah_id",
   *     in="formData",
   *     description="id wilayah",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_pass_decrypt",
   *     in="formData",
   *     description="password decrypt",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="remember_token",
   *     in="formData",
   *     description="token",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="email",
   *     in="formData",
   *     description="Email user",
   *     required=true,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="password",
   *     in="formData",
   *     description="password",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="nip",
   *     in="formData",
   *     description="NIP",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="flag",
   *     in="formData",
   *     description="ID Group",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="jabatan_pegawai",
   *     in="formData",
   *     description="jabatan pegawai",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="telp_pegawai",
   *     in="formData",
   *     description="no telepon pegawai",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="lokasi_kerja",
   *     in="formData",
   *     description="Lokasi kerja",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="foto_pegawai",
   *     in="formData",
   *     description="Foto Pegawai",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="email_token",
   *     in="formData",
   *     description="token email",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="temp_change_email",
   *     in="formData",
   *     description="ID Group",
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
  * @SWG\Put(
  *   path="/users/{id}",
  *   tags={"User"},
  *   summary="update User by id",
  *   operationId="update data by id",
  *   @SWG\Parameter(
  *     name="id",
  *     in="path",
  *     description="update data by id",
  *     required=true,
  *     type="integer"
  *   ),
  *   @SWG\Parameter(
   *     name="group_id",
   *     in="formData",
   *     description="ID Group",
   *     required=true,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_name",
   *     in="formData",
   *     description="username",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_login",
   *     in="formData",
   *     description="user login",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_email",
   *     in="formData",
   *     description="User email",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_pass",
   *     in="formData",
   *     description="user password",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="active_flag",
   *     in="formData",
   *     description="active flag",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="description",
   *     in="formData",
   *     description="deskripsi",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="wilayah_id",
   *     in="formData",
   *     description="id wilayah",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="user_pass_decrypt",
   *     in="formData",
   *     description="password decrypt",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="remember_token",
   *     in="formData",
   *     description="token",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="email",
   *     in="formData",
   *     description="Email user",
   *     required=true,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="password",
   *     in="formData",
   *     description="password",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="nip",
   *     in="formData",
   *     description="NIP",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="flag",
   *     in="formData",
   *     description="ID Group",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="jabatan_pegawai",
   *     in="formData",
   *     description="jabatan pegawai",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="telp_pegawai",
   *     in="formData",
   *     description="no telepon pegawai",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="lokasi_kerja",
   *     in="formData",
   *     description="Lokasi kerja",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="foto_pegawai",
   *     in="formData",
   *     description="Foto Pegawai",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="email_token",
   *     in="formData",
   *     description="token email",
   *     required=false,
   *     type="string"
   *   ),
   *   @SWG\Parameter(
   *     name="temp_change_email",
   *     in="formData",
   *     description="ID Group",
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
  *   path="/users/{id}",
  *   tags={"User"},
  *   summary="Delete User by id",
  *   operationId="delete data by id",
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