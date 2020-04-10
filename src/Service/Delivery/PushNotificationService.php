<?php

namespace App\Service\Delivery;

use App\Entity\CarPost;
use App\Model\TransferInterface;
use Pusher\Pusher;

/**
 * Class PushNotificationService
 */
class PushNotificationService implements DeliveryInterface
{
    /**
     * @var Pusher
     */
    private $pusher;

    /**
     * @var string
     */
    public const LOCAL_SERVICE_TAG = 'delivery.push.service';

    /**
     * PushNotificationService constructor.
     * @param Pusher $pusher
     */
    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * @param TransferInterface $transfer
     * @throws \Pusher\PusherException
     */
    public function transfer(TransferInterface $transfer): void
    {
        /** @var CarPost $post */
        $post = $transfer->getPost();
        $user = $transfer->getUser();
        $subscription = $transfer->getSubscription();

//        var_dump($this->pusher);exit();

        $this->pusher->trigger(
            $user->getUsername() . '-' . $user->getId(),
            'new-car',
            array(
                'post' => array(
                    'id' => $post->getId(),
                    'title' => $post->getTitle(),
                    'carInfo' => array(
                        'mark' => $post->getCarInfo()->getMark()->getName(),
                        'model' => $post->getCarInfo()->getModel()->getName(),
                        'generation' => $post->getCarInfo()->getGeneration()->getName()
                    )
                )
            )
        );
    }
}
