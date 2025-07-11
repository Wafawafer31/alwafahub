<?php
/*
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google;

use Google\Auth\ApplicationDefaultCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Google\Auth\OAuth2;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use LogicException;

/**
 * The Google API Client
 * https://developers.google.com/api-client-library/php
 */
class Client
{
    const LIBVER = "2.15.0";
    const USER_AGENT_SUFFIX = "google-api-php-client/";
    
    /**
     * @var OAuth2 $auth
     */
    private $auth;
    
    /**
     * @var ClientInterface
     */
    private $httpClient;
    
    /**
     * @var array
     */
    private $config;
    
    /**
     * @var string
     */
    private $accessToken;
    
    /**
     * Construct the Google client.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'application_name' => '',
            'client_id' => '',
            'client_secret' => '',
            'redirect_uri' => null,
            'state' => null,
            'developer_key' => '',
            'use_application_default_credentials' => false,
            'signing_key' => null,
            'signing_algorithm' => null,
            'subject' => null,
            'sub' => null,
            'include_granted_scopes' => null,
            'login_hint' => null,
            'prompt' => null,
            'openid.realm' => null,
            'hd' => null,
            'access_type' => 'online',
            'approval_prompt' => 'auto',
            'request_visible_actions' => null,
            'federated_signon_certs_url' => 'https://www.googleapis.com/oauth2/v1/certs',
        ], $config);
        
        $this->auth = $this->createAuth();
        $this->httpClient = $this->createHttpClient();
    }
    
    /**
     * Get the OAuth 2.0 access token.
     *
     * @return string|null The access token, or null if none has been set.
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    
    /**
     * Set the OAuth 2.0 access token using the string that resulted from calling createAuthUrl()
     * or Google_Client::getAccessToken().
     * @param string|array $accessToken JSON encoded string in the following format:
     * {"access_token":"TOKEN", "refresh_token":"TOKEN", "token_type":"Bearer",
     *  "expires_in":3600, "id_token":"TOKEN", "created":1320790426}
     */
    public function setAccessToken($accessToken)
    {
        if (is_string($accessToken)) {
            if ($json = json_decode($accessToken, true)) {
                $accessToken = $json;
            } else {
                // assume $accessToken is just the token string
                $accessToken = array(
                    'access_token' => $accessToken,
                );
            }
        }
        if ($accessToken == null) {
            $accessToken = array();
        }
        if (!isset($accessToken['access_token'])) {
            throw new InvalidArgumentException("Invalid token format");
        }
        $this->accessToken = $accessToken;
        $this->auth->setAccessToken($accessToken);
    }
    
    /**
     * @return string
     */
    public function createAuthUrl($scope = null)
    {
        if (is_array($scope)) {
            $scope = implode(' ', $scope);
        }
        return $this->auth->buildFullAuthorizationUri(['scope' => $scope]);
    }
    
    /**
     * Exchange an authorization code for an OAuth 2.0 access token.
     *
     * @param string $code authorization code from the callback page.
     */
    public function fetchAccessTokenWithAuthCode($code)
    {
        if (strlen($code) == 0) {
            throw new InvalidArgumentException("Invalid code");
        }
        
        $authHandler = HttpHandlerFactory::build($this->httpClient);
        $creds = $this->auth->fetchAuthToken($authHandler);
        
        if ($creds && isset($creds['access_token'])) {
            $creds['created'] = time();
            $this->setAccessToken($creds);
        }
        
        return $creds;
    }
    
    /**
     * @param string $refreshToken
     * @return array access token
     */
    public function fetchAccessTokenWithRefreshToken($refreshToken = null)
    {
        if ($refreshToken === null && isset($this->accessToken['refresh_token'])) {
            $refreshToken = $this->accessToken['refresh_token'];
        }
        
        if ($refreshToken === null) {
            throw new LogicException('refresh token must be passed in or set as part of setAccessToken');
        }
        
        $this->auth->setRefreshToken($refreshToken);
        $authHandler = HttpHandlerFactory::build($this->httpClient);
        $creds = $this->auth->fetchAuthToken($authHandler);
        
        if ($creds && isset($creds['access_token'])) {
            $creds['created'] = time();
            if (!isset($creds['refresh_token'])) {
                $creds['refresh_token'] = $refreshToken;
            }
            $this->setAccessToken($creds);
        }
        
        return $creds;
    }
    
    /**
     * Set the application name, this is included in the User-Agent HTTP header.
     * @param string $applicationName
     */
    public function setApplicationName($applicationName)
    {
        $this->config['application_name'] = $applicationName;
    }
    
    /**
     * Set the OAuth 2.0 Client ID.
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->config['client_id'] = $clientId;
        $this->auth->setClientId($clientId);
    }
    
    /**
     * Set the OAuth 2.0 Client Secret.
     * @param string $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->config['client_secret'] = $clientSecret;
        $this->auth->setClientSecret($clientSecret);
    }
    
    /**
     * Set the OAuth 2.0 Redirect URI.
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->config['redirect_uri'] = $redirectUri;
        $this->auth->setRedirectUri($redirectUri);
    }
    
    /**
     * Set OAuth 2.0 "state" parameter to achieve per-request customization.
     * @see http://tools.ietf.org/html/draft-ietf-oauth-v2-22#section-3.1.2.2
     * @param string $state
     */
    public function setState($state)
    {
        $this->config['state'] = $state;
        $this->auth->setState($state);
    }
    
    /**
     * Set the developer key to use, these are used to authenticate applications
     * for certain APIs which do not require OAuth.
     * @see https://developers.google.com/console/help/#generatingdevkeys
     * @param string $developerKey
     */
    public function setDeveloperKey($developerKey)
    {
        $this->config['developer_key'] = $developerKey;
    }
    
    /**
     * Set OAuth 2.0 scopes.
     * @param string|array $scopes
     */
    public function setScopes($scopes)
    {
        $this->auth->setScope($scopes);
    }
    
    /**
     * Add OAuth 2.0 scopes.
     * @param string|array $scopes
     */
    public function addScope($scopes)
    {
        if (is_string($scopes)) {
            $scopes = explode(' ', $scopes);
        }
        $this->auth->setScope(array_merge($this->auth->getScope(), $scopes));
    }
    
    /**
     * Returns the list of scopes set on the client
     *
     * @return array the list of scopes
     *
     */
    public function getScopes()
    {
        return $this->auth->getScope();
    }
    
    /**
     * @return OAuth2 implementation
     */
    private function createAuth()
    {
        $auth = new OAuth2([
            'clientId' => $this->config['client_id'],
            'clientSecret' => $this->config['client_secret'],
            'authorizationUri' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'tokenCredentialUri' => 'https://www.googleapis.com/oauth2/v4/token',
            'redirectUri' => $this->config['redirect_uri'],
            'issuer' => $this->config['client_id'],
            'signingKey' => $this->config['signing_key'],
            'signingAlgorithm' => $this->config['signing_algorithm'],
        ]);
        
        return $auth;
    }
    
    /**
     * @return ClientInterface implementation
     */
    private function createHttpClient()
    {
        $options = ['exceptions' => false];
        
        if ($this->config['application_name']) {
            $options['headers']['User-Agent'] = $this->config['application_name']
                . ' ' . self::USER_AGENT_SUFFIX . self::LIBVER;
        }
        
        return new HttpClient($options);
    }
}