<?php
namespace App\Service\Command\ServiceImpl\User;

use App\Service\AbstractCommandApplicationService;
use App\Service\AbstractDTOAssembler;
use App\Service\ApplicationServeException;
use Domain\Model\User\HashedPassword;
use Domain\Model\User\User;
use Domain\Model\User\UserRepository;
use App\Service\Command\DTO\User\LoginCommand\InputDTO;
use Infrastructure\Encrypting\JwtEncrypting;

class LoginCommandService extends AbstractCommandApplicationService
{
    protected $userRepository;

    protected static $loginTokenHowLongExpire = 3600 * 24;

    public function __construct(UserRepository $user_repository)
    {
        $this->userRepository = $user_repository;
    }

    public function handle(InputDTO $input, AbstractDTOAssembler $output_assembler): void
    {
        if (is_null($user = $this->userRepository->findByUsername($input->getUsername()))) {
            throw new ApplicationServeException('用户不存在.');
        }

        if (!$user->checkPasswordIsSelf(HashedPassword::fromPassword($input->getPassword()))) {
            throw new ApplicationServeException('密码不正确.');
        }

        session_id(md5($token = static::createLoginToken($user)));
        session_cache_expire(static::$loginTokenHowLongExpire);
        session_start();
        $_SESSION['login_user'] = [
            'uName' => $user->getUsername(),
            'uId' => $user->getId()
        ];

        $output_assembler->compress(['token' => $token, 'user' => $user]);
    }

    protected static function createLoginToken(User $user)
    {
        return JwtEncrypting::encodeToken(
            null,
            $now = time(),
            $now + static::$loginTokenHowLongExpire,
            $now,
            ['uid' => $user->getId()]
        );
    }
}