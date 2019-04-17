<?php

namespace App\Helpers;

class GenerateAccessToken
{
public static function createAccesstoken($user)
{
return 'Bearer ' .
$user->createToken(env('APP_NAME'))->accessToken; 
}
}