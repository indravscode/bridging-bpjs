<?php

namespace Bridging\Bpjs\Antrean;

use Bridging\Bpjs\PCare\PcareService;

class ReferensiDokter extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'ref/dokter';

    /**
     * @param string $kodePoli 
     */
    public function kodePoli($kodePoli)
    {
        $this->feature .= "/kodepoli/{$kodePoli}";
        return $this;
    }

    /**
     * @param string $tanggal YYYY-MM-DD
     */
    public function tanggal($tanggal)
    {
        $this->feature .= "/tanggal/{$tanggal}";
        return $this;
    }
}
