<?php

namespace App\Service;

use App\Entity\Tarea;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;

class TareaManager 
{
    private $em;
    private $tareaRepository;
    public function __construct(TareaRepository $tar ,EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function crear(Tarea $tarea)
    {
        $this->em->persist($tarea);
        $this->em->flush();
    }

    public function editar(Tarea $tarea)
    {
        $this->em->flush();
    }

    public function eliminar(Tarea $tarea)
    {
        $this->em->remove($tarea);
        $this->em->flush();
    }

    public function validar(Tarea $tarea)
    {
        $errores = [];
        if(empty($tarea->getDescripcion())){
            $errores[] = "Campo 'descripcion' obligatorio";
        }
        return $errores;
    }
}