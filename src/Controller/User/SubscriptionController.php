<?php

namespace App\Controller\User;

use App\Entity\User\Subscription;
use App\Entity\User\User;
use App\Form\SubscriptionForm;
use App\Serializer\CarPostSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SubscriptionController
 */
class SubscriptionController extends AbstractController
{
    /**
     * @var CarPostSerializer
     */
    private $carPostSerializer;

    /**
     * @var array
     */
    private $subscriptionSerializable = [
        'attributes' => [
            'id',
            'mark' => [
                'id',
                'name'
            ],
            'model' => [
                'id',
                'name'
            ],
            'generation' => [
                'id',
                'name',
                'fromYear',
                'toYear'
            ],
            'active'
        ]
    ];


    /**
     * SubscriptionController constructor.
     * @param CarPostSerializer $carPostSerializer
     */
    public function __construct(CarPostSerializer $carPostSerializer)
    {
        $this->carPostSerializer = $carPostSerializer;
    }

    /**
     * @Route(
     *     "/subscription/create"
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $subscription = new Subscription();
        $form = $this->createForm(SubscriptionForm::class, $subscription);

        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Subscription $subscription */
            $subscription = $form->getData();

            $subscription->setUser(
                $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => 'sollent'])
            );
            $subscription->setActive(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($subscription);
            $em->flush();
            $em->refresh($subscription);

            return new JsonResponse($this->carPostSerializer->getSerializer()->normalize($subscription, null, $this->subscriptionSerializable));
        }

        return new JsonResponse(['error' => 'Some error'], Response::HTTP_BAD_REQUEST);
    }
}
