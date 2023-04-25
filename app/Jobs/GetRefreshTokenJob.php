<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Passport\Client;
use GuzzleHttp\Client as GuzzleHttpClient;

class GetRefreshTokenJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Đường liên kết đến oauth/token
     */
    private string $linkOauthToken;
    /**
     * Dữ liệu
     */
    private mixed $loginData;
    /**
     * Kết quả trả về
     */
    private static string $result;
    /**
     * Create a new job instance.
     */
    public function __construct(string $linkOauthToken, mixed $loginData) {
        $this->linkOauthToken = $linkOauthToken;
        $this->loginData = $loginData;
        self::$result = "";
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        // gọi dịch vụ lấy token từ oauth server
        $clientId = config('passport.personal_password_client.id');
        $clientSecret = config('passport.personal_password_client.secret');
        $oauthClient = Client::where('id', $clientId)->first();
        $httpClient = new GuzzleHttpClient([
            'base_uri' => $this->linkOauthToken,
        ]);

        $response = $httpClient->post($this->linkOauthToken, [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oauthClient->id ?? $clientId,
                'client_secret' => $oauthClient->secret ?? $clientSecret,
                'username' => $this->loginData['username'],
                'password' => $this->loginData['password'],
                'scope' => '',
            ]
        ]);
        $responseData = json_decode($response->getBody()->getContents());
        $this->loginData['refreshToken'] = $responseData->refresh_token;
    }
}
