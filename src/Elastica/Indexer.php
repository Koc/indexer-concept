<?php

namespace App\Elastica;

use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;

class Indexer
{
    private $provider;

    private $persister;

    public function __construct(AbstractProvider $provider, ObjectPersisterInterface $persister)
    {
        $this->provider = $provider;
        $this->persister = $persister;
    }

    public function reindexByIds(array $ids): void
    {
        $dtoItems = $this->provider->getByIds($ids);
        $this->persister->replaceMany($dtoItems);
    }
}
