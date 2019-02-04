<?php

namespace App\Document;

class Product
{
    private $id;

    private $name;

    private $offers;

    /**
     * @param Offer[] $offers
     */
    public function __construct(int $id, string $name, array $offers)
    {
        $this->id = $id;
        $this->name = $name;
        $this->offers = $offers;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Offer[]
     */
    public function getOffers(): array
    {
        return $this->offers;
    }
}
