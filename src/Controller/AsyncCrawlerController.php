<?php

namespace App\Controller;

use App\Entity\CarPost;
use Clue\React\Buzz\Browser;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AsyncCrawlerController
 * @package App\Controller
 */
class AsyncCrawlerController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var Browser
     */
    private $browser;

    private $carPostArray = [];

    /**
     * @var array
     */
    private $currentCarLinks = array();

    /**
     * CrawlerController constructor.
     * @param EntityManagerInterface $em
     * @param Client $client
     * @param ParameterBagInterface $params
     */
    public function __construct(
        EntityManagerInterface $em,
        Client $client,
        ParameterBagInterface $params
    )
    {
        $this->em = $em;
        $this->client = $client;
        $this->params = $params;
    }

    /**
     * By default it will be first page (25 first car posts) of cars
     * @return void
     */
    private function fillCarLinks(): void
    {
        $carsPageLink = 'https://cars.av.by/search?sort=date&order=desc&year_from=&year_to=&currency=USD&price_from=&price_to=&body_id=&engine_volume_min=&engine_volume_max=&driving_id=&mileage_min=&mileage_max=&region_id=&interior_material=&interior_color=&exchange=&search_time=1';

        $crawler = $this->client->request('GET', $carsPageLink, array(), array(), array(
            'Host' => 'www.artstation.com',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:69.0)   Gecko/20100101 Firefox/69.0',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'ru,en-US;q=0.5',
            'Accept-Encoding' => 'gzip, deflate, br',
            'DNT' => '1',
            'Connection' => 'keep-alive',
            'Upgrade-Insecure-Requests' => '1',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache'
        ));

        $newLinks = $crawler->filter('.listing-item-title h4 > a')->each(function (Crawler $node) {
            return $node->attr('href');
        });

        $this->currentCarLinks = array_reverse($newLinks);

//        $oldLinks = $this->getLastCarPostsLink();
//
//        $diffLinks = array_diff($newLinks, $oldLinks);
//        //dump($diffLinks);exit();
//        $this->currentCarLinks = $diffLinks;
    }

    /**
     * @Route(
     *     "/async-crawler"
     * )
     * @throws \Exception
     */
    public function crawler()
    {
        $this->fillCarLinks();

        $loop = \React\EventLoop\Factory::create();
        $this->browser = new Browser($loop);

        foreach ($this->currentCarLinks as $link) {
            $this->browser->get($link)
                ->then(function (\Psr\Http\Message\ResponseInterface $response) {
//                    dump($response->getBody());
                    $crawler = new Crawler((string) $response->getBody());
                    dump($crawler);
//
//                    try {
//                        $title = $crawler->filter('.card-title')->text();
//                    } catch (\Exception $exception) {
//                        dump($link);
//                    }
//
//                    $createdAt = $crawler->filter('.card-about-item-dates > dl dd')->text();
//                    $price = $crawler->filter('.card-details .card-price-main-primary')->text();
//
//                    try {
//                        $previewImage = $crawler->filter('.card-gallery .fotorama a')->first()->attr('href');
//                        $images = $crawler->filter('.card-gallery .fotorama a')->each(function (Crawler $node, $i) {
//                            if ($i > 0) {
//                                return $node->attr('href');
//                            }
//                        });
//                        array_splice($images, 0, 1);
//
////                        $previewImage = $this->downloadAndSaveImage($previewImage);
////
////                        for ($i = 0; $i < count($images); $i++) {
////                            $images[$i] = $this->downloadAndSaveImage($images[$i]);
////                        }
//
//                    } catch (\Exception $exception) {
//                        $previewImage = null;
//                        $images = null;
//                    }
//
//                    try {
//                        $aboutCar = $crawler->filter('.card-description')->text();
//                    } catch (\Exception $exception) {
//                        $aboutCar = null;
//                    }
//
//                    $phonesNumbers = [];
//                    $phonesNumbers[] = trim($crawler->filter('a.modal-choice-link')->first()->text());
//
//                    $carPost = new CarPost();
//
//                    $carPost = $this->fillEntity(
//                        $carPost,
//                        compact('title', 'createdAt', 'price', 'aboutCar', 'phonesNumbers', 'link', 'previewImage', 'images')
//                    );
//
//                    $this->carPostArray[] = $carPost;
//                    $this->em->persist($carPost);
//                    $this->em->flush();
                });
        }

        $loop->run();

        dump($this->carPostArray);

        exit();
    }

    /**
     * @param string $link
     * @throws \Exception
     */
    private function saveCarPost(string $link): void
    {


    }

    /**
     * @param CarPost $carPost
     * @param array $parameters
     * @return CarPost
     * @throws \Exception
     */
    private function fillEntity(CarPost $carPost, array $parameters): CarPost
    {
        /** @var CarPost $carPost */
        $carPost
            ->setTitle(trim($parameters['title']))
            ->setCreatedAt(new \DateTime($parameters['createdAt']))
            ->setPrice((int)filter_var($parameters['price'], FILTER_SANITIZE_NUMBER_INT))
            ->setAboutCar(trim($parameters['aboutCar']))
            ->setLink($parameters['link'])
            ->setSellerPhones($parameters['phonesNumbers'])
            ->setPreviewImage($parameters['previewImage'])
            ->setImages($parameters['images']);

        return $carPost;
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLastCarPostsLink(int $limit = 25): array
    {
        $carPosts = $this->getDoctrine()->getRepository(CarPost::class)->findBy(
            array(),
            array('createdAtInSystem' => 'DESC'),
            25
        );

        $links = array();

        /** @var CarPost $carPost */
        foreach ($carPosts as $carPost) {
            $links[] = $carPost->getLink();
        }

        return $links;
    }

    /**
     * @param $imageUrl
     * @return string
     * @throws \Exception
     */
    private function downloadAndSaveImage($imageUrl): string
    {
        // Download image in project public dir
        $path = $this->params->get('images') . "/" . basename($imageUrl);
        $file = file_get_contents($imageUrl);
        $insert = file_put_contents($path, $file);

        if (!$insert) {
            throw new \Exception('Failed to write image');
        }

        return basename($imageUrl);
    }

    /**
     * @param $entity
     */
    private function saveEntity($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

}
