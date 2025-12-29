<?php

namespace Bridging\Bpjs\ICare;

class FKTP extends ICareService
{
    public function validate($nomorKartu)
    {
        $payload = [
            'param' => $nomorKartu,
        ];

        $response = $this->post('api/pcare/validate', $payload);

        return $this->responseDecoded($response);
    }
}
