<?php

namespace App\Elastica;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\ElasticaBundle\Provider\PagerfantaPager;
use FOS\ElasticaBundle\Provider\PagerInterface;
use FOS\ElasticaBundle\Provider\PagerProviderInterface;
use Pagerfanta\Adapter\CallbackAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractProvider implements PagerProviderInterface
{
    protected $em;

    protected $options;

    public function __construct(EntityManagerInterface $em, string $entityClass, array $options = [])
    {
        $this->em = $em;
        $this->options = array_replace(
            [
                'entity_class' => $entityClass,
                'query_builder_method_name' => 'createQueryBuilder',
                'hydration_mode' => AbstractQuery::HYDRATE_ARRAY,
                'query_builder_method_args' => ['entity'],
                'entity_alias' => 'entity',
                'id_field' => 'id',
                'requires_distinct_count' => false,
                'supports_ids_ordering' => false,
            ],
            $options
        );
    }

    /**
     * @param array $options
     *
     * @return PagerInterface
     */
    public function provide(array $options = array())
    {
        $getNbResults = function () use ($options) {
            return $this->getIdsCount();
        };

        $getSliceCallback = function ($offset, $length) use ($options) {
            $ids = $this->getIdsBatch($offset, $length);

            return $this->getByIds($ids);
        };

        return new PagerfantaPager(new Pagerfanta(new CallbackAdapter($getNbResults, $getSliceCallback)));
    }

    public function getIdsCount(): int
    {
        $queryBuilder = $this->getQueryBuilder();

        $expr = $queryBuilder->expr();
        $countExpr = $expr->count($this->getIdField());
        if ($this->options['requires_distinct_count']) {
            $countExpr = $expr->countDistinct($this->getIdField());
        }

        $queryBuilder
            ->select($countExpr);

        $this->prepareSelectCountQueryBuilder($queryBuilder);

        return $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getIdsBatch(int $offset, int $length): array
    {
        $queryBuilder = $this
            ->getQueryBuilder()
            ->select(sprintf('%s as _id', $this->getIdField()))
            ->orderBy($this->getIdField())
            ->setFirstResult($offset)
            ->setMaxResults($length);

        $this->prepareSelectIdsQueryBuilder($queryBuilder);

        $result = $queryBuilder
            ->getQuery()
            ->getResult();

        return array_column($result, '_id');
    }

    public function getByIds(array $ids): array
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->andWhere($queryBuilder->expr()->in($this->getIdField(), ':ids'))
            ->setParameter('ids', $ids);

        $this->prepareSelectQueryBuilder($queryBuilder);

        $result = $queryBuilder
            ->getQuery()
            ->getResult($this->options['hydration_mode']);

        if ($result) {
            $result = $this->transformResult($result);
        }

        return $result;
    }

    protected function prepareSelectIdsQueryBuilder(QueryBuilder $queryBuilder): void
    {
        // override this method if you need modify query builder
    }

    protected function prepareSelectCountQueryBuilder(QueryBuilder $queryBuilder): void
    {
        // override this method if you need modify query builder
    }

    protected function prepareSelectQueryBuilder(QueryBuilder $queryBuilder): void
    {
        // override this method if you need modify query builder
    }

    protected function transformResult(array $result): array
    {
        // override this method if you need modify results
        return $result;
    }

    private function getQueryBuilder(): QueryBuilder
    {
        $repository = $this->em->getRepository($this->options['entity_class']);
        $method = $this->options['query_builder_method_name'];

        return $repository->$method(...$this->options['query_builder_method_args']);
    }

    protected function getIdField(): string
    {
        return sprintf('%s.%s', $this->options['entity_alias'], $this->options['id_field']);
    }
}
