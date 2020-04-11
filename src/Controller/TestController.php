<?php

namespace App\Controller;

use App\Entity\CarGeneration;
use App\Entity\CarMark;
use App\Entity\CarModel;
use App\Entity\CarPost;
use App\Serializer\CarPostSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

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
            ['createdAtInSystem' => 'DESC'],
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

    /**
     * @Route(
     *     "/get-marks"
     * )
     *
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getMarks(): JsonResponse
    {
        $marks = $this->getDoctrine()->getRepository(CarMark::class)->findAll();

        $response = new JsonResponse($this->carPostSerializer->getSerializer()->normalize($marks, null, [
            'attributes' => [
                'id',
                'name'
            ]
        ]));

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route(
     *     "/get-models/{markId}",
     *     requirements={"markId"="\d+"}
     * )
     *
     * @param int $markId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getModels(int $markId): JsonResponse
    {
        $models = $this->getDoctrine()->getRepository(CarModel::class)->findBy([
            'mark' => $this->getDoctrine()->getRepository(CarMark::class)->find($markId)
        ]);

        $response = new JsonResponse($this->carPostSerializer->getSerializer()->normalize($models, null, [
            'attributes' => [
                'id',
                'name'
            ]
        ]));

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    /**
     * @Route(
     *     "/get-generations/{modelId}",
     *     requirements={"modelId"="\d+"}
     * )
     *
     * @param int $modelId
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getGenerations(int $modelId): JsonResponse
    {
        $generations = $this->getDoctrine()->getRepository(CarGeneration::class)->findBy([
            'model' => $this->getDoctrine()->getRepository(CarModel::class)->find($modelId)
        ]);

        $response = new JsonResponse($this->carPostSerializer->getSerializer()->normalize($generations, null, [
            'attributes' => [
                'id',
                'name',
                'fromYear',
                'toYear'
            ]
        ]));

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
