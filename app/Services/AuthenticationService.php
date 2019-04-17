<?php
namespace App\Services;
use App\Repositories\AuthenticationLogRepository;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AuthenticationService
{
/**
* @var AuthenticationLogRepository $authenticationLog
*/ private $authenticationLogRepository;
/**
* AuthenticationService constructor.
* Initialize object/instances of the classes.
*
* @param AuthenticationLogRepository $authenticationLogRepository
*/
public function __construct(AuthenticationLogRepository $authenticationLogRepository)
{
$this->authenticationLogRepository = $authenticationLogRepository;
}
/**
* Method to store to logged-in logs in the database.
*
* @param Request $request
* @param User $user
* @return void
*/
public function storeLoginActivityOfUser(Request $request, User $user)
{
$logDetails = [
'user_id' => $user->id,
'ip_address' => $request->ip(),
'login_time' => Carbon::now()->toDateTimeString(),
'logout_time' => date('Y-m-d H:i:s'),
'browser_agent' => $request->server('HTTP_USER_AGENT'),

];
$this->authenticationLogRepository->create($logDetails);
}

}