<?php

namespace Pj\EntityExtendBundle\Mapping;

use Doctrine\ORM\Mapping\Annotation;

/**
 * Class ExtendedEntity.
 *
 * @author Paulius Jarmalavičius <paulius.jarmalavicius@gmail.com>
 *
 * @Annotation
 * @Target("CLASS")
 */
final class ExtendedEntity implements Annotation
{
    public ?string $className = null;
}