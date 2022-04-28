<?php declare(strict_types=1);

namespace App\Helper;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;

class SerializerHelper
{
    public static function serialize($data, string $format = 'json', ?SerializationContext $context = null, ?string $type = null): string
    {
        return SerializerBuilder::create()->build()->serialize($data, $format, $context, $type);
    }

    public static function deserialize(string $data, string $type, string $format, ?DeserializationContext $context = null)
    {
        return SerializerBuilder::create()->build()->deserialize($data, $type, $format, $context);
    }

    public static function toArray($data, ?SerializationContext $context = null, ?string $type = null): array
    {
        return SerializerBuilder::create()->build()->toArray($data, $context, $type);
    }

    public static function fromArray(array $data, string $type, ?DeserializationContext $context = null)
    {
        return SerializerBuilder::create()->build()->fromArray($data, $type, $context);
    }
}
