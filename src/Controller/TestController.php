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
        $carPosts = $this->getDoctrine()->getRepository(CarPost::class)->findBy(
            [],
            [],
            10
        );
//        /** @var CarPost[] $carPosts */
//        $carPosts = $this->getDoctrine()->getRepository(CarPost::class)->findAll();

        $response = new JsonResponse($this->carPostSerializer->getSerializer()->normalize($carPosts, null, [
            'attributes' => [
                'id',
                'title',
                'description',
                'sellerName',
                'sellerPhones',
                'link',
                'previewImage',
                'images',
                'carInfo' => [
                    'id',
                    'mark' => [
                        'id',
                        'name'
                    ],
                    'model' => [
                        'id',
                        'name'
                    ],
                    'year',
                    'generation' => [
                        'id',
                        'name',
                        'fromYear',
                        'toYear'
                    ],
                    'bodyType' => [
                        'id',
                        'name'
                    ],
                    'modification',
                    'shape' => [
                        'id',
                        'name'
                    ],
                    'price' => [
                        'id',
                        'byn',
                        'usd'
                    ],
                    'mileage',
                    'mileageMeasure' => [
                        'id',
                        'name'
                    ],
                    'engine' => [
                        'id',
                        'type' => [
                            'id',
                            'name'
                        ],
                        'engineCapacity',
                        'engineCapacityHint',
                        'hybrid',
                        'gasEquipment',
                        'gasEquipmentType' => [
                            'id',
                            'name'
                        ],
                        'powerReserve',
                        'powerReserveHint'
                    ],
                    'transmission' => [
                        'id',
                        'name'
                    ],
                    'driveType' => [
                        'id',
                        'name'
                    ],
                    'color' => [
                        'id',
                        'name'
                    ]
                ]
            ]
        ]));

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
