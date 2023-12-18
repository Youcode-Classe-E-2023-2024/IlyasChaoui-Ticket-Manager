<?php
class Users
{
    private $user_id;
    private $username;
    private $email;
    private $password;
    private $picture;
    private $conn;

    function __construct($conn)
    {
        $this->conn = $conn;
    }

    function getUserData($email)
    {
        $sql = "SELECT * FROM users WHERE UserEmail=?";
        $stmt = mysqli_stmt_init($this->conn);
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        return (mysqli_fetch_assoc($res));
    }
    function login($email, $password)
    {
        $row = $this->getUserData($email);
        if ($row) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["log"] = true;
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["email"] = $row["email"];
            } else {
                throw new Exception("password_is_not_correct");
                return false;
            }
        } else {
            throw new Exception("user_not_found");
            return false;
        }
        return true;
    }

    function register($username, $email, $password)
    {
        $row = $this->getUserData($email);
        if (!$row) {
            $sql = "INSERT INTO users (userName, UserEmail, password) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($this->conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
            mysqli_stmt_execute($stmt);
        } else {
            throw new Exception("user_exists");
            return false;
        }
        return true;
    }

    function logout()
    {
        session_destroy();
    }
}
