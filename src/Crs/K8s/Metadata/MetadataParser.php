<?php

/**
 * This file is part of the crs/k8s library.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Crs\K8s\Metadata;

use Crs\K8s\Annotation\Attribute;
use Crs\K8s\Annotation\Kind;
use Crs\K8s\Annotation\Operation;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionProperty;

class MetadataParser
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    public function __construct(?AnnotationReader $annotationReader = null)
    {
        $this->annotationReader = $annotationReader ?? new AnnotationReader();
    }

    public function parse(string $modelFqcn): ModelMetadata
    {
        $kind = null;
        $operations = [];
        $properties = [];

        $modelClass = new ReflectionClass($modelFqcn);
        $classAnnotations = $this->annotationReader->getClassAnnotations($modelClass);

        foreach ($classAnnotations as $classAnnotation) {
            if ($classAnnotation instanceof Kind) {
                $kind = new KindMetadata($classAnnotation);
            } elseif ($classAnnotation instanceof Operation) {
                $operations[] = new OperationMetadata($classAnnotation);
            }
        }

        foreach ($modelClass->getProperties() as $modelProperty) {
            $annotations = $this->annotationReader->getPropertyAnnotations($modelProperty);
            $metadata = $this->getPropertyMetadata($annotations, $modelProperty);
            if ($metadata) {
                $properties[] = $metadata;
            }
        }

        return new ModelMetadata(
            $modelFqcn,
            $properties,
            $operations,
            $kind
        );
    }

    private function getPropertyMetadata(array $annotations, ReflectionProperty $modelProperty): ?ModelPropertyMetadata
    {
        $modelPropertyMetadata = null;

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Attribute) {
                $modelPropertyMetadata = new ModelPropertyMetadata(
                    $modelProperty->getName(),
                    $annotation
                );
                break;
            }
        }

        return $modelPropertyMetadata;
    }
}
