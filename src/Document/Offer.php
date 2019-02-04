<?php

namespace App\Document;

class Offer
{
    private $id;

    private $price;

    private $companyName;

    public function __construct(int $id, int $price, string $companyName)
    {
        $this->id = $id;
        $this->price = $price;
        $this->companyName = $companyName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }
}
