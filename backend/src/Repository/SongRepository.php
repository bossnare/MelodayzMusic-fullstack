<?php

namespace App\Repository;

use App\Document\Song;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * @extends DocumentRepository<Song>
 */
class SongRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(SongRepository::class);
        parent::__construct($dm, $uow, $classMetaData);
    }

    //    /**
    //     * @return Song[] Returns an array of Song objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    // efa ODM

    public function findAllPopularSong(int $limit = 10): array
    {
        return $this->createQueryBuilder('s')
            ->field('playCount')->gte(1)  // Raha misy ny playCount, azo atao miankina amin'ny fomba fampiasàna izany
            ->sort('playCount', 'desc')  // Sort amin'ny playCount, descending order
            ->limit($limit)  // Manampy limit ny valiny
            ->getQuery()
            ->execute()  // Manatanteraka ny query
            ->toArray();  // Mivadika ho array
    }




    //    public function findOneBySomeField($value): ?Song
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
