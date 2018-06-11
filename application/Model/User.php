<?php

namespace Model;

use Core\Model;
use PDO;

class User extends Model
{
    /** @var string */
    private $accessToken;

    /**
     * Register user and starts user session.
     *
     * @param array $request - New user data
     */
    public function register($request)
    {
        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/createUser.sql');
        $query = $this->db->prepare($sql);

        $this->accessToken = $token = bin2hex(openssl_random_pseudo_bytes(16));;
        $passwordHash = $this->generatePasswordHash($request['password']);

        $query->bindParam(':username', $request['username'], PDO::PARAM_STR);
        $query->bindParam(':pass', $passwordHash, PDO::PARAM_STR);
        $query->bindParam(':access_token', $this->accessToken, PDO::PARAM_STR);
        $query->bindParam(':first_name', $request['first-name'], PDO::PARAM_STR);
        $query->bindParam(':last_name', $request['last-name'], PDO::PARAM_STR);

        $query->execute();

        $this->startUserSession();
    }

    /**
     * Logs in user and starts user session.
     *
     * @param array $request
     * @return bool
     */
    public function login($request)
    {
        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/getUser.sql');
        $query = $this->db->prepare($sql);

        $passwordHash = $this->generatePasswordHash($request['password']);

        $query->bindParam(':username', $request['username']);
        $query->bindParam(':pass', $passwordHash);
        $query->execute();
        $user = $query->fetch();

        if ($user) {
            $this->accessToken = $user->access_token;
            $this->startUserSession();
            return true;
        } else return false;
    }

    private function startUserSession()
    {
        session_start();
        $_SESSION['accessToken'] = $this->accessToken;
        setcookie("accessToken", $this->accessToken, time() + 3600);
        session_write_close();
    }

    /**
     * Generate password hash with a SALT
     *
     * @param string $password
     * @return bool|string
     */
    private function generatePasswordHash($password)
    {
        return crypt($password, SALT);
    }
}