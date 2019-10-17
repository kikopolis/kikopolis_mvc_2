<?php

declare(strict_types=1);

namespace Kikopolis\App\Utility;

use Kikopolis\App\Helpers\Str;

defined('_KIKOPOLIS') or die('No direct script access!');

/**
 * Token utility class. Generate and validate basic tokens and csrf tokens.
 * Part of the Kikopolis MVC Framework.
 * @author Kristo Leas <admin@kikopolis.com>
 * @version 0.0.0.1000
 * PHP Version 7.3.5
 */

class Token
{
    /**
     * Regular token. Used for activation and misc low level auth functions
     * @var string
     */
    private $token;

    /**
     * CSRF token, hashed and more secure token.
     * @var string
     */
    private $csrf_token;

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Token constructor.
     * @param null $token_value Default $token_value is null, then we create a new token. If an actual value is passed in
     * then that is set as value and can be used for verifying said token.
     * @throws \Exception
     */
    public function __construct($token_value = null)
    {
        if ($token_value !== null) {
            $this->token = $token_value;
        } else {
            $this->token = Str::random(16);
        }
    }

    /**
     * Create a csrf token from a random string and hash it.
     * @throws \Exception
     * @return string
     */
    public function getCsrfToken(): string
    {
        $this->createCsrfToken();
        $this->csrf_token = $this->getCsrfHash();

        return $this->csrf_token;
    }

    /**
     * Return a hashed token.
     * @return string
     */
    public function getTokenHash(): string
    {
        return Hash::getHash($this->token);
    }

    /**
     * Compare token to its verification, saved in where ever, DB, session etc.
     * @param string $token
     * @param string $verification
     * @return bool
     */
    public function tokenIsValid(string $token, string $verification): bool
    {
        return Hash::compare($token, $verification);
    }

    /**
     * Get csrf token hash.
     * @return string
     */
    private function getCsrfHash(): string
    {
        return Hash::getHash($this->csrf_token);
    }

    /**
     * Create and set the CSRF token into session.
     * @throws \Exception
     * @return string
     */
    private function createCsrfToken(): string
    {
        $this->csrf_token = Str::random(16);
        $_SESSION['csrf_token'] = $this->csrf_token;
        $_SESSION['token_confirmation'] = Str::random(16);
        $_SESSION['csrf_token_time'] = time();

        return $this->csrf_token;
    }

    /**
     * Destroy the csrf token
     * @return boolean
     */
    private function destroyCSRFToken()
    {
        if (isset($_SESSION['csrf_token']) || isset($_SESSION['token_confirmation']) || isset($_SESSION['csrf_token_time'])) {
            $_SESSION['csrf_token'] = null;
            $_SESSION['token_confirmation'] = null;
            $_SESSION['csrf_token_time'] = null;

            return true;
        }

        return false;
    }

    /**
     * Verify that the csrf token is valid.
     * @throws \Exception
     * @return bool
     */
    public function csrfTokenIsValid(): bool
    {
        if (!Validate::hasValue($_POST['csrf_token'])) {
            throw new \Exception('Form Token not present. Stop the press and call the office!');
        } else {
            if (!$this->csrfTokenIsRecent()) {
                throw new \Exception('Form token has expired. Please try again.');
            }
            if (!Hash::compare($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                throw new \Exception('CSRF Tokens from form are mismatched. Stopping everything and running away scared!!!');
            } else {
                echo "<h1>CSRF TOKEN IS VALID</h1>";
                return true;
            }
        }
    }

    /**
     * Verify the csrf token is recent.
     * @return bool
     */
    private function csrfTokenIsRecent(): bool
    {
        $max_elapsed = 60 * 60 * 24;
        if (!Validate::hasValue($_SESSION['csrf_token'])) {
            $this->destroyCSRFToken();

            return false;
        } else {

            return ($_SESSION['csrf_token_time'] + $max_elapsed) >= time();
        }
    }
}
