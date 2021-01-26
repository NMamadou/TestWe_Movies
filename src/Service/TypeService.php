<?php

namespace App\Service;

use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;

class TypeService
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAllTypes() {
        return $this->getTypeRepository()->getAllTypes();
    }

    public function getTypeRepository() {
        return $this->em->getRepository(Type::class);
    }
}