<?php


namespace Nickmel\SMSTo;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use Nickmel\SMSTo\Exception\InvalidArgumentException;

/**
 * Class SMSTo
 * @package Nickmel\SMSTo
 */
class SMSTo
{
    /**
     * @var
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $message;

    /**
     * @var
     */
    protected $messages;

    /**
     * @var
     */
    protected $senderId;

    /**
     * @var
     */
    protected $recipients;

    /**
     * @var
     */
    protected $listId;

    /**
     * @var
     */
    protected $baseUrl;

    /**
     * @var
     */
    protected $callbackUrl;

    /**
     * @var string
     */
    protected $environment = 'sandbox';

    /**
     * @var
     */
    protected $options;

    /**
     * @var
     */
    protected $clientId;

    /**
     * @var
     */
    protected $clientSecret;

    /**
     * @var
     */
    protected $username;

    /**
     * @var
     */
    protected $password;

    /**
     * @var
     */
    protected $credentials;

    /**
     * SMSToAPI constructor.
     *
     * @param string|null $clientId
     * @param string|null $clientSecret
     * @param string|null $username
     * @param string|null $password
     * @param string|null $baseUrl
     * @param string|null $callbackUrl
     * @throws InvalidArgumentException
     */
    public function __construct(string $clientId = null, string $clientSecret = null, string $username = null, string $password = null, string $baseUrl = null, string $callbackUrl = null)
    {
        if(function_exists('config'))
        {
            !is_null($clientId) ? $this->clientId = $clientId : $this->clientId = config('laravel-sms-to.client_id');
            !is_null($clientSecret) ? $this->clientSecret = $clientSecret : $this->clientSecret = config('laravel-sms-to.client_secret');
            !is_null($username) ? $this->username = $username : $this->username = config('laravel-sms-to.username');
            !is_null($password) ? $this->password = $password : $this->password = config('laravel-sms-to.password');
            !is_null($baseUrl) ? $this->baseUrl = $baseUrl : $this->baseUrl = config('laravel-sms-to.base_url');
            !is_null($callbackUrl) ? $this->callbackUrl = $callbackUrl : $this->callbackUrl = config('laravel-sms-to.callback_url');
        } else {
            throw new InvalidArgumentException(sprintf('Unable to create the "%s" object', SMSTo::class));
        }
    }

    /**
     * Get the access token
     *
     * @return bool|mixed|string|null
     */
    public function getAccessToken()
    {
        if ($this->accessToken)
        {
            return $this->accessToken;
        }

        $url = $this->baseUrl . '/oauth/token';

        $this->credentials = [
            'grant_type' => 'password',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->username,
            'password' => $this->password,
            'scope' => '*'
        ];

        return $this->token($url);
    }

    /**
     * Get the refresh token
     *
     * @return bool|mixed|string|null
     */
    public function refreshToken()
    {
        $url = $this->baseUrl . '/oauth/token';

        $this->credentials = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->accessToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => ''
        ];

