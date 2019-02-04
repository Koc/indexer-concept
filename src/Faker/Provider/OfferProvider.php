<?php

namespace App\Faker\Provider;

use App\Entity\Offer;
use Faker\Provider\Base;

class OfferProvider extends Base
{
    public function offer(): Offer
    {
        return new Offer($this->generator->numberBetween(1, 999), $this->generator->company);
    }
}
