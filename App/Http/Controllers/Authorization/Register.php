<?php

declare(strict_types=1);

namespace App\Http\Controllers\Authorization;

use Kikopolis\App\Framework\Controllers\Controller;
use Kikopolis\App\Utility\Validate;
use Kikopolis\App\Utility\Hash;
use Kikopolis\App\Framework\Aurora\View;
use Kikopolis\Core\Http\Request;
use Kikopolis\App\Framework\Orion\ModelGuard\RegisterUser;

defined('_KIKOPOLIS') or die('No direct script access!');

/**
 * Register
 * Part of the Kikopolis MVC Framework.
 * @author Kristo Leas <admin@kikopolis.com>
 * @version 0.0.0.1000
 * PHP Version 7.3.5
 */

class Register extends Controller
{
    protected $middleware = ['register'];

    /**
     * Show registration form
     */
    public function indexAction()
    {
        echo "Register index";
        die();
        View::render('register.index');
    }

    public function registerAction(array $data)
    {
        if (!$this->validate($data)) {
            // TODO: Show the actual errors
            throw new \Exception('Validation error');
        }
        if (!$this->save($data)) {
            // TODO: Show the actual errors
            throw new \Exception('Error saving to db');
        }
        $this->registerSuccess();
    }

    protected function validate(array $data): bool
    {
        return Validate::ruleSet($data, [
           'first_name' => 'required|string|max:255',
           'last_name' => 'required|string|max:255',
           'email' => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:8|letter|number|symbol',
        ]);
    }

    protected function save(array $data)
    {
        if (RegisterUser::createNew([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password_hash' => Hash::getHash($data['password_hash']),
        ])) {
            return true;
        } else {
            throw new \Exception('Error registering user');
        }
    }

    protected function registerSuccess()
    {
        redirect('/');
    }
}