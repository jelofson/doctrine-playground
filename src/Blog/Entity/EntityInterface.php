<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\ClassMetadata;

interface EntityInterface
{
    public static function loadMetadata(ClassMetadata $metadata);
}