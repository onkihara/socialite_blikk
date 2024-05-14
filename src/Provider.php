<?php

namespace Onkihara\SocialiteBlikk;

use GuzzleHttp\RequestOptions;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;
use Carbon\Carbon;

class Provider extends AbstractProvider
{
    
    /**
     * {@inheritdoc}
     */
    protected $scopes = ['user-read'];

    private function getConfig($configname)
    {
        return config('services.blikk.'.$configname);
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        $url = $this->getConfig('oauth_server').'/auth/oauth/blikkauthorize';
        return $this->buildAuthUrlFromBase($url, $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        $url = $this->getConfig('oauth_server').'/auth/oauth/token';
        return $url;
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $url = $this->getConfig('oauth_server').'/auth/api/user';
        $response = $this->getHttpClient()->get($url, [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['ID'],
            'email' => $user['EMail'],
            'name' => $user['Name'],
            'avatar' => $user['UserIcon']
        ]);
        
    }
}
