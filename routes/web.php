<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    // return view('email.verify', [
    //     'id' => '1',
    //     "full_name" => "admin",
    //     "email" => "tutranthanh22082002@gmail.com ",
    //     'otp_email_code' => 'abc123',
    //     'otp_email_expired_at' => now()->addMinute()->toString(),
    //     'link' => 'http://localhost:8000/api/v1/auth/resend-otp-email'
    //     // 'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiN2Y4OTBkYmFmNzZjN2Q3ZjdlOWU5ZWZlMTA5MGFkZWExNWRhYmExYmU0MTNlMmJlMjE1NDViNjgyM2EwZjZhNmUzYjc3MmY4YmMyNTAxNDgiLCJpYXQiOjE2ODE2MTg2MDkuMzkwOTU3LCJuYmYiOjE2ODE2MTg2MDkuMzkwOTYsImV4cCI6MTY4MjIyMzQwOS4zMDk3NzksInN1YiI6IjIxIiwic2NvcGVzIjpbXX0.O01uHSQQGQvmvTeNiy8Carm-mYJ-tu7rRblI-xJKP70b8W89sMYrzHhePArnXC8n4ZfmV0xwSk1GmFpl6zzLnmB3A2O2QUQJNPCjtac6jJt9ImCPXBBkr7E_OIn_DQJfRej8Gaqh2kiA5gMlO2kCGGc9PJCUrqMNnZV3arRLRL5feTYJMEXjm-3lwxhM5o5aW1R7H9oodHdmmy3hds1t3EaO3dTIOedWlg6oP_jv_CWlFeZ1QTsCic72ceiuwpHXbSS3--YKcHZCLcH4mfihP2EzENs6fL6lbGmN_1fObzpb25UR5WhuJFblsLrMNY44flpjsOQm6VhjdVVERKLwvBANJ3G4OjOIOgsOql35-z4MylqPYS-RLELNFOczv3mcfJZkzo7vbXypG5s6E0Qns4ycrAe4P5JlAOo79F6z5All-iuQPNBURobZQKw6kf-_VPH1IfLTyLt5_G-0zhPSuQSuBE3vqVxA7St_CrxlSsDAP6ON1luIr3Ql7r_PKShijg_291eyKlXVP4-VoNcMFFflhcEiCJo488LeOHd7NobneA3dDCPJjQCVmQjLi_wcB6bLdaBR-0g-BUvASqVoKMyeqYi4Dr5WKrlJETdb05IpdMzR9sdZMohee-8qwP6lPv9m57Jr1vX-tOy1i9XHsjOUstEc8zt09NvahdzKzA4',
    // ]);
    return view('email.noticeReturnBooks', [
        "full_name" => "Trần Thanh Tú",
        "email" => "tutranthanh22082002@gmail.com ",
        'books' => [
            [
                'name' => 'Sách 1',
                'amount' => 3,
                'payment' => 20000,
                'unit' => 'VND',
                'borrowed_at' =>  now()->toString(),
                'estimated_returned_at' => now()->addDay()->toString(),
            ],
            [
                'name' => 'Sách 2',
                'amount' => 5,
                'payment' => 80000,
                'unit' => 'VND',
                'borrowed_at' => now()->toString(),
                'estimated_returned_at' => now()->addDay()->toString(),
            ],


        ],
        'estimated_returned_at' => now()->addDay()->toString(),
    ]);
});
