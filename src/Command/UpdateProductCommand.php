<?php

namespace App\Command;

use App\Entity\Offer;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateProductCommand extends Command
{
    protected static $defaultName = 'app:add-offer';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('product-id', InputArgument::REQUIRED);
        $this->addArgument('offer-price', InputArgument::REQUIRED);
        $this->addArgument('company-name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('product-id');
        $product = $this->em->find(Product::class, $id);

        if (null === $product) {
            throw new \InvalidArgumentException('Product not found.');
        }

        $price = $input->getArgument('offer-price');
        $companyName = $input->getArgument('company-name');
        $product->addOffer(new Offer($price, $companyName));

        $this->em->flush();
    }
}
