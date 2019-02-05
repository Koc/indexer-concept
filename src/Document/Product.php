<?php

namespace App\Document;

class Product
{
    private $id;

    private $ean;

    private $offers;

    /**
     * @param Offer[] $offers
     */
    public function __construct(int $id, string $ean, array $offers)
    {
        $this->id = $id;
        $this->ean = $ean;
        $this->offers = $offers;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * @return Offer[]
     */
    public function getOffers(): array
    {
        return $this->offers;
    }
}
