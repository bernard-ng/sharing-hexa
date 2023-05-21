<?php

declare(strict_types=1);

namespace Infrastructure\Authentication\Symfony\Authenticator;

use Domain\Authentication\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private ?UserInterface $user = null;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserRepositoryInterface $repository
    ) {}


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('authentication_login');
    }

    public function authenticate(Request $request): Passport
    {
        $identifier = (string) $request->request->get('identifier', '');
        $password = (string) $request->request->get('password', '');
        $csrf = (string) $request->request->get('_token', '');

        $passport = new Passport(
            userBadge: new UserBadge(
                userIdentifier: $identifier,
                userLoader: fn (string $identifier) => $this->repository->findOneByEmail($identifier)
            ),
            credentials: new PasswordCredentials($password),
            badges: [
                new CsrfTokenBadge('authenticate', $csrf),
                new RememberMeBadge()
            ]
        );

        $this->user = $this->createToken($passport, 'main')->getUser();
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_link_index'));
    }
}
