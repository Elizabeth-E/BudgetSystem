<?php
namespace Config;

// Global configuration variable

$GLOBALS["CONFIG"] = [
    "database" => [
        "host" => "localhost",
        "username" => "root",
        "password" => "",
        "database" => "budget_system_db"
    ],
    "default" => [
        "controller" => "home",
        "action" => "index"
    ],
    "email" => [
        "from" => [
            "email" => "from@example.com",
            "name" => "John Doe"
        ],
        "username" => "example@gmail.com",
        "password" => "passwordhere",
        "port" => 465,
        "host" => "smtp.gmail.com",
        "secure" => "tls"
    ],
    "password_salt" => "salero",
    "xorCryptKey" => "SuperSecretKey"
]
?>