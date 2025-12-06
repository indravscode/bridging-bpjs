<?php
namespace Bridging\Bpjs\PCare;

use Bridging\Bpjs\PCare\PcareService;

class Alergi extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'alergi';

    public function jenis($jenisAlergi)
    {
        $this->feature .= "/jenis/{$jenisAlergi}";
        return $this;
    }
}
