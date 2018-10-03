<?php

namespace App\Transformers;

class Json
{
    public static function response($data = null, $status = null, $comment = "tidak ada pesan", $code = 200, $paginate = null)
    {
        if ($paginate==null) {
            return [
                //'id'        => $id,
                'code'      => $code,
                'status'    => $status,
                'comment'   => $comment,
                'data'      => $data,
            ];
        } else {
            return [
                 //'id'        => $id,
                 'code'      => $code,
                 'status'    => $status,
                 'comment'   => $comment,
                 'data'      => $data,
                 'paginate'  => $paginate,
             ];
        }
    }

    public static function loginresponse($data = null, $status = null, $comment = "tidak ada pesan", $code = 200)
    {
        return [
            'code'          => $code,
            'status'        => $status,
            'comment'       => $comment,
            'data'          => $data,
        ];
    }
    //
    // public static function loginresponseMobile($data = null, $status = "kosong", $comment = "tidak ada pesan", $token = "qwertyasdfghzxcvbn", $tomorrow = "null")
    // {
    //     return [
    //         'access_token'   => $token,
    //         'expires_in'     => $tomorrow,
    //         'id'             => $data['id'],
    //         'name'           => $data['name'],
    //         'status'         => $status,
    //         'comment'        => $comment,
    //     ];
    // }
}
