<?php
namespace Bridging\Bpjs\PCare;

use Bridging\Bpjs\PCare\PcareService;

class Skrining extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'skrining';

    public function rekap()
    {
        $this->feature = 'skrinning/rekap';
        return $this;
    }

    public function peserta()
    {
        $this->feature = "skrinning/peserta";
        return $this;
    }

    public function prolanisDm()
    {
        $this->feature = "skrinning/prolanis/dm";
        return $this;
    }

    public function prolanisHt()
    {
        $this->feature = "skrinning/prolanis/ht";
        return $this;
    }
}