<?php

namespace App\Domain\Google\Repository;

use App\Domain\Google\Entity\GoogleCallbackRequestEntity;
use App\Domain\Google\Entity\GoogleAppLoginRequestEntity;

interface GoogleRepository
{
    public function login(GoogleCallbackRequestEntity $requestEntity): array;
    public function appLogin(GoogleAppLoginRequestEntity $requestEntity): string;
}
