<?php

namespace App\Services\Meta;

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
     * @return void
     */
    public function logout(): void;

    /**
     * Reset user password
     *
     * @param array $data Request data with old, new
     * and confirm passwords.
     * @return void
     */
    public function resetPassword(array $data);

    /**
     * Update currently authenticated user
     *
     * @param array $data Request data with upated columns
     * for authenticated user.
     */
    public function updateAuthenticatedUser(array $data);

    /**
     * Authenticate client token
     */
    public function authenticate(string $accessToken): void;
}
