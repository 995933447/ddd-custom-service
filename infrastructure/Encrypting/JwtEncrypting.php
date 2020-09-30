<?php
namespace Infrastructure\Encrypting;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Infrastructure\Shared\Config\Config;

class JwtEncrypting
{
    const TOKEN_ERROR_DECODE_FAILED = -1;

    const TOKEN_EXPIRE_DECODE_FAILED = -2;

    public static function getJwtDefaultSigner()
    {
        return new Sha256();
    }

    public static function getJwtDefaultKey(): string
    {
        return Config::get('encrypting.jwt.key');
    }

    public static function encodeToken(?string $key, int $iat, int $expire, int $nbf, array $data)
    {
        $builder = new Builder();
        $builder->issuedAt($iat) // Set token created at
        ->canOnlyBeUsedAfter($nbf) // Can use token after time
        ->expiresAt($expire); // Set token expired

        foreach ($data as $field => $value) {
            $builder->withClaim($field, $value);
        }
        return (string)$builder->getToken(static::getJwtDefaultSigner(), new Key(is_null($key) ? static::getJwtDefaultKey() : $key));
    }

    public static function decodeToken(?string $key, $token)
    {
        $parse = (new Parser())->parse($token);

        // Validate token correct
        if (!$parse->verify(static::getJwtDefaultSigner(),  is_null($key) ? static::getJwtDefaultKey() : $key)) {
            return static::TOKEN_ERROR_DECODE_FAILED;
        }
        // Validate token expired
        if ($parse->isExpired()) {
            return static::TOKEN_EXPIRE_DECODE_FAILED;
        }

        foreach ($parse->getClaims() as $field => $value) {
            $payload[$field] = $parse->getClaim($field);
        }

        return $payload ?? [];
    }
}