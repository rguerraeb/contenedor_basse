<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PromoCategoryRepository extends EntityRepository
{
    /**
     * Search in database with search parameters
     *
     * @param Promo $promo promo to use as filter
     * @return array results of query
     */
    public function findByNotSubcategory() {
        $em = $this->getEntityManager();

        $qb  = $em->createQueryBuilder();
        $sub = $qb;

        $sub = $qb
            ->select('IDENTITY(ps.subcategory)')
            ->from('AppBundle:PromoSubcategory', 'ps');

        $qb  = $em->createQueryBuilder();

        return $qb
            ->select('pc')
            ->from('AppBundle:PromoCategory', 'pc')
            /*
            ->where(
                $qb->expr()->notIn(
                    'pc.promoCategoryId',  $sub->getDQL()
                )
            );
            */
            ->where('pc.promoCategoryId = 1')
        ;
    }

    /**
     * Find subcategories of a category
     *
     * @param int $category parent PromoCategory
     * @return array list of PromoCategory subcategories
     */
    public function findSubcategory($category) {
        $query = $this->findSubcategoryQuery($category);

        return $query->getQuery()->getResult();
    }

    /**
     * Find subcategories of a category
     *
     * @param int $category parent PromoCategory
     * @return QueryBuilder query object with query to get results
     */
    public function findSubcategoryQuery($category) {
        $em = $this->getEntityManager();
        $query = $em
            ->createQueryBuilder()
            ->select('pc')
            ->from('AppBundle:PromoSubcategory', 'ps')
            ->from('AppBundle:PromoCategory', 'pc')
            ->where('ps.subcategory = pc');

        if ($category) {
            $query
                ->andWhere('ps.parentCategory = :category')
                ->setParameter('category', $category);
        }

        return $query;
    }

    /**
     * Looks for the parent category of a subcategory
     *
     * @param PromoCategory $subcategory category to look it's parent
     * @return array PromoCategory who are parent of the subcategory
     */
    public function findParentCategory($subcategory) {
        $em = $this->getEntityManager();

        $qb  = $em->createQueryBuilder();
        $sub = $qb;

        $sub = $qb
            ->select('pc')
            ->from('AppBundle:PromoSubcategory', 'ps')
            ->from('AppBundle:PromoCategory', 'pc')
            ->where('ps.subcategory = :subcategory')
            ->andWhere('pc = ps.parentCategory')
            ->setParameter('subcategory', $subcategory);

        return $sub->getQuery()->getOneOrNullResult();
    }
}
