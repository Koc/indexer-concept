<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="offer")
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="offers")
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string")
     */
    private $companyName;

    public function __construct(int $price, string $companyName)
    {
        $this->price = $price;
        $this->companyName = $companyName;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }
}
