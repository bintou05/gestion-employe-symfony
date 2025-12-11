<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
#[ORM\Table(name: 'departements')]
#[UniqueEntity(fields: ['name'], message: 'la valeur {{ value }} existe déjà.')]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[Assert\NotBlank(message: 'Le nom du département est obligatoire.')]
    #[Assert\Length(
        min: 4,
        max: 25,
        minMessage: 'Le nom du département doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom du département ne peut pas dépasser {{ limit }} caractères.'
    )]

    #[ORM\Column(length: 100,unique: true)]
    private ?string $name = null;

    #[ORM\Column(name: 'creat_at')]
    private ?\DateTimeImmutable $creatAt = null;

    #[ORM\Column (nullable: true,name: 'update_at')]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column ()]
    private ?bool $isArchived = null;

    /**
     * @var Collection<int, Employe>
     */
    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'departement')]
    private Collection $Employes;
    

    public function __construct()
    {
        $this->Employes = new ArrayCollection();
        $this->isArchived = false;
        $this->creatAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function isArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->Employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->Employes->contains($employe)) {
            $this->Employes->add($employe);
            $employe->setDepartement($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        if ($this->Employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getDepartement() === $this) {
                $employe->setDepartement(null);
            }
        }

        return $this;
    }
}
