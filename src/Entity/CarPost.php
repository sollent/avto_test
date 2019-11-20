<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CarPost
 * @package App\Entity
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class CarPost
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
    private $title;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Price in BYN (for test)
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $aboutCar;

    /**
     * @var string[]
     * @ORM\Column(type="array")
     */
    private $sellerPhones;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $link;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAtInSystem;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $previewImage;

    /**
     * @var string[]
     * @ORM\Column(type="array", nullable=true)
     */
    private $images;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CarPost
     */
    public function setId(int $id): CarPost
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CarPost
     */
    public function setTitle(string $title): CarPost
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return CarPost
     */
    public function setCreatedAt(\DateTime $createdAt): CarPost
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return CarPost
     */
    public function setPrice(int $price): CarPost
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getAboutCar(): ?string
    {
        return $this->aboutCar;
    }

    /**
     * @param string $aboutCar
     * @return CarPost
     */
    public function setAboutCar(string $aboutCar): CarPost
    {
        $this->aboutCar = $aboutCar;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getSellerPhones(): ?array
    {
        return $this->sellerPhones;
    }

    /**
     * @param string[] $sellerPhones
     * @return CarPost
     */
    public function setSellerPhones(array $sellerPhones): CarPost
    {
        $this->sellerPhones = $sellerPhones;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return CarPost
     */
    public function setLink(string $link): CarPost
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAtInSystem(): ?\DateTime
    {
        return $this->createdAtInSystem;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtInSystem(): void
    {
        $this->createdAtInSystem = new \DateTime('now');
    }

    /**
     * @return string
     */
    public function getPreviewImage(): ?string
    {
        return $this->previewImage;
    }

    /**
     * @param string $previewImage
     * @return CarPost
     */
    public function setPreviewImage(?string $previewImage): CarPost
    {
        $this->previewImage = $previewImage;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @param string[] $images
     * @return CarPost
     */
    public function setImages(?array $images): CarPost
    {
        $this->images = $images;
        return $this;
    }
}
