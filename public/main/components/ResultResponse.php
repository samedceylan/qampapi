<?php

use EF2\Http\Response;

class ResultResponse
{
    /**
     * @param $code
     * @param $message
     * @param null $errors
     * @param null $extras
     *
     *
     * reponse mekanizması,
     *
     * code: hata kodu veya onay kodu
     * message: mesaj (front end veya mobil geliştiricisinin göreceği bilgilendirici mesaj)
     * errors: kullanıcı göreceği hata kodları, array veri içerir
     * extras: sonuç başarılıysa extra bilgiler yer alır, array veri içerir
     */
    public function response($code, $message, $errors = null, $extras = null)
    {

        $response = new Response;
        $response->json(["code" => $code, "message" => $message, "errors" => $errors, "extras" => $extras]);
        exit;
    }
}