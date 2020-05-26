<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

final class CustomController
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
}
