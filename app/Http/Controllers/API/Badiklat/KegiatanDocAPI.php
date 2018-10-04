<?php

     /**
      * @SWG\Get(
      *   path="/kegiatan",
      *   tags={"Badiklat Kegiatan"},
      *   summary="Get List Kegiatan",
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
       *   path="/kegiatan/{id}",
       *   tags={"Badiklat Kegiatan"},
       *   summary="Get Kegiatan by id",
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
        *   path="/kegiatan",
        *   tags={"Badiklat Kegiatan"},
        *   summary="List Kegiatan",
        *   operationId="StoreData",
        *   @SWG\Parameter(
        *     name="Authorization",
        *     in="header",
        *     description="Authorization Token",
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
        *     name="tgl_pelaksanaan",
        *     in="formData",
        *     description="tgl_pelaksanaan",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tempat",
        *     in="formData",
        *     description="tempat",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tempat_idprovinsi",
        *     in="formData",
        *     description="tempat_idprovinsi",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tempat_idkabkota",
        *     in="formData",
        *     description="tempat_idkabkota",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tempat_idkecamatan",
        *     in="formData",
        *     description="tempat_idkecamatan",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tempat_iddesa",
        *     in="formData",
        *     description="tempat_iddesa",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="total_hari_diklat",
        *     in="formData",
        *     description="total_hari_diklat",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="total_jam_pelajaran",
        *     in="formData",
        *     description="total_jam_pelajaran",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="total_narasumber_pengajar",
        *     in="formData",
        *     description="total_narasumber_pengajar",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="total_peserta",
        *     in="formData",
        *     description="total_peserta",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="syarat_mengikuti_diklat",
        *     in="formData",
        *     description="syarat_mengikuti_diklat",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="kodeanggaran",
        *     in="formData",
        *     description="kodeanggaran",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="anggaran_id",
        *     in="formData",
        *     description="anggaran_id",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="status",
        *     in="formData",
        *     description="status",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tgl_selesai",
        *     in="formData",
        *     description="tgl_selesai",
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
        *     name="jenis_diklat",
        *     in="formData",
        *     description="jenis_diklat",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tujuan_kegiatan",
        *     in="formData",
        *     description="tujuan_kegiatan",
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
           *   path="/kegiatan/{id}",
           *   tags={"Badiklat Kegiatan"},
           *   summary="update Badiklat Kegiatan by id",
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
           *     name="nama_kegiatan",
           *     in="formData",
           *     description="nama_kegiatan",
           *     required=true,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="tgl_pelaksanaan",
           *     in="formData",
           *     description="tgl_pelaksanaan",
           *     required=true,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="tempat",
           *     in="formData",
           *     description="tempat",
           *     required=true,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="tempat_idprovinsi",
           *     in="formData",
           *     description="tempat_idprovinsi",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="tempat_idkabkota",
           *     in="formData",
           *     description="tempat_idkabkota",
           *     required=true,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="tempat_idkecamatan",
           *     in="formData",
           *     description="tempat_idkecamatan",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="tempat_iddesa",
           *     in="formData",
           *     description="tempat_iddesa",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="total_hari_diklat",
           *     in="formData",
           *     description="total_hari_diklat",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="total_jam_pelajaran",
           *     in="formData",
           *     description="total_jam_pelajaran",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="total_narasumber_pengajar",
           *     in="formData",
           *     description="total_narasumber_pengajar",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="total_peserta",
           *     in="formData",
           *     description="total_peserta",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="syarat_mengikuti_diklat",
           *     in="formData",
           *     description="syarat_mengikuti_diklat",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="kodeanggaran",
           *     in="formData",
           *     description="kodeanggaran",
           *     required=true,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="anggaran_id",
           *     in="formData",
           *     description="anggaran_id",
           *     required=false,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="status",
           *     in="formData",
           *     description="status",
           *     required=true,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="tgl_selesai",
           *     in="formData",
           *     description="tgl_selesai",
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
           *     name="jenis_diklat",
           *     in="formData",
           *     description="jenis_diklat",
           *     required=true,
           *     type="string"
           *   ),
           *   @SWG\Parameter(
           *     name="tujuan_kegiatan",
           *     in="formData",
           *     description="tujuan_kegiatan",
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
           *   path="/kegiatan/{id}",
           *   tags={"Badiklat Kegiatan"},
           *   summary="Delete Badiklat Kegiatan by id",
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
