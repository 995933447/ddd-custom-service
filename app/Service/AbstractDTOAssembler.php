<?php
namespace App\Service;

abstract class AbstractDTOAssembler
{
    abstract public function compress($data);

    abstract public function assemble();
}