<?php
namespace App\Service\Command\DTO\User\LoginCommand;

use App\Service\AbstractDTO;

class TokenOutputDTO extends AbstractDTO
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}