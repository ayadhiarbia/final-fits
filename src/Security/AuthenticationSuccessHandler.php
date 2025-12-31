<?php

// src/Security/SimpleAuthenticationSuccessHandler.php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token
    ): RedirectResponse {
        $user = $token->getUser();

        // Get user roles
        $roles = $user->getRoles();

        // Check if user is admin
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return new RedirectResponse(
                $this->urlGenerator->generate('admin')
            );
        }

        // Default to user dashboard
        return new RedirectResponse(
            $this->urlGenerator->generate('app_dashboard')
        );
    }
}
