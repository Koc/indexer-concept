<?php

namespace App\Job;

use App\Elastica\Indexer;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ReindexChangedProducts
{
    private $em;

    private $indexer;

    public function __construct(EntityManagerInterface $em, Indexer $indexer)
    {
        $this->em = $em;
        $this->indexer = $indexer;
    }

    public function execute(): void
    {
        $this->indexer->reindexByIds($this->getChangedProductIds());
    }

    private function getChangedProductIds(): array
    {
        $rows = $this->em
            ->createQueryBuilder()
            ->select('product.id')
            ->from(Product::class, 'product')
            ->andWhere('product.updatedAt > :last_synced_at')
            ->setParameter('last_synced_at', $this->getLastSyncedAt())
            ->getQuery()
            ->getResult();

        return array_column($rows, 'id');
    }

    public function getLastSyncedAt(): \DateTimeInterface
    {
        return new \DateTime('-10 minutes');
    }
}
