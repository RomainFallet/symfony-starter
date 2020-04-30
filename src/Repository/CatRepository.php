<?php

namespace App\Repository;

use App\Entity\Cat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Error;
use Exception;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @method Cat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cat[]    findAll()
 * @method Cat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cat::class);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getRandomUrl(): string
    {
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'https://api.thecatapi.com/v1/images/search'
        );
        if ($response->getStatusCode() >= 400) {
            throw new Error('Unable to get a new cat.');
        }
        return $response->toArray()[0]['url'];
    }
}
