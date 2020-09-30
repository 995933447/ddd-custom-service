<?php
namespace App\Service;

final class NullDTO extends AbstractDTO
{
    public function __construct()
    {
        parent::__construct([]);
    }
}