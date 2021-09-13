<?php

namespace Pj\EntityExtendBundle\Mapping\Driver;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver as DoctrineAnnotationDriver;
use Pj\EntityExtendBundle\Mapping\Driver\Traits\ExtendedEntitiesTrait;
use Pj\EntityExtendBundle\Mapping\ExtendedEntity;
use ReflectionClass;
use ReflectionException;

/**
 * Class AnnotationDriver.
 *
 * @author Paulius Jarmalavičius <paulius.jarmalavicius@gmail.com>
 * @author Andreas Keßler <andreas@innovation-agents.de>
 */
class AnnotationDriver extends DoctrineAnnotationDriver
{
    use ExtendedEntitiesTrait;

    protected ?EntityManagerInterface $em = null;

    public function setEntityManager(EntityManagerInterface $entityManager): self
    {
        $this->em = $entityManager;

        return $this;
    }

    /**
     * @param string $className
     * @param ClassMetadataInfo $metadata
     * @throws MappingException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     * @throws ReflectionException
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        parent::loadMetadataForClass($className, $metadata);

        $classAnnotations = $this->getClassAnnotations($metadata);
        if (isset($classAnnotations[ExtendedEntity::class])) {
            /** @var ExtendedEntity $annotation */
            $annotation = $classAnnotations[ExtendedEntity::class];
            $extendedEntityClass = $annotation->className;
            $cmf = $this->em->getMetadataFactory();
            $extendedEntityMetadata = $cmf->getMetadataFor($extendedEntityClass);

            // Set by parent entity.
            $metadata->setPrimaryTable($extendedEntityMetadata->table);
        }
    }

    /**
     * Returns whether the class with the specified name is transient. Only non-transient
     * classes, that is entities and mapped superclasses, should have their metadata loaded.
     *
     * A class is non-transient if it is annotated with an annotation
     * from the {@see AnnotationDriver::entityAnnotationClasses}.
     */
    public function isTransient($className): bool
    {
        $isTransient = parent::isTransient($className);

        if (!$isTransient && isset($this->extendedEntities[$className])) {
            $isTransient = true;
        }

        return $isTransient;
    }

    /**
     * @throws ReflectionException
     */
    protected function getClassAnnotations(ClassMetadataInfo $metadata): array
    {
        $class = $metadata->getReflectionClass();
        if ( !$class) {
            // this happens when running annotation driver in combination with
            // static reflection services. This is not the nicest fix
            $class = new ReflectionClass($metadata->name);
        }

        return $this->readAnnotations($class);
    }

    protected function readAnnotations(ReflectionClass $class): array
    {
        $classAnnotations = $this->reader->getClassAnnotations($class);
        if ($classAnnotations) {
            foreach ($classAnnotations as $key => $annotations) {
                if (!is_numeric($key)) {
                    continue;
                }

                $classAnnotations[get_class($annotations)] = $annotations;
            }
        }

        return $classAnnotations;
    }
}