<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Vich\Uploadable()
 * @UniqueEntity("name")
 */
class Product
{

    const PERIOD = [
        0 => 'Jour',
        1 => 'Semaine',
        2 => 'Mois'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_production;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_user;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var File
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $production_time;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="product", orphanRemoval=true)
     */
    private $orders;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMaxProduction(): ?int
    {
        return $this->max_production;
    }

    public function setMaxProduction(int $max_production): self
    {
        $this->max_production = $max_production;

        return $this;
    }

    public function getMaxUser(): ?int
    {
        return $this->max_user;
    }

    public function setMaxUser(int $max_user): self
    {
        $this->max_user = $max_user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProductionTime(): ?int
    {
        return $this->production_time;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Product
     */
    public function setFilename(?string $filename): Product
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     * @return Product
     */
    public function setImageFile(?File $imageFile): Product
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceOf UploadedFile)
        {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }




    public function setProductionTime(int $production_time): self
    {
        $this->production_time = $production_time;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getProduct() === $this) {
                $order->setProduct(null);
            }
        }

        return $this;
    }

    public function returnClassName()
    {
        return "Produit";
    }


    public function getPeriod()
    {
        $periods = Product::PERIOD;
        foreach ($periods as $k => $v)
        {
            if ($k == $this->getProductionTime())
            {
                return $v;
            }
        }
        return 'Invalid Period';
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
