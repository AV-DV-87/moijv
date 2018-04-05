<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Tag::class);
    }

    /**
     * 
     * @param string $tagName the name of the tag we are
     * looking for
     * 
     * @return Tag the corresponding tag in DB or a new Tag instance if
     * no corresponding tag is found
     * 
     */
    public function getCorrespondingTag($tagName) {
        $slugify = new \Cocur\Slugify\Slugify();
        $tagSlug = $slugify->slugify($tagName);

        $tag = $this->findOneBy(['slug' => $tagSlug]);

        if (!$tag) {
            $tag = new Tag();
            $tag->setName($tagName);
            $tag->setSlug($tagSlug);
        }
        return $tag;
    }

//    /**
//     * @return Tag[] Returns an array of Tag objects
//     */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('t')
      ->andWhere('t.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('t.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Tag
      {
      return $this->createQueryBuilder('t')
      ->andWhere('t.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
