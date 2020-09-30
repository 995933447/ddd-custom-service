<?php
namespace App\Service\Command\DTOAssembler\User\LoginCommand;

use App\Service\Command\DTO\User\LoginCommand\TokenOutputDTO;

class TokenOutputDTOAssembler extends AbstractCmdExeOutputDTOAssembler
{
    public function assemble(): TokenOutputDTO
    {
        return new TokenOutputDTO(['token' => $this->token]);
    }
}