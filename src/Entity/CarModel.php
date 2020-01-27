<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CarModel
 * @package App\Entity
 * @ORM\Entity
 */
class CarModel
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $avByLinkName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $nameFromLink;

    /**
     * @var CarMark
     * @ORM\ManyToOne(targetEntity="App\Entity\CarMark", inversedBy="models")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $carMark;

    /**
     * @var mixed
     * @ORM\OneToMany(targetEntity="App\Entity\CarGeneration", mappedBy="carModel")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $generations;

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
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return CarMark
     */
    public function getCarMark(): ?CarMark
    {
        return $this->carMark;
    }

    /**
     * @param CarMark $carMark
     */
    public function setCarMark(CarMark $carMark): void
    {
        $this->carMark = $carMark;
    }

    /**
     * @return mixed
     */
    public function getGenerations()
    {
        return $this->generations;
    }

    /**
     * @param mixed $generations
     */
    public function setGenerations($generations): void
    {
        $this->generations = $generations;
    }

    /**
     * @return string
     */
    public function getAvByLinkName(): ?string
    {
        return $this->avByLinkName;
    }

    /**
     * @param string $avByLinkName
     */
    public function setAvByLinkName(string $avByLinkName): void
    {
        $this->avByLinkName = $avByLinkName;
    }

    /**
     * @return string
     */
    public function getNameFromLink(): ?string
    {
        return $this->nameFromLink;
    }

    /**
     * @param string $nameFromLink
     */
    public function setNameFromLink(string $nameFromLink): void
    {
        $this->nameFromLink = $nameFromLink;
    }
}