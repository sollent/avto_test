<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CarGeneration
 * @package App\Entity
 * @ORM\Entity
 */
class CarGeneration
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
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $fromYear;

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $toYear;

    /**
     * @var CarModel
     * @ORM\ManyToOne(targetEntity="App\Entity\CarModel", inversedBy="generations")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $carModel;

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
     * @return CarModel
     */
    public function getCarModel(): ?CarModel
    {
        return $this->carModel;
    }

    /**
     * @param CarModel $carModel
     */
    public function setCarModel(CarModel $carModel): void
    {
        $this->carModel = $carModel;
    }

    /**
     * @return \DateTime
     */
    public function getFromYear(): ?\DateTime
    {
        return $this->fromYear;
    }

    /**
     * @param \DateTime $fromYear
     * @return CarGeneration
     */
    public function setFromYear(\DateTime $fromYear): CarGeneration
    {
        $this->fromYear = $fromYear;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getToYear(): ?\DateTime
    {
        return $this->toYear;
    }

    /**
     * @param \DateTime $toYear
     * @return CarGeneration
     */
    public function setToYear(\DateTime $toYear): CarGeneration
    {
        $this->toYear = $toYear;
        return $this;
    }
}
