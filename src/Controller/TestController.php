<?php

namespace App\Controller;

use App\Entity\CarPost;
use App\Serializer\CarPostSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TestController
 * @package App\Controller
 */
class TestController extends AbstractController
{
    /**
     * @var CarPostSerializer
     */
    private $carPostSerializer;

    /**
     * TestController constructor.
     * @param CarPostSerializer $carPostSerializer
     */
    public function __construct(CarPostSerializer $carPostSerializer)
    {
        $this->carPostSerializer = $carPostSerializer;
    }

    /**
     * @Route(
     *     "/cars"
     * )
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function showAll()
    {
        /** @var CarPost[] $carPosts */
        $carPosts = $this->getDoctrine()->getRepository(CarPost::class)->findAll();

        $response = new JsonResponse($this->carPostSerializer->getSerializer()->normalize($carPosts));
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
