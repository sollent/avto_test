<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CarPrice
 * @package App\Entity
 * @ORM\Entity
 */
class CarPrice
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $BYN;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $USD;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getBYN(): ?int
    {
        return $this->BYN;
    }

    /**
     * @param int $BYN
     */
    public function setBYN(int $BYN): void
    {
        $this->BYN = $BYN;
    }

    /**
     * @return int
     */
    public function getUSD(): ?int
    {
        return $this->USD;
    }

    /**
     * @param int $USD
     */
    public function setUSD(int $USD): void
    {
        $this->USD = $USD;
    }
}
