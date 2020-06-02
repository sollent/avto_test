<?php

namespace App\Controller;

use App\Entity\CarPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CustomController extends AbstractController
{
    /**
     * @Route(
     *     "/custom/{max}",
     *     name="custom_route"
     * )
     * @param int $max
     */
    public function index(int $max)
    {
        dump($max);
        dump('Hello custom route');exit();
    }

    /**
     * @Route(
     *     "/list"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        $carPosts = $this->getDoctrine()->getRepository(CarPost::class)->findAll();

        return $this->render('avto-list.html.twig', compact('carPosts'));
    }
}
