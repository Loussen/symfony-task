<?php

namespace App\Utils;

use Doctrine\Persistence\ManagerRegistry;

class EntityUtils
{
    function get_column_names_by_entity($entityClassName,ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $class = $entityManager->getClassMetadata($entityClassName);
        $fields = [];
        if (!empty($class->discriminatorColumn)) {
            $fields[] = $class->discriminatorColumn['name'];
        }
        $fields = array_merge($class->getColumnNames(), $fields);
        foreach ($fields as $index => $field) {
            if ($class->isInheritedField($field)) {
                unset($fields[$index]);
            }
        }
        foreach ($class->getAssociationMappings() as $name => $relation) {
            if (!$class->isInheritedAssociation($name)){
                foreach ($relation['joinColumns'] as $joinColumn) {
                    $fields[] = $joinColumn['name'];
                }
            }
        }
        return $fields;
    }
}
