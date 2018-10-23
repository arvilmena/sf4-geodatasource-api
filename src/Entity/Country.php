<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
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
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $tld;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country_name;

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

    public function getCcIso(): ?string
    {
        return $this->cc_iso;
    }

    public function setCcIso(string $cc_iso): self
    {
        $this->cc_iso = $cc_iso;

        return $this;
    }

    public function getTld(): ?string
    {
        return $this->tld;
    }

    public function setTld(?string $tld): self
    {
        $this->tld = $tld;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->country_name;
    }

    public function setCountryName(string $country_name): self
    {
        $this->country_name = $country_name;

        return $this;
    }
}
