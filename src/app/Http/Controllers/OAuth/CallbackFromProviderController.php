<?php declare(strict_types=1);

namespace App\Http\Controllers\OAuth;

use App\Enums\SocialProvider;
use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteTwoUser;


final class CallbackFromProviderController extends Controller
{
    /**
     * @param AuthManager $auth
     */
    public function __construct(
        private AuthManager $auth,
    ) {
    }

    /**
     * @param Request $request
     * @param SocialProvider $provider
     * @return RedirectResponse
     */
    public function __invoke(Request $request, SocialProvider $provider): RedirectResponse
    {
        /** @var SocialiteTwoUser $socialiteTwoUser */
        $socialiteTwoUser = Socialite::driver($provider->value)->user();

        $socialAccount = SocialAccount::where([
            'provider_name' => $provider->name,
            'provider_id' => $socialiteTwoUser->getId(),
        ])->first();

        if ($socialAccount) {
            $this->auth->guard()->login($socialAccount->user);
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        $user = User::firstOrCreate([
            'email' => $socialiteTwoUser->getEmail(),
        ], [
            'name' => $socialiteTwoUser->getName() ?? $socialiteTwoUser->getNickname(),
            'password' => Hash::make(Str::random()),
        ]);

        $user->socialAccounts()->create([
            'provider_name'  => $provider->value,
            'provider_id'    => $socialiteTwoUser->getId(),
            'provider_token' => $socialiteTwoUser->token,
            'provider_refresh_token' => $socialiteTwoUser->refreshToken,
        ]);

        $this->auth->guard()->login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    }
}