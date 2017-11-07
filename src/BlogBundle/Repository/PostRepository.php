<?php

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{

    public function getPublishedPost($slug)
    {
        $qb = $this->getQueryBuilder([
            'status' => 'published'
        ]);

        $qb->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }
    
    /**
     * @param array $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder(array $params = [])
    {
        
        $qb = $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't');

        if (!empty($params['status'])) {
            if ($params['status'] == 'published') {
                $qb->where('p.publishedDate <= :currentDate AND p.publishedDate IS NOT NULL')
                    ->setParameter('currentDate', new \DateTime());
            } else if ($params['status'] == 'unpublished') {
                $qb->where('p.publishedDate > :currentDate OR p.publishedDate IS NULL')
                    ->setParameter('currentDate', new \DateTime());
            }
        }

        if (!empty($params['orderBy'])) {
            $orderDir = !empty($params['orderDir']) ? $params['orderDir'] : null;
            $qb->orderBy($params['orderBy'], $orderDir);
        }

        if (!empty($params['categorySlug'])) {
            $qb->andWhere('c.slug = :categorySlug')
                ->setParameter('categorySlug', $params['categorySlug']);
        }

        if (!empty($params['tagSlug'])) {
            $qb->andWhere('t.slug = :tagSlug')
                ->setParameter('tagSlug', $params['tagSlug']);
        }

        if (!empty($params['search'])) {
            $searchParam = '%' . $params['search'] . '%';
            $qb->andWhere('p.title LIKE :searchParam OR p.content LIKE :searchParam')
            ->setParameter('searchParam', $searchParam);
        }

        return $qb;
    }
}
