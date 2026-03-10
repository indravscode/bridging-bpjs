<?php
namespace Bridging\Bpjs\PCare;

use Bridging\Bpjs\PCare\PcareService;

class Kunjungan extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'kunjungan';

    public function store($data = [])
    {
        $response = $this->post('kunjungan/v1', $data);

        return $this->responseDecoded($response);
    }

    public function rujukan($nomorKunjungan)
    {
        $this->feature .= "/rujukan/{$nomorKunjungan}";
        return $this;
    }

    public function riwayat($nomorKartu)
    {
        $this->feature .= "/peserta/{$nomorKartu}";
        return $this;
    }
}
