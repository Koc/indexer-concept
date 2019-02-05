<?php

namespace App\Elastica;

use App\Document\Offer;
use App\Document\Product;
use App\Entity\Offer as OfferEntity;

class ProductProvider extends AbstractProvider
{
    protected function transformResult(array $result): array
    {
        $productIds = array_column($result, 'id');
        $offersPerProductIds = $this->getOffersPerProductIds($productIds);

        $documents = [];
        foreach ($result as $productRow) {
            $id = $productRow['id'];
            $offers = $offersPerProductIds[$id] ?? [];
            $documents[] = new Product($id, $productRow['ean'], $offers);
        }

        return $documents;
    }

    /**
     * @param int[] $productIds
     * @return Offer[][]
     */
    private function getOffersPerProductIds(array $productIds): array
    {
        $offerRows = $this->em->createQueryBuilder()
            ->select('offer.id')
            ->addSelect('IDENTITY(offer.product) AS productId')
            ->addSelect('offer.price')
            ->addSelect('offer.companyName')
            ->from(OfferEntity::class, 'offer')
            ->where('offer.product IN (:product_ids)')
            ->setParameter('product_ids', $productIds)
            ->getQuery()
            ->getResult();

        $offersPerProductIds = [];
        foreach ($offerRows as $offerRow) {
            $productId = $offerRow['productId'];
            $offersPerProductIds[$productId][] = new Offer(
                $offerRow['id'],
                $offerRow['price'],
                $offerRow['companyName']
            );
        }

        return $offersPerProductIds;
    }
}
