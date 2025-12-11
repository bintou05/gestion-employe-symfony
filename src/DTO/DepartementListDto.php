<?php 
namespace App\DTO;

use App\Entity\Departement;
use \DateTimeImmutable;
class DepartementListDto{
    public int $id;
    public string $name;
    public bool $isArchived;
    public DateTimeImmutable $creatAt;
    public int $nbreEmploye = 0 ;


    public static function fromEntitie($departement): DepartementListDto{
        $dto = new DepartementListDto();
        $dto->id = $departement->getId();
        $dto->name = $departement->getName();
        $dto->isArchived = $departement->isArchived();
        $dto->creatAt = $departement->getCreatAt();
        $dto->nbreEmploye = count($departement->getEmployes());
        return $dto;
    }

    //collection

    public static function fromEntities(array $entities): array{
        return array_map(function(Departement $entity){
            return self::fromEntitie($entity);
        }, $entities);
    }
}