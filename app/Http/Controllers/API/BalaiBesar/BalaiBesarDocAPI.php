<?php

/**
    * @SWG\Get(
    *   path="/balaiBesar",
    *   tags={"Balai Besar"},
    *   summary="Get List Balai Besar",
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
       *   path="/balaiBesar/{id}",
       *   tags={"Balai Besar"},
       *   summary="Get Balai Besar by id",
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
        *   path="/balaiBesar",
        *   tags={"Balai Besar"},
        *   summary="Store data Balai Besar",
        *   operationId="Store data",
        *   @SWG\Parameter(
        *     name="Authorization",
        *     in="header",
        *     description="Authorization Token",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="jenis_kegiatan",
        *     in="formData",
        *     description="jenis_kegiatan",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="nama_kegiatan",
        *     in="formData",
        *     description="nama_kegiatan",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tanggal_mulai",
        *     in="formData",
        *     description="tanggal_mulai",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tanggal_selesai",
        *     in="formData",
        *     description="tanggal_selesai",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="nomor_agenda",
        *     in="formData",
        *     description="nomor_agenda",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="nomor_surat",
        *     in="formData",
        *     description="nomor_surat",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="kode_instansi",
        *     in="formData",
        *     description="kode_instansi",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="meta_instansi",
        *     in="formData",
        *     description="meta_instansi",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="meta_bnn_instansi",
        *     in="formData",
        *     description="meta_bnn_instansi",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="meta_materi",
        *     in="formData",
        *     description="meta_materi",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="bnn_jumlah_peserta",
        *     in="formData",
        *     description="bnn_jumlah_peserta",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="file_laporan",
        *     in="formData",
        *     description="file_laporan",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="status",
        *     in="formData",
        *     description="status",
        *     required=true,
        *     type="string"
        *   )
        *   ,
        *   @SWG\Response(response=200, description="successful operation"),
        *   @SWG\Response(response=406, description="not acceptable"),
        *   @SWG\Response(response=500, description="internal server error")
        * )
        *
        */

        /**
          * @SWG\Put(
          *   path="/balaiBesar/{id}",
          *   tags={"Balai Besar"},
          *   summary="update Balai Besar by id",
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
        *     name="jenis_kegiatan",
        *     in="formData",
        *     description="jenis_kegiatan",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="nama_kegiatan",
        *     in="formData",
        *     description="nama_kegiatan",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tanggal_mulai",
        *     in="formData",
        *     description="tanggal_mulai",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tanggal_selesai",
        *     in="formData",
        *     description="tanggal_selesai",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="nomor_agenda",
        *     in="formData",
        *     description="nomor_agenda",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="nomor_surat",
        *     in="formData",
        *     description="nomor_surat",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="kode_instansi",
        *     in="formData",
        *     description="kode_instansi",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="meta_instansi",
        *     in="formData",
        *     description="meta_instansi",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="meta_bnn_instansi",
        *     in="formData",
        *     description="meta_bnn_instansi",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="meta_materi",
        *     in="formData",
        *     description="meta_materi",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="bnn_jumlah_peserta",
        *     in="formData",
        *     description="bnn_jumlah_peserta",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="file_laporan",
        *     in="formData",
        *     description="file_laporan",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="status",
        *     in="formData",
        *     description="status",
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
          *   path="/balaiBesar/{id}",
          *   tags={"Balai Besar"},
          *   summary="Delete Balai Besar by id",
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
