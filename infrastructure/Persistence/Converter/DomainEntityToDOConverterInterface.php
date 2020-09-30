<?php
namespace Infrastructure\Persistence\Converter;

use Infrastructure\Persistence\DataObject\DataObjectInterface;

interface DomainEntityToDOConverterInterface
{
    public function toDataObject();
}