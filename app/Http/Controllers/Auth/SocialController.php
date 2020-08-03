<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Traits\RandomStringGenerator;

class SocialController extends Controller
{
    use RandomStringGenerator;
   /**
    * Handle Social login request
    *
    * @return response
    */
    public function socialLogin($social)
    {
        if ($social == 'github') {
            return Socialite::driver($social)->redirect();
        } else {
            return redirect('https://api.intra.42.fr/oauth/authorize?client_id=ad621af753cdab32eb73006372af41da6240ca0f38204be154ecc2a9485782bc&redirect_uri=http%3A%2F%2Flocalhost%3A8000%2Fsocialauth%2Fintra%2Fcallback&response_type=code');
        }
    }

   /**
    * Obtain the user information from Social Logged in.
    * @param $social
    *
    * @return Response
    */
    public function handleProviderCallback($social)
    {
        switch ($social) {
            case "github":
                $socialUser = Socialite::driver($social)->stateless()->user();
                // $socialUser = Socialite::driver($social)->user();

                // dd($socialUser->getEmail);

                // If we have the user email already within the system we log them in and return
                $user = User::whereEmail($socialUser->getEmail())->whereProvider($social)->first();

                // Save the record
                if (!$user) {
                    $user = User::create([
                        'first_name'    => $socialUser->getName() ?? $socialUser->nickname,
                        'email'         => $socialUser->getEmail(),
                        'password'      => bcrypt($this->randomCode()),
                        'provider'      => $social,
                        'path_picture'  => $socialUser->getAvatar(),
                    ]);
                }

                auth()->login($user);       //Authenticate user

                return redirect('/home');
                break;
            case "intra":
                $curl = curl_init();
    
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_URL,'https://api.intra.42.fr/oauth/token');

                if (!isset($_GET['code'])) {
                    return redirect('/');
                }
        
                $credital = [
                    "grant_type" => "authorization_code",
                    "client_id" => env('INTRA_ID'),
                    "client_secret" => env('INTRA_SECRET'),
                    "code" => $_GET['code'],
                    "redirect_uri" => env('INTRA_URL'),
                ];

        
                curl_setopt($curl, CURLOPT_POSTFIELDS, $credital);
        
                $response = curl_exec($curl);

                curl_close($curl);

                $tokenData = explode(',' ,$response);
                $accessToken = $tokenData[0];
                $accessToken = substr($accessToken, 17 , -1);

                $curl = curl_init();
                $tokenArr = [
                  'Authorization: Bearer ' . $accessToken
                ];
        
                curl_setopt($curl, CURLOPT_URL,'https://api.intra.42.fr/v2/me');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $tokenArr);
        
                $response = curl_exec($curl);
                curl_close($curl);
        
                $response = json_decode($response, true);

                // If we have the user email already within the system we log them in and return
                $user = User::whereEmail($response['email'])->whereProvider($social)->first();

                // Save the record
                if (!$user) {
                    $user = User::create([
                        'first_name'    => $response['first_name'],
                        'last_name'    => $response['last_name'],
                        'email'         => $response['email'],
                        'password'      => bcrypt($this->randomCode()),
                        'provider'      => $social,
                        'path_picture'  => $response['image_url'],
                    ]);
                }
                
                auth()->login($user);       //Authenticate user

                return redirect('/home');
                break;
            default:
                return redirect('/login');
        }
    }
}
