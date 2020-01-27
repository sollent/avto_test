<?php

namespace App\Service;

use App\Entity\CarColor;
use App\Entity\CarEngine;
use App\Entity\CarGeneration;
use App\Entity\CarInfo;
use App\Entity\CarMark;
use App\Entity\CarMileageMeasure;
use App\Entity\CarModel;
use App\Entity\CarPost;
use App\Entity\CarPrice;
use App\Entity\CarTransmission;
use App\Repository\CarBodyTypeRepository;
use App\Repository\CarEngineTypeRepository;
use App\Repository\CarShapeRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CarPostService
 * @package App\Service
 */
class CarPostService
{
    /**
     * @var string
     */
    private const FILE_PATH = 'public/images';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CarEngineTypeRepository
     */
    private $carEngineTypeRepository;

    /**
     * @var CarBodyTypeRepository
     */
    private $carBodyTypeRepository;

    /**
     * @var CarShapeRepository
     */
    private $carShapeRepository;

    /**
     * CarPostService constructor.
     * @param EntityManagerInterface $em
     * @param CarEngineTypeRepository $carEngineTypeRepository
     * @param CarBodyTypeRepository $carBodyTypeRepository
     * @param CarShapeRepository $carShapeRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        CarEngineTypeRepository $carEngineTypeRepository,
        CarBodyTypeRepository $carBodyTypeRepository,
        CarShapeRepository $carShapeRepository
    )
    {
        $this->em = $em;
        $this->carEngineTypeRepository = $carEngineTypeRepository;
        $this->carBodyTypeRepository = $carBodyTypeRepository;
        $this->carShapeRepository = $carShapeRepository;
    }

    /**
     * @param array $posts
     * @throws \Exception
     */
    public function save(array $posts): void
    {
        foreach ($posts as $post) {
            $carPost = new CarPost();
            $carPost->setTitle($post['title']);
            $carPost->setDescription($post['description']);
            $carPost->setCreatedAt($post['createdAt']);
            $carPost->setSellerName($post['sellerName']);
            $carPost->setImagesLinks($post['images'] ? $post['images'] : array());
            try {
                $carPost->setPreviewImageLink($post['previewImageLink']);
            } catch (\Exception $e) {
                dump($e->getMessage());
                dump($post['carInfo']['previewImageLink']);
            }
            $carPost->setLink($post['link']);
            $carPost->setSellerPhones($post['sellerPhones']);

            $carInfo = new CarInfo();
            $carPrice = new CarPrice();
            $carEngine = new CarEngine();

            $carPrice->setBYN($post['price']);
            try {
                $carEngine->setType($this->carEngineTypeRepository->findOneByName($post['carInfo']['engine.type']));
            } catch (\Exception $e) {
                dump($e->getMessage());
                dump($post['link']);
            }
            try {
                $carEngine->setEngineCapacity($this->getEngineCapacity($post['carInfo']['engine.engineCapacity']));
            } catch (\Exception $e) {
                dump($e->getMessage());
                dump($post['carInfo']['engine.engineCapacity']);
            }

            $carInfo->setMark($this->em->getRepository(CarMark::class)->find($post['carInfo']['mark']));
            $carInfo->setModel($this->em->getRepository(CarModel::class)->find($post['carInfo']['model']));
            try {
                $carInfo->setGeneration($this->em->getRepository(CarGeneration::class)->find($post['carInfo']['generation']));
            } catch (\Exception $exception) {
                dump($exception->getMessage());
            }

            $carInfo->setPrice($carPrice);
            $carInfo->setEngine($carEngine);
            $carInfo->setBodyType($this->carBodyTypeRepository->findOneByName($post['carInfo']['bodyType']));
            $carInfo->setYear($post['carInfo']['year']);
            $carInfo->setMileage((int)filter_var($post['carInfo']['mileage'],FILTER_SANITIZE_NUMBER_INT));
            $carInfo->setMileageMeasure($this->getMileageMeasure($post['carInfo']['mileage']));
            $carInfo->setColor($this->em->getRepository(CarColor::class)->findOneBy([
                'name' => $post['carInfo']['color']
            ]));
            $carInfo->setTransmission($this->em->getRepository(CarTransmission::class)->findOneBy([
                'name' => $post['carInfo']['transmission']
            ]));
            $carInfo->setShape($this->carShapeRepository->findOneByName($post['carInfo']['shape']));

            $carPost->setCarInfo($carInfo);

            $this->em->persist($carPrice);
            $this->em->persist($carEngine);
            $this->em->persist($carPost);
        }

        $this->em->flush();
    }

    /**
     * @param string $capacity
     * @return int
     */
    public function getEngineCapacity(string $capacity): int
    {
        $array = explode(' ', $capacity);
        $engineCapacity = (int) $array[0];

        return $engineCapacity;
    }

    /**
     * @param string $mileageMeasure
     * @return CarMileageMeasure
     */
    private function getMileageMeasure(string $mileageMeasure): CarMileageMeasure
    {
//        $array = explode(' ', $mileageMeasure);
//        $measure = $array[1];

        return $this->em->getRepository(CarMileageMeasure::class)->findOneBy([
            'name' => 'км'
        ]);

    }

    /**
     * @param array $imagesQueue
     */
    public function saveImages(array $imagesQueue): void
    {
        foreach ($imagesQueue as $key => $image) {
            file_put_contents("public/images/" . $key . '.jpeg', $image);
        }
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLastPostsLinks(int $limit = 25): array
    {
        $carPosts = $this->em->getRepository(CarPost::class)->findBy(
            array(),
            array('createdAtInSystem' => 'DESC'),
            $limit
        );

        $links = array();

        /** @var CarPost $carPost */
        foreach ($carPosts as $carPost) {
            $links[] = $carPost->getLink();
        }

        return $links;
    }
}
