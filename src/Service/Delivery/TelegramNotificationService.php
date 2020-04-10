<?php

namespace App\Service\Delivery;

use App\Model\TransferInterface;
use Telegram\Bot\Api;

/**
 * Class TelegramNotificationService
 */
class TelegramNotificationService implements DeliveryInterface
{
    /**
     * @param TransferInterface $transfer
     */
    public function transfer(TransferInterface $transfer): void
    {
        $client = new Api("1001979581:AAF_r8ryClsdK-RnI2YCFgjxSeET39dTDV0");
    }
}