        return $this->token($url);
    }

    /**
     * Make a request for a new token
     *
     * @param string $url
     * @return bool|mixed|string|null
     */
    public function token($url)
    {
        $response = $this->request($url, 'post', $this->credentials);
        if (isset($response['access_token'])) {
            $this->accessToken = $response['access_token'];
            return $response;
        }
        return false;
    }

    /**
     * Get the account balance
     *
     * @return array
     */
    public function getBalance()
    {
        $this->getAccessToken();

        $path = $this->baseUrl . '/balance';

        return $this->request($path, 'post');
    }

    /**
     * Send a single personalized SMS to a number or multiple personalized SMS to multiple numbers
     *
     * @param array $messages
     * @param string|null $senderId
     * @param string|null $callbackUrl
     * @return array
     * @throws InvalidArgumentException
     */
    public function sendSingle($messages, $senderId = null, $callbackUrl = null)
    {
        if(is_null($messages) || empty($messages))
        {
            throw new InvalidArgumentException('Messages must be of type array and not empty');
        } else {
            $this->messages = $messages;
        }

        if(is_null($senderId))
        {
            $this->senderId = config('laravel-sms-to.sender_id');
        } else {
            $this->senderId = $senderId;
        }

        $this->callbackUrl = $callbackUrl;

        $this->getAccessToken();

        $path = $this->baseUrl . '/sms/single/send';
        $body = [
            'messages' => $this->messages,
            'sender_id' => $this->senderId,
            'callback_url' => $this->callbackUrl
        ];

        return $this->request($path, 'post', $body);
    }

    /**
     * Send an SMS to multiple numbers
     *
     * @param string $body
     * @param array $recipients
     * @param string|null $senderId
     * @param string|null $callbackUrl
     * @return array
     * @throws InvalidArgumentException
     */
    public function sendMultiple($body, $recipients, $senderId = null, $callbackUrl = null)
    {
        if(is_null($body))
        {
            throw new InvalidArgumentException('Body must be of type string and not empty');
        } else {
            $this->message = $body;
        }

        if(is_null($recipients) || empty($recipients))
        {
            throw new InvalidArgumentException('Recipients must be of type array and not empty');
        } else {
            $this->recipients = $recipients;
        }

        if(is_null($senderId))
        {
            $this->senderId = config('laravel-sms-to.sender_id');
        } else {
            $this->senderId = $senderId;
        }

        $this->callbackUrl = $callbackUrl;

        $this->getAccessToken();

        $path = $this->baseUrl . '/sms/send';

        $body = [
            'body' => $this->message,
            'to' => $this->recipients,
            'sender_id' => $this->senderId,
            'callback_url' => $this->callbackUrl
        ];

        return $this->request($path, 'post', $body);
    }

    /**
     * Sending an SMS to a predefined list
     *
     * @return array
     */
    public function sendList()
    {
        $this->getAccessToken();
        $path = $this->baseUrl . '/sms/send';

        $body = [
            'body' => $this->message,
            'to_list_id' => $this->listId,
            'sender_id' => $this->senderId,
            'callback_url' => $this->callbackUrl
        ];
        return $this->request($path, 'post', $body);
    }

    /**
     * Set the message
     *
     * @param string $message
     * @throws InvalidArgumentException
     */
    public function setMessage($message)
    {
        if (!is_string($message))
        {
            throw new InvalidArgumentException('Message must be of type string');
        }

        $this->message = $message;
    }

    /**
     * Set the messages
     *
     * @param array $messages
     * @throws InvalidArgumentException
     */
    public function setMessages($messages)
    {
        if (!is_array($messages))
        {
            throw new InvalidArgumentException('Messages must be of type array');
        }

        $this->messages = $messages;
    }

    /**
     * Set the sender id
     *
     * @param string $senderId
     * @throws InvalidArgumentException
     */
    public function setSenderId($senderId)
    {
        if (!is_string($senderId))
        {
            throw new InvalidArgumentException('Sender ID must be of type string');
        }

        $this->senderId = $senderId;
    }

    /**
     * Set the recipients
     *
     * @param array $recipients
     * @throws InvalidArgumentException
     */
    public function setRecipients($recipients)
    {
        if (!is_array($recipients))
        {
            throw new InvalidArgumentException('Recipients must be of type array');
        }

        $this->recipients = $recipients;
    }

    /**
     * Set the list id
     *
     * @param $listId
     * @return SMSTo
     */
    public function setListId($listId)
    {
        $this->listId = $listId;

        return $this;
    }

    /**
     * Set the callback URL
     *
     * @param $callbackUrl
     * @return SMSTo
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    /**
     * Send the request using GuzzleHttp
     *
     * @param $path
     * @param $method
     * @param array $body
     * @return bool|mixed|string|null
     */
    public function request($path, $method, $body = [])
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $headers['Authorization'] = 'Bearer ' . $this->accessToken;

        $client = new HttpClient(['headers' => $headers]);
        $response = null;

        try {
           switch ($method)
           {
               case 'get':
                   $response = $client->get($path)->getBody()->getContents();
                   break;
               case 'post':
                   $response = $client->post($path, ['json' => $body])->getBody()->getContents();
                   break;
               default:
                   $response = '';
                   break;
           }

           $response = json_decode($response, true);

        } catch (ClientException $e) {
            $response = $this->exception($e);
        } catch (ServerException $e) {
            $response = $this->exception($e);
        } catch (RequestException $e) {
            $response = $this->exception($e);
        } catch (\Exception $e) {
            $response = $this->exception($e);
        }

        return $response;
    }

    /**
     * Format the exception message
     *
     * @param $e
     * @return bool|mixed
     */
    public function exception($e)
    {
        if ($e->hasResponse())
        {
            $response = $e->getResponse();
            return json_decode($response->getBody()->getContents(), true);
        }

        return false;
    }
}