<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe  extends User
{
  

    #[Assert\NotBlank(message: "Le nom et le prénom de l'Employe est obligatoire.")]
    #[Assert\Length(
        min: 4,
        max: 25,
        minMessage: "Le nom et le prénom de l'Employe doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom et le prénom de l'Employet ne peut pas dépasser {{ limit }} caractères."
    )]

    #[ORM\Column(length: 200)]
    private ?string $nomComplet = null;

    #[ORM\Column(length: 25, unique: true)]
    #[Assert\NotBlank(message: 'Le Telephone est obligatoire.')]
    #[Assert\Regex(
        pattern: '/^(77|78)\d{7}$/',
        message: 'Le numéro de téléphone doit commencer par 77 ou 78 et contenir 9 chiffres au total.'
        )]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creatAt = null;

    #[ORM\Column()]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToOne(inversedBy: 'Employes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Le département de l'Employe est obligatoire.")]
    public ?Departement $departement = null;
    

    public function __construct()
    {
        $this->isArchived = false;
        $this->creatAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
        
    }
    #[ORM\Column]
    private ?bool $isArchived = null;

    #[ORM\Column(length: 20,unique: true)]
    private ?string $numero = null;

    #[ORM\Column(nullable: true)]
    #[Assert\LessThanOrEqual(
        'today',
        message: "La date d'embauche ne peut etre superieur à la date du jour.")]
    private ?\DateTimeImmutable $embaucheAt = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    
    #[Assert\NotNull(message: "La photo de l'Employe est obligatoire.")]
    #[Assert\Image(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez télécharger une image valide (JPEG ou PNG).',
        maxSizeMessage: 'La taille maximale du fichier est de 2 Mo.'
    )]
    private $photoFile;
    // getter et setter pour $photoFile
    public function getPhotoFile()
    {
        return $this->photoFile;
    }   
    public function setPhotoFile($photoFile): static
    {
        $this->photoFile = $photoFile;

        return $this;
    }


    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): static
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCreatAt(): ?\DateTimeImmutable
    {
        return $this->creatAt;
    }

    public function setCreatAt(\DateTimeImmutable $creatAt): static
    {
        $this->creatAt = $creatAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEmbaucheAt(): ?\DateTimeImmutable
    {
        return $this->embaucheAt;
    }

    public function setEmbaucheAt(?\DateTimeImmutable $embaucheAt): static
    {
        $this->embaucheAt = $embaucheAt;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
