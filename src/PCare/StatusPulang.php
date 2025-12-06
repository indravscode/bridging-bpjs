<?php
namespace Bridging\Bpjs\PCare;

use Bridging\Bpjs\PCare\PcareService;

class StatusPulang extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'statuspulang';

    public function rawatInap($status = true)
    {
        $this->feature .= "/rawatInap/{$status}";
        return $this;
    }
}
