<?php
namespace Pulse\Bus\Commands\User;

class CreateUserCommand
{
    /**
     * Name
     *
     * @var string
     */
    public $name;

    /**
     * Username
     *
     * @var string
     */
    public $username;

    /**
     * Email
     *
     * @var string
     */
    public $email;

    /**
     * Password
     *
     * @var string
     */
    public $password;

    /**
     * Create a new CreateUserCommand instance
     *
     * @param string $name
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @return void
     */
    public function __construct($name, $username, $email, $password)
    {
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get User Data
     * @return array
     */
    public function getUserData()
    {
        return [
        'name' => $this->name,
        'username' => $this->username,
        'email' => $this->email,
        'password' => $this->password
        ];
    }
}