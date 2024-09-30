<?php

namespace App\Services\Meta;

use App\Core\Request;

interface AuthServiceMeta
{
    /**
     * Log in user with provided credentials
     */
    public function login(array $credentials);

    /**
     * Register new user based on provided credentials
     */
    public function register(array $credentials);

    /**
     * Logout user
     *
     * @param Request $request Incoming request
     * @return void
     */
    public function logout(array $user): void;

    /**
     * Reset user password
     *
     * @param Request $request Incoming request
     * @return void
     */
    public function resetPassword(array $user, array $data): void;

    /**
     * Update currently authenticated user
     *
     * @return void
     */
    public function updateAuthenticatedUser(array $user, array $data): void;

    /**
     * Authenticate client token
     *
     * @param Request $request Incoming request
     * @return void
     */
    public function authenticate(array $user): void;
}
