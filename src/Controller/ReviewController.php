<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewController extends AbstractController
{
    const MIN_RATING = 6;
    const LATEST_COUNT = 5;

    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    #[Route('/api/car/{carId}/reviews', name: 'api_car_reviews', requirements: ['carId'=>"^\d+$"], methods: ['GET'])]
    public function getLatestReviews(SerializerInterface $serializer, $carId): Response
    {
        $reviews = $this->reviewRepository->getLatestReviews(intval($carId), self::MIN_RATING, self::LATEST_COUNT);
        $serializer->normalize($reviews, null, ['groups' => ['output'], DateTimeNormalizer::FORMAT_KEY => 'Y-m-d h:m']);
        $data = $serializer->serialize($reviews, 'json');
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }
}
