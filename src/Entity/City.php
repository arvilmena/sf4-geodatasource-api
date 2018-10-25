<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $cc_fips;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $cc_iso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $full_name_nd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCcFips(): ?string
    {
        return $this->cc_fips;
    }

    public function setCcFips(string $cc_fips): self
    {
        $this->cc_fips = $cc_fips;

        return $this;
    }

    public function getFullNameNd(): ?string
    {
        return $this->full_name_nd;
    }

    public function setFullNameNd(string $full_name_nd): self
    {
        $this->full_name_nd = $full_name_nd;

        return $this;
    }
}
