<?php
namespace App\Service;

class InvalidDTOAssembler extends AbstractDTOAssembler
{
    final public function compress($data)
    {
    }

    final public function assemble()
    {
        throw new \BadMethodCallException();
    }
}