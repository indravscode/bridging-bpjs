<?php
namespace Bridging\Bpjs\PCare;

use Bridging\Bpjs\PCare\PcareService;

class Poli extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'poli';

    public function fktp()
    {
        $this->feature .= "/fktp";
        return $this;
    }
}
