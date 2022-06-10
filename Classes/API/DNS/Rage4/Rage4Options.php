<?php

namespace App\Service\API\DNS\Rage4;


class Rage4Options
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}