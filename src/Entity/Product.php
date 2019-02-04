<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $ean;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="product", cascade={"persist"})
     *
     * @var Collection|Offer[]
     */
    private $offers;

    public function __construct(string $ean)
    {
        $this->ean = $ean;
        $this->offers = new ArrayCollection();
    }

    public function addOffer(Offer $offer): void
    {
        $offer->setProduct($this);
        $this->offers->add($offer);
    }
}
