<?php

namespace Jcanda\Gotomeeting\Traits;

use Httpful\Request;
use Jcanda\Gotomeeting\Exception\GotoException;

trait GotoClient
{
    protected $G2W_uri = 'https://api.getgo.com/G2M/rest';
    protected $AUTH_uri = 'https://api.getgo.com';
    protected $timeout = 10; //seconds
    protected $verify_ssl = false;
    private $headers = [];
    private $response;
    private $message = '';

    //returns the body of the rest response
    function sendRequest($verb, $path, $parameters = null, $payload = null)
    {
        $verb = strtoupper(trim($verb));

        try {
            switch ($verb) {

                case 'GET':

                    $this->response = Request::get($this->getUrl($this->G2W_uri, $path, $parameters))
                            ->strictSSL($this->verify_ssl)
                            ->addHeaders($this->determineHeaders())
                            ->timeout($this->timeout)
                            ->expectsJson()
                            ->send();
                    break;

                case 'POST':

                    $this->response = Request::post($this->getUrl($this->G2W_uri, $path, $parameters))
                            ->strictSSL($this->verify_ssl)
                            ->addHeaders($this->determineHeaders())
                            ->timeout($this->timeout)
                            ->expectsJson()
                            ->sendsJson()
                            ->body($payload)
                            ->send();
                    break;


                case 'PUT':

                    $this->response = Request::put($this->getUrl($this->G2W_uri, $path, $parameters))
                            ->strictSSL($this->verify_ssl)
                            ->addHeaders($this->determineHeaders())
                            ->timeout($this->timeout)
                            ->expectsJson()
                            ->sendsJson()
                            ->body($payload)
                            ->send();
                    break;

                case 'DELETE':

                    $this->response = Request::delete($this->getUrl($this->G2W_uri, $path, $parameters))
                            ->strictSSL($this->verify_ssl)
                            ->addHeaders($this->determineHeaders())
                            ->timeout($this->timeout)
                            ->expectsJson()
                            ->send();
                    break;

                default:

                    break;
            }
        } catch (\Exception $e) {

            $this->throwResponseException($verb, $this->response, $e->getMessage());
        }

        return $this->processResultCode($verb, $this->response);
    }

    function getUrl($baseUri, $path, $parameters = null)
    {
        if (is_null($parameters)) {
            return $this->getBasePath($baseUri, $path);
        }

        return $this->getBasePath($baseUri, $path) . '?' . http_build_query($parameters);
    }

    function getBasePath($baseUri, $path)
    {
        return trim($baseUri, '/') . '/' . trim($path, '/');
    }

    function getPathRelativeToOrganizer($relativePathSection = null)
    {
        return sprintf('organizers/%s/', $this->getOrganizerKey()) . trim($relativePathSection, '/');
    }

    function getPathRelativeToAccount($relativePathSection = null)
    {
        return sprintf('accounts/%s/', $this->getAccountKey()) . trim($relativePathSection, '/');
    }

    function getAuthObject($path, $parameters = null)
    {
        try {
            $this->response = Request::post($this->getUrl($this->AUTH_uri, $path))
                    ->strictSSL($this->verify_ssl)
                    ->addHeaders($this->determineHeaders())
                    ->timeout($this->timeout)
                    ->expectsJson()
                    ->sendsForm()
                    ->body(http_build_query($parameters))                    
                    ->send();
        } catch (\Exception $e) {
            $this->throwResponseException('POST', $this->response, $e->getMessage());
        }

        if ($this->response->code >= 201 && !isset($this->response->body->access_token)) {
            var_dump($this->response->body);
            return false;
        }

        //the authObject is in the body of the response object
        return $this->response->body;
    }

    private function determineHeaders()
    {
        $this->headers['Authorization'] = 'Basic '.base64_encode(GOTO_CONSUMER_KEY.':'.GOTO_CONSUMER_KEY_SECRET);
        
        // if the accessObject exist it means the API can probably authenticate by token, this add it to the headers
        if (isset($_SESSION['GOTO_ACCESS_OBJECT'])) {
            //$this->headers['Authorization'] = $this->getAccessToken();
            $this->headers['Authorization'] = $_SESSION['GOTO_ACCESS_OBJECT']->access_token;
        }

        return $this->headers;
    }

    private function throwResponseException($verb, $response, $exceptionMessage = null): void
    {
        $this->message = $this->getResponseMessage($response->code);

        if (is_null($exceptionMessage)) {
            $exceptionMessage = isset($response->body->error) ? $response->body->error : $response->body->msg;
        }

        if ($response->hasErrors()) {
            if ($response->hasBody()) {
                switch ($response->code) {

                    case '401':
                        throw new GotoException($this->message . ' - ' . $exceptionMessage);

                        break;

                    default:
                        throw new GotoException($this->message . ' - ' . $exceptionMessage);
                }
            }
        }

        throw new GotoException($this->message . ' - There was an error, make sure the API endpoint-url exist and if all the required data is given to the request');
    }

    private function getResponseMessage($responseCode)
    {
        return isset($responseCode) ? ' (' . $responseCode . ')' : 'unknown status';
    }

    /**
     * @param $response
     *
     * @throws GotoException
     */
    private function processResultCode($verb, $response)
    {
        if ($response->code > 204) { //if any error range
            $this->throwResponseException($verb, $response);
        } else {

            if (in_array($verb, [
                        'DELETE',
                        'UPDATE',
                        'PUT',
                    ])) {

                if (is_null($response->body) || empty($response->body)) {
                    return true; //return true if it was not an error and if the VERB was completed
                }
            }
        }

        return $response->body;
    }
}