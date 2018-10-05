<?php

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
        *     name="nomor_surat_permohonan_pengajuan",
        *     in="formData",
        *     description="nomor_surat_permohonan_pengajuan",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tgl_surat",
        *     in="formData",
        *     description="tgl_surat",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="perihal_surat",
        *     in="formData",
        *     description="perihal_surat",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="no_lplkn",
        *     in="formData",
        *     description="no_lplkn",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="tgl_lplkn",
        *     in="formData",
        *     description="tgl_lplkn",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="jenis_kasus",
        *     in="formData",
        *     description="jenis_kasus",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="nama_instansi",
        *     in="formData",
        *     description="nama_instansi",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="nama_pengirim",
        *     in="formData",
        *     description="nama_pengirim",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="pangkat_gol",
        *     in="formData",
        *     description="pangkat_gol",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="no_telp_pengirim",
        *     in="formData",
        *     description="no_telp_pengirim",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="idjenisbarangbukti",
        *     in="formData",
        *     description="idjenisbarangbukti",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="idbarangbukti",
        *     in="formData",
        *     description="idbarangbukti",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="kuantitas",
        *     in="formData",
        *     description="kuantitas",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="satuan",
        *     in="formData",
        *     description="satuan",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="warna",
        *     in="formData",
        *     description="warna",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="logo",
        *     in="formData",
        *     description="logo",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="laporan_hasil",
        *     in="formData",
        *     description="laporan_hasil",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="hasil_uji",
        *     in="formData",
        *     description="hasil_uji",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="status_aktif",
        *     in="formData",
        *     description="status_aktif",
        *     required=true,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="periode",
        *     in="formData",
        *     description="periode",
        *     required=false,
        *     type="string"
        *   ),
        *   @SWG\Parameter(
        *     name="kode_jnsbrgbukti",
        *     in="formData",
        *     description="kode_jnsbrgbukti",
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
          *   path="/pengujian/{id}",
          *   tags={"Balai Lab Pengujian"},
          *   summary="update Balai Lab Pengujian by id",
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
          *     name="nomor_surat_permohonan_pengajuan",
          *     in="formData",
          *     description="nomor_surat_permohonan_pengajuan",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="tgl_surat",
          *     in="formData",
          *     description="tgl_surat",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="perihal_surat",
          *     in="formData",
          *     description="perihal_surat",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="no_lplkn",
          *     in="formData",
          *     description="no_lplkn",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="tgl_lplkn",
          *     in="formData",
          *     description="tgl_lplkn",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="jenis_kasus",
          *     in="formData",
          *     description="jenis_kasus",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="nama_instansi",
          *     in="formData",
          *     description="nama_instansi",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="nama_pengirim",
          *     in="formData",
          *     description="nama_pengirim",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="pangkat_gol",
          *     in="formData",
          *     description="pangkat_gol",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="no_telp_pengirim",
          *     in="formData",
          *     description="no_telp_pengirim",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="idjenisbarangbukti",
          *     in="formData",
          *     description="idjenisbarangbukti",
          *     required=false,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="idbarangbukti",
          *     in="formData",
          *     description="idbarangbukti",
          *     required=false,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="kuantitas",
          *     in="formData",
          *     description="kuantitas",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="satuan",
          *     in="formData",
          *     description="satuan",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="warna",
          *     in="formData",
          *     description="warna",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="logo",
          *     in="formData",
          *     description="logo",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="laporan_hasil",
          *     in="formData",
          *     description="laporan_hasil",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="hasil_uji",
          *     in="formData",
          *     description="hasil_uji",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="status_aktif",
          *     in="formData",
          *     description="status_aktif",
          *     required=true,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="periode",
          *     in="formData",
          *     description="periode",
          *     required=false,
          *     type="string"
          *   ),
          *   @SWG\Parameter(
          *     name="kode_jnsbrgbukti",
          *     in="formData",
          *     description="kode_jnsbrgbukti",
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
          *   path="/pengujian/{id}",
          *   tags={"Balai Lab Pengujian"},
          *   summary="Delete Balai Lab Pengujian by id",
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
