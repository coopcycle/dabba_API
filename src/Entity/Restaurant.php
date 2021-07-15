<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={"get"},
 *     normalizationContext={"groups"={"restaurant:read"}},
 *     denormalizationContext={"groups"={"restaurant:write"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "name": "partial"
 *     })
 * @ApiFilter(RangeFilter::class, properties={"lat","lng"})
 * @ApiFilter(NumericFilter::class, properties={"zone.id"})
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"restaurant:read","user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Assert\NotBlank()
     * @Groups({"restaurant:read", "restaurant:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"restaurant:read", "restaurant:write"})
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     * @Groups({"restaurant:read", "restaurant:write"})
     */
    private $lng;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="restaurants",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $zone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $google_place_id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="restaurants")
     */
    private $fans;

    public function __construct()
    {
        $this->fans = new ArrayCollection();
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

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getGooglePlaceId(): ?string
    {
        return $this->google_place_id;
    }

    public function setGooglePlaceId(?string $google_place_id): self
    {
        $this->google_place_id = $google_place_id;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFans(): Collection
    {
        return $this->fans;
    }

    public function addFan(User $fan): self
    {
        if (!$this->fans->contains($fan)) {
            $this->fans[] = $fan;
        }

        return $this;
    }

    public function removeFan(User $fan): self
    {
        $this->fans->removeElement($fan);

        return $this;
    }
}
