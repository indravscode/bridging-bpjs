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
        $status = $status ? 'true' : 'false';
        $this->feature .= "/rawatInap/{$status}";
        return $this;
    }
}
