<?php declare(strict_types=1);

namespace Kikopolis\App\Utility;

use Kikopolis\App\Helpers\Str;
use Kikopolis\App\Config\Config;
use Kikopolis\App\Helpers\Validate;

defined('_KIKOPOLIS') or die('No direct script access!');

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
            $this->token = Str::randomString(16);
        }
    }

    /**
     * Create a csrf token from a random string and hash it.
     * @return string
     */
    public function getCsrfToken(): string
    {
        $this->createCsrfToken();
        $this->csrf_token = $this->getTokenHash($_SESSION['csrf_token'], 'sha256', $_SESSION['token_confirmation']);
        return $this->csrf_token;
    }

    /**
     * Return a hashed token.
     * @param string $algorithm
     * @param string $hmac_key
     * @return string
     */
    public function getTokenHash($algorithm = 'sha256', $hmac_key = Config::STRING): string
    {
        return hash_hmac($algorithm, $this->token , $hmac_key);
    }

    /**
     * Create and set the CSRF token into session.
     * @return string
     */
    private function createCsrfToken(): string
    {
        $this->csrf_token = Str::randomString(16);
        $_SESSION['csrf_token'] = $this->csrf_token;
        $_SESSION['token_confirmation'] = Str::randomString(16);
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
    }

    public function csrfTokenIsValid()
    {
        if (!Validate::hasValue($_POST['csrf_token'])) {
            throw new \Exception('Form Token not present. Stop the press and call the office!');
        } else {
            if (!$this->csrfTokenIsRecent()) {
                throw new \Exception('Form token has expired. Please try again.');
            }
            if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new \Exception('CSRF Tokens from form are mismatched. Stopping everything and running away scared!!!');
            } else {
                return true;
            }
        }
    }

    public function csrfTokenIsRecent()
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
