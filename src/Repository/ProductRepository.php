<?php

namespace App\Repository;

use App\Entity\Product;
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
        $queryBuilder = $this->createQueryBuilder('p')->orderBy('p.id', 'ASC');
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
