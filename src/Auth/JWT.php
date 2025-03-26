<?php

namespace src\Auth;

use Carbon\CarbonImmutable;
use Exceptions\JWTExpiredException;
use Exceptions\JWTValidatorException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoder;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Src\Auth\Exceptions\JWTParserException;

final readonly class JWT
{
    public function __construct(
        private string  $secret,
        private Encoder $encoder = new JoseEncoder(),
    )
    {
    }

    public function createToken(string $id): string
    {
        $builder = new Builder(
            $this->encoder,
            ChainedFormatter::default()
        );

        return $builder
            ->issuedAt(now()->toImmutable())
            ->expiresAt($this->getExpiresAt())
            ->relatedTo($id)
            ->getToken(new Sha256(), InMemory::base64Encoded($this->secret))
            ->toString();

    }

    public function getExpiresAt(): CarbonImmutable
    {
        return now()->toImmutable()->addHour();
    }

    /**
     * @throws JWTValidatorException
     * @throws JWTExpiredException
     * @throws JWTParserException
     */
    public function parseToken(string $token): string
    {
        $parser = new \Lcobucci\JWT\Token\Parser(
            $this->encoder,
        );

        try {
            $parsedToken = $parser->parse($token);
        } catch (\Throwable $e) {
            throw new JWTParserException($e->getMessage(), $e->getCode(), $e);
        }

        $key = InMemory::base64Encoded($this->secret);

        $configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            $key
        );

        $configuration->withValidationConstraints(
            new SignedWith(
                new Sha256(),
                $key
            )
        );

        if ($configuration->validator()->validate($parsedToken, ...$configuration->validationConstraints())) {
            throw new JWTValidatorException('Validation failed');
        }

        if ($parsedToken->isExpired(now())) {
            throw new JWTExpiredException('Token is expired');
        }

        return $parsedToken
            ->claims()
            ->get('sub');


    }

}
