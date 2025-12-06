<?php
namespace Bridging\Bpjs\PCare;

use Bridging\Bpjs\PCare\PcareService;

class Peserta extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'peserta';

    public function jenisKartu($jenisKartu)
    {
        $this->feature .= "/{$jenisKartu}";
        return $this;
    }
}
