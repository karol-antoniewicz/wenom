<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Collection;
use Laravel\Fortify\Events\RecoveryCodesGenerated;
use Laravel\Fortify\RecoveryCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;

class TWoFAAuthentication extends Controller
{

//TODO: make this dynamic from DB if so
	//would normally be  public function __invoke(): JsonResponse
    public function __invoke()
	    {
            echo "<script>alert('called 2faController' );</script>";

        }


    public function activate2FA(): JsonResponse
    {
        $user = User::query()
        ->where(column: 'id', operator: '=', value: '213');
        //$user->update(['two_factor_secret' => 'dummy','two_factor_confirmed_at' => now()->format(format: 'Y-m-d H:i:s.u'),'two_factor_recovery-codes' => 'xxxxxx']);
		$user->update([
            'two_factor_secret' => 'dummy',
            'two_factor_confirmed_at' => now()->format(format: 'Y-m-d H:i:s.u'),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(1, function () {
                return RecoveryCode::generate();
            })->all())),
		]);


        //keep this for later
        // User::all()->each(
        //     callback: fn (User $user): bool => $user->update(
        //         attributes: [
        //             'two_factor_secret' => 'dummy'
        //             // 'two_factor_confirmed_at' => now()->format(format: 'Y-m-d H:i:s.u'),
        //             // 'two_factor_recovery-codes' => 'dummy',
        //         ]
        //     )
        // );

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deactivate2FA(): JsonResponse
    {
        User::all()->each(
            callback: fn (User $user): bool => $user->update(
                attributes: [
                    'two_factor_secret' => 'null',
                    'two_factor_confirmed_at' => 'null',
                    'two_factor_recovery_codes' => 'null',
                ]
            )
        );
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

}
