<?php

namespace App\Controller;

use App\Entity\CarPost;
use Nzo\FileDownloaderBundle\FileDownloader\FileDownloader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    private $fileDownloader;

    private $params;

    public function __construct(FileDownloader $fileDownloader, ParameterBagInterface $params)
    {
        $this->fileDownloader = $fileDownloader;
        $this->params = $params;

        // without autowiring use: $this->get('nzo_file_downloader')
    }

    /**
     * @Route(
     *     "/posts"
     * )
     */
    public function posts()
    {
        $carPosts = $this->getDoctrine()->getRepository(CarPost::class)->findBy([], ['createdAtInSystem' => 'DESC']);

        return $this->render('avto-list.html.twig', [
            'posts' => $carPosts
        ]);
    }

    /**
     * @Route(
     *     "/download-file"
     * )
     * @throws \Exception
     */
    public function downloadFile()
    {
        $image = 'https://static3.av.by/public_images/big/016/31/16/public_16311678_b_9c4f123.jpeg';

        $path = $this->params->get('images') . "/" . basename($image);
        $file = file_get_contents($image);
        $insert = file_put_contents($path, $file);
        if (!$insert) {
            throw new \Exception('Failed to write image');
        }

        

        dump($insert);
        exit();
    }
}
