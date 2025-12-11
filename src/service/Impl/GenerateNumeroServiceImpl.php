<?php 
namespace App\service\Impl;
use App\service\GenerateNumeroService;

class GenerateNumeroServiceImpl implements GenerateNumeroService{
    public function generateNumero(): string
    {
        $numero = 'COMPT-' . strtoupper(bin2hex(random_bytes(4)));
        return $numero;
    }
}