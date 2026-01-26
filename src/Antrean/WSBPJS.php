<?php

namespace Bridging\Bpjs\Antrean;

use Bridging\Bpjs\PCare\PcareService;

class WSBPJS extends PcareService
{
    /**
     * @var string
     */
    protected $feature = 'antrean';

    public function add()
    {
        $this->feature .= "/add";
        return $this;
    }

    public function panggil()
    {
        $this->feature .= "/panggil";
        return $this;
    }

    public function batal()
    {
        $this->feature .= "/batal";
        return $this;
    }
}
