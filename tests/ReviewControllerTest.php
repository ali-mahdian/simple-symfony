<?php

namespace App\Tests;

use App\Controller\ReviewController;
use App\Entity\Car;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;

class ReviewControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private Car $car1;
    private Car $car2;
    private int $rating = 10;


    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->car1 = new Car();
        $this->entityManager->persist($this->car1);
        $this->entityManager->flush();
        $this->car2 = new Car();
        $this->entityManager->persist($this->car2);
        $this->entityManager->flush();

        for ($i = 1; $i <= $this->rating ; $i++) {
            $review = new Review();
            $review
                ->setRating($i)
                ->setCar($this->car1)
                ->setText("test review {$i}");
            $this->entityManager->persist($review);
            $this->entityManager->flush();
        }
        parent::setUp();
    }

    public function testLatestReviews(): void
    {
        $client = HttpClient::create();
        $response = $client->request('GET', "http://localhost/api/car/{$this->car1->getId()}/reviews");

        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertNotEmpty($responseData);

        //assert above min_rating condition with count of reviews in response
        $this->assertEquals($this->rating - ReviewController::MIN_RATING, count($responseData));

        foreach ($responseData as $review) {
            //assert sort
            self::assertEquals($this->rating, $review['rating']);
            $this->rating--;
        }

        $response = $client->request('GET', "/api/car/{$this->car2->getId()}/reviews");
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        // assert car_id condition as car2 has no reviews
        $this->assertEmpty($responseData);
    }
}