<?php

namespace App\Repository;

use App\Document\ListeningHistory;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * @extends DocumentRepository<ListeningHistory>
 */
class ListeningHistoryRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(ListeningHistory::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    
    // efa ODM
    public function findAllListening($player, $song): array
    {
        return $this->createQueryBuilder('lh')
            ->field('player')->equals($player)  // Manampy condition amin'ny 'player'
            ->field('song')->equals($song)  // Manampy condition amin'ny 'song'
            ->field('playedAt')->gte(new \DateTimeImmutable('today'))  // Manampy condition amin'ny 'playedAt'
            ->sort('playedAt', 'desc')  // Sort amin'ny playedAt, descending order
            ->limit(10)  // Manampy limit 10 valiny
            ->getQuery()
            ->execute()  // Manatanteraka ny query
            ->toArray();  // Mivadika ho array
    }


    //    /**
    //     * @return ListeningHistory[] Returns an array of ListeningHistory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ListeningHistory
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
