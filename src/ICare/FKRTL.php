<?php

namespace Bridging\Bpjs\ICare;

class FKRTL extends ICareService
{
    public function validate($nomorKartu, $kodeDokter)
    {
        $payload = [
            'param' => $nomorKartu,
            'kodedokter' => $kodeDokter,
        ];

        $response = $this->post('api/rs/validate', $payload);

        return $this->responseDecoded($response);
    }
}
