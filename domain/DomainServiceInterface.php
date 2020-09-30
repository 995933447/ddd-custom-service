<?php
namespace Domain;

interface DomainServiceInterface
{
    /**
     * Execute domain service.
     * @throws DomainServeException
     * @return mixed
     */
    public function execute();
}