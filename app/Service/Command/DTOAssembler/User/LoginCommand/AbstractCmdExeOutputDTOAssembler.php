<?php
namespace App\Service\Command\DTOAssembler\User\LoginCommand;

use App\Service\AbstractDTOAssembler;
use Domain\Model\User\User;

abstract class AbstractCmdExeOutputDTOAssembler extends AbstractDTOAssembler
{
    protected $token;

    protected $user;

    public function compress($data)
    {
        if (!is_array($data) || !isset($data['token']) || !isset($data['user']) || !$data['user'] instanceof User) {
            throw new \InvalidArgumentException();
        }

        $this->token = $data['token'];

        $this->user = $data['user'];
    }
}