<?php

namespace App\Elastica;

use FOS\ElasticaBundle\Transformer\ModelToElasticaTransformerInterface;

class ModelToElasticaProviderTransformer implements ModelToElasticaTransformerInterface
{
    private $decorated;

    private $provider;

    private $entityClass;

    private $idGetter;

    public function __construct(
        ModelToElasticaTransformerInterface $decorated,
        AbstractProvider $provider,
        string $entityClass,
        string $idGetter
    ) {
        $this->decorated = $decorated;
        $this->provider = $provider;
        $this->entityClass = $entityClass;
        $this->idGetter = $idGetter;
    }

    public function transform($object, array $fields)
    {
        $dto = $object;
        if ($object instanceof $this->entityClass) {
            $objectId = $object->{$this->idGetter}();
            $documents = $this->provider->getByIds([$objectId]);

            $dto = reset($documents);
        }

        return $this->decorated->transform($dto, $fields);
    }
}
