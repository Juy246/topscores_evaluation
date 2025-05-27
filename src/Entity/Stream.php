<?php

namespace App\Entity;

use App\Repository\StreamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: StreamRepository::class)]
class Stream
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\ManyToOne(targetEntity: Jeu::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jeu $jeu = null;




    public function getId(): ?int
    {
        return $this->id;
    }



    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getJeu(): ?Jeu
    {
        return $this->jeu;
    }

    public function setJeu(Jeu $jeu): static
    {
        $this->jeu = $jeu;

        return $this;
    }

    
}
