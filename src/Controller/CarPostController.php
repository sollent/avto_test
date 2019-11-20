<?php

namespace App\Controller;

use App\Entity\CarPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CarController
 * @package App\Controller
 */
class CarPostController extends AbstractController
{
    /**
     * @Route(
     *     "/car-posts/show-all"
     * )
     */
    public function showAll()
    {
        $carPosts = $this->getDoctrine()->getRepository(CarPost::class)->findBy(
            array(),
            array('createdAtInSystem' => 'DESC'),
            25
        );

        return $this->json($carPosts);
    }
}