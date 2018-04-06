<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) 
    {
        parent::__construct($registry, Product::class);
    }
    
    //Requête nécessaire à la pagination
    public function findPaginated($page = 1) 
    {
        //CONSTRUCTION D'UNE REQUETE  et alias pour la table produit = p
        $queryBuilder = $this->createQueryBuilder('p')
                ->leftJoin('p.owner', 'u')
                ->addSelect('u')
                ->leftJoin('p.tags', 't')
                ->addSelect('t')
                ->leftJoin('p.loans', 'l')
                ->where('l.status = :status1')
                ->orWhere('l.status = :status2')
                ->orWhere('l.status is NULL')
                ->setParameter('status1', 'finished')
                ->setParameter('status2', 'refused')
                ->orderBy('p.id', 'DESC');
        //lien doctrine et Pager Fanta
        $adapter = new DoctrineORMAdapter($queryBuilder);
        //Pager Fanta
        $pager = new Pagerfanta($adapter);
        //défini la page courante donc le nombre passé en argument
        return $pager->setMaxPerPage(12)->setCurrentPage($page);
    }
    
    //detecte la pagination en fonction de l'utilisateur connecté
    public function findPaginatedByUser(User $user, $page = 1)
    {
        //CONSTRUCTION D'UNE REQUETE  et alias pour la table produit = p
        $queryBuilder = $this->createQueryBuilder('p')
                ->leftJoin('p.owner', 'u')
                ->addSelect('u')
                ->leftJoin('p.tags', 't')
                ->addSelect('t')
                ->where('u = :user')
                ->setParameter('user', $user)
                ->orderBy('p.id', 'ASC');
        //lien doctrine et Pager Fanta
        $adapter = new DoctrineORMAdapter($queryBuilder);
        //Pager Fanta
        $pager = new Pagerfanta($adapter);
        //défini la page courante donc le nombre passé en argument
        return $pager->setMaxPerPage(12)->setCurrentPage($page);
    }
    
    public function findPaginatedByTag(Tag $tag, $page = 1)
    {
        //CONSTRUCTION D'UNE REQUETE  et alias pour la table produit = p
        $queryBuilder = $this->createQueryBuilder('p')
                ->leftJoin('p.owner', 'u')
                ->addSelect('u')
                ->leftJoin('p.tags', 't')
                ->leftJoin('p.tags', 't2')                
                ->addSelect('t')
                ->where('t2 = :tag')
                ->leftJoin('p.loans', 'l')
                ->setParameter('tag', $tag)
                ->orderBy('p.id', 'DESC');
            //conditional group to permit WHERE AND(OR OR)
            $orGroup = $queryBuilder->expr()->orX();
            $orGroup->add($queryBuilder->expr()->eq('l.status', ':status1'));
            $orGroup->add($queryBuilder->expr()->eq('l.status', ':status2'));
            $orGroup->add($queryBuilder->expr()->isNull('l.status'));            
        //RESTART of query builder
        $queryBuilder->andWhere($orGroup)
                     ->setParameter('status1', 'refused')
                     ->setParameter('status2', 'finished');
                             

        //lien doctrine et Pager Fanta
        $adapter = new DoctrineORMAdapter($queryBuilder);
        //Pager Fanta
        $pager = new Pagerfanta($adapter);
        //défini la page courante donc le nombre passé en argument
        return $pager->setMaxPerPage(12)->setCurrentPage($page);
    }  

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('p.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Product
      {
      return $this->createQueryBuilder('p')
      ->andWhere('p.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
