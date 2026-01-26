<?php

namespace Bridging\Bpjs\Antrean;

use Bridging\Bpjs\PCare\PcareService;

class ReferensiPoli extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'ref/poli';

    /**
     * @param string $tanggal YYYY-MM-DD
     */
    public function tanggal($tanggal)
    {
        $this->feature .= "/tanggal/{$tanggal}";
        return $this;
    }
}
