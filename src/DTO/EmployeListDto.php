<?php 
namespace App\DTO;
use App\Entity\Employe;
use \DateTimeImmutable;
class EmployeListDto{
    public int $id;
    public string $numero;
    public string $nomComplet;
    public string $telephone;
    public DateTimeImmutable $embaucheAt;
    public bool $isArchived;
    public string $departementName;
    public ?string $photo;
    public static function fromEntitie(Employe $entity): EmployeListDto{
        $dto = new EmployeListDto();
        $dto->id = $entity->getId();
        $dto->numero = $entity->getNumero();
        $dto->nomComplet = $entity->getNomComplet();
        $dto->telephone = $entity->getTelephone();
        $dto->embaucheAt = $entity->getEmbaucheAt();
        $dto->isArchived = $entity->isArchived();
        $dto->departementName = $entity->getDepartement() ? $entity->getDepartement()->getName() : 'N/A';
        $dto->photo = $entity->getPhoto()?'/uploads/employes/'.$entity->getPhoto():'/uploads/employes/default.png';
        return $dto;
    }
    //collection
    public static function fromEntities(array $entities): array{
        return array_map(function(Employe $entity){
            return self::fromEntitie($entity);
        }, $entities);
    }
}