<?php
class EpiOAuth
{
    public $version = '1.0';
    protected $requestTokenURL;
    protected $accessTokenURL;
    protected $authenticateURL;
    protected $authorizeURL;
    protected $consumerKey;
    protected $consumerSecret;
    protected $token;
    protected $tokenSecret;
    protected $callback;
    protected $signatureMethod;
    protected $debug = false;
    protected $useSSL = false;
    protected $followLocation = false;
    protected $headers = array();
    protected $userAgent = 'EpiOAuth (http://github.com/jmathai/twitter-async/tree/)';
    protected $connectionTimeout = 5;
    protected $requestTimeout = 30;
    
    public function addHeader($header) {
        if (is_array($header) and !empty($header)) {
            $this->headers = array_merge($this->headers, $header);
        } elseif (!empty($header)) {
            $this->headers[] = $header;
        }
    }
    
    public function getAccessToken($params = null) {
        if (isset($_GET['oauth_verifier']) and !isset($params['oauth_verifier'])) {
            $params['oauth_verifier'] = $_GET['oauth_verifier'];
        }

        $response = $this->httpRequest('POST', $this->getURL($this->accessTokenURL), $params);

        return new EpiOAuthResponse($response);
    }
    
    public function getAuthenticateURL($token = null, $params = null) {
        $token = $token ? $token : $this->getRequestToken($params);

        if (is_object($token)) {
            $token = $token->oauth_token;
        }

        $addlParams = empty($params) ? '' : '&'. http_build_query($params, '', '&');
        
        return $this->getURL($this->authenticateURL) .'?oauth_token='. $token . $addlParams;
    }
    
    public function getAuthorizeURL($token = null, $params = null) {
        $token = $token ? $token : $this->getRequestToken($params);

        if (is_object($token)) {
            $token = $token->oauth_token;
        }

        return $this->getURL($this->authorizeURL) .'?oauth_token='. $token;
    }
    
    public function getRequestToken($params = null) {
        if (isset($this->callback) and !isset($params['oauth_callback'])) {
            $params['oauth_callback'] = $this->callback;
        }

        $response = $this->httpRequest('POST', $this->getURL($this->requestTokenURL), $params);
        
        return new EpiOAuthResponse($response);
    }
    
    public function getURL($URL) {
        if ($this->useSSL === true) {
            return preg_replace('/^http:/', 'https:', $URL);
        }

        return $URL;
    }
    
    public function httpRequest($method = null, $URL = null, $params = null, $isMultipart = false) {
        if (empty($method) or empty($URL)) {
            return false;
        }
        
        if (empty($params['oauth_signature'])) {
            $params = $this->prepareParameters($method, $URL, $params);
        }

        switch($method) {
            case 'GET':
                return $this->httpGet($URL, $params);
                break;            
            case 'POST':
                return $this->httpPost($URL, $params, $isMultipart);
                break;            
            case 'DELETE':
                return $this->httpDelete($URL, $params);
                break;    
        }
    }
    
    public function setDebug($bool = false) {
        $this->debug = (bool) $bool;
    }
    
    public function setFollowLocation($bool) {
        $this->followLocation = (bool) $bool;
    }
    
    public function setTimeout($requestTimeout = null, $connectionTimeout = null) {
        if ($requestTimeout !== null) {
            $this->requestTimeout = floatval($requestTimeout);
        }

        if ($connectionTimeout !== null) {
            $this->connectionTimeout = floatval($connectionTimeout);
        }
    }
    
    public function setToken($token = null, $secret = null) {
        $this->token       = $token;
        $this->tokenSecret = $secret;
    }
    
    public function setCallback($callback = null) {
        $this->callback = $callback;
    }
    
    public function useSSL($use = false) {
        $this->useSSL = (bool) $use;
    }
    
    protected function addDefaultHeaders($URL, $oauthHeaders) {
        $_h = array('Expect:');

        $URLParts = parse_URL($URL);

        $oauth = 'Authorization: OAuth realm="'. $URLParts['scheme'] .'://'. $URLParts['host'] . $URLParts['path'] .'",';

        foreach ($oauthHeaders as $name => $value) {
            $oauth .= "{$name}=\"{$value}\",";
        }

        $_h[] = substr($oauth, 0, -1);
        $_h[] = "User-Agent: {$this->userAgent}";

        $this->addHeader($_h);
    }
    
    protected function buildHttpQueryRaw($params) {
        $retval = '';
        $params = (array) $params;

        foreach ($params as $key => $value) {
            $retval .= "{$key}={$value}&";
        }

        $retval = substr($retval, 0, -1);
        
        return $retval;
    }
    
    protected function curlInit($URL) {
        $ch = curl_init($URL);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->requestTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        
        if ($this->followLocation) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }

        if (isset($_SERVER['SERVER_ADDR']) and !empty($_SERVER['SERVER_ADDR']) and $_SERVER['SERVER_ADDR'] !== '127.0.0.1') {
            curl_setopt($ch, CURLOPT_INTERFACE, $_SERVER['SERVER_ADDR']);
        }
                
        if (file_exists($cert = dirname(__FILE__) . DIRECTORY_SEPARATOR .'ca-bundle.crt')) {
            curl_setopt($ch, CURLOPT_CAINFO, $cert);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        return $ch;
    }
    
    protected function emptyHeaders() {
        $this->headers = array();
    }
    
    protected function encode_rfc3986($string) {
        return str_replace('+', ' ', str_replace('%7E', '~', rawURLencode(($string))));
    }
    
    protected function generateNonce() {
        if (isset($this->nonce)) {
            return $this->nonce;
        }

        return md5(uniqid(rand(), true));
    }
        
    protected function generateSignature($method = null, $URL = null, $params = null) {
        if (empty($method) or empty($URL)) {
            return false;
        }
                
        $concatenatedParams = $this->encode_rfc3986($this->buildHttpQueryRaw($params));
                
        $normalizedURL = $this->encode_rfc3986($this->normalizeURL($URL));
        $method        = $this->encode_rfc3986($method);
        
        $signatureBaseString = "{$method}&{$normalizedURL}&{$concatenatedParams}";

        return $this->signString($signatureBaseString);
    }
    
    protected function executecurl($ch) {
        if ($this->isAsynchronous) {
            return $this->curl->addcurl($ch);
        } else {
            return $this->curl->addEasycurl($ch);
        }
    }
    
    protected function httpDelete($URL, $params) {
        $this->addDefaultHeaders($URL, $params['oauth']);

        $ch = $this->curlInit($URL);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildHttpQueryRaw($params['request']));

        $response = $this->executecurl($ch);

        $this->emptyHeaders();

        return $response;
    }
    
    protected function httpGet($URL, $params = null) {
        if (count($params['request']) > 0) {
            $URL .= '?';

            foreach ($params['request'] as $k => $v) {
                $URL .= "{$k}={$v}&";
            }

            $URL = substr($URL, 0, -1);
        }

        $this->addDefaultHeaders($URL, $params['oauth']);

        $ch = $this->curlInit($URL);

        $response = $this->executecurl($ch);
        
        $this->emptyHeaders();
        
        return $response;
    }
    
    protected function httpPost($URL, $params = null, $isMultipart) {
        $this->addDefaultHeaders($URL, $params['oauth']);
        
        $ch = $this->curlInit($URL);

        curl_setopt($ch, CURLOPT_POST, 1);
        
        if ($isMultipart) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params['request']);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildHttpQueryRaw($params['request']));
        }

        $response = $this->executecurl($ch);
        
        $this->emptyHeaders();
        
        return $response;
    }
    
    protected function normalizeURL($URL = null) {
        $URLParts = parse_URL($URL);
        $scheme = strtolower($URLParts['scheme']);
        $host = strtolower($URLParts['host']);
        $port = isset($URLParts['port']) ? intval($URLParts['port']) : 0;
        
        $retval = strtolower($scheme) .'://'. strtolower($host);
        
        if (!empty($port) and (($scheme === 'http' and $port != 80) or ($scheme === 'https' and $port != 443))) {
            $retval .= ":{$port}";
        }
        
        $retval .= $URLParts['path'];

        if (!empty($URLParts['query'])) {
            $retval .= "?{$URLParts['query']}";
        }
        
        return $retval;
    }
    
    protected function isMultipart($params = null) { 
        if ($params) {
            foreach ($params as $k => $v) {
                if (strncmp('@', $k, 1) === 0) {
                    return true;
                }
            }
        }

        return false;
    }
    
    protected function prepareParameters($method = null, $URL = null, $params = null) {
        if (empty($method) or empty($URL)) {
            return false;
        }
        
        $oauth['oauth_consumer_key'] = $this->consumerKey;
        $oauth['oauth_token'] = $this->token;
        $oauth['oauth_nonce'] = $this->generateNonce();
        $oauth['oauth_timestamp'] = !isset($this->timestamp) ? time() : $this->timestamp; 
        $oauth['oauth_signature_method'] = $this->signatureMethod;
        
        if (isset($params['oauth_verifier'])) {
            $oauth['oauth_verifier'] = $params['oauth_verifier'];            
            unset($params['oauth_verifier']);
        }

        $oauth['oauth_version'] = $this->version;
        
        foreach ($oauth as $k => $v) {
            $oauth[$k] = $this->encode_rfc3986($v);
        }

        $sigParams = array();
        $hasFile   = false;

        if (is_array($params)) {
            foreach ($params as $k => $v) {
                if (strncmp('@', $k, 1) !== 0) {
                    $sigParams[$k] = $this->encode_rfc3986($v);
                    $params[$k]    = $this->encode_rfc3986($v);
                } else {
                    $params[substr($k, 1)] = $v;
                    unset($params[$k]);
                    $hasFile = true;
                }
            }
            
            if ($hasFile === true) {
                $sigParams = array();
            }
        }
        
        $sigParams = array_merge($oauth, (array) $sigParams);                
        ksort($sigParams);                
        $oauth['oauth_signature'] = $this->encode_rfc3986($this->generateSignature($method, $URL, $sigParams));
        
        return array(
            'request' => $params,
            'oauth'   => $oauth
        );
    }
    
    protected function signString($string = null) {
        $retval = false;

        switch($this->signatureMethod) {
            case 'HMAC-SHA1':
                $key    = $this->encode_rfc3986($this->consumerSecret) .'&'. $this->encode_rfc3986($this->tokenSecret);
                $retval = base64_encode(hash_hmac('sha1', $string, $key, true));
            break;
        }
        
        return $retval;
    }
    
    public function __construct($consumerKey, $consumerSecret, $signatureMethod = 'HMAC-SHA1') {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->signatureMethod = $signatureMethod;
        $this->curl = EpiCurl::getInstance();
    }
}

class EpiOAuthResponse
{
    private $__resp;
    protected $debug = false;
    
    public function __construct($response) {
        $this->__resp = $response;
    }
    
    public function __get($name) {
        if ($this->__resp->code != 200) {
            EpiOAuthException::raise($this->__resp, $this->debug);
        }

        parse_str($this->__resp->data, $result);
        
        foreach ($result as $k => $v) {
            $this->$k = $v;
        }
        
        return isset($result[$name]) ? $result[$name] : null;
    }
    
    public function __toString() {
        return $this->__resp->data;
    }
}

class EpiOAuthException extends Exception
{
    public static function raise($responseonse, $debug) {
        $message = $responseonse->responseText;
        
        switch($responseonse->code) {
            case 400:
                showAlert("An unknown problem has ocurred, try to login again", path());

            case 401:
                showAlert("An unknown problem has ocurred, try to login again", path());

            default:
                showAlert("An unknown problem has ocurred, try to login again", path());
        }
    }
}

class EpiSequence
{
    private $timers, $min, $max, $width = 100;

    public function __construct($timers) {
        $this->timers = $timers;
        
        $min = PHP_INT_MAX;
        $max = 0;

        foreach ($this->timers as $timer) {
            $min = min($timer['start'], $min);
            $max = max($timer['end'], $max);
        }

        $this->min = $min;
        $this->max = $max;
        $this->range = $max - $min;
        $this->step = floatval($this->range / $this->width);
    }
    
    public function renderAscii() {
        $tpl = '';

        foreach ($this->timers as $timer) {
            $tpl .= $this->tplAscii($timer);
        }

        return $tpl;
    }
    
    private function tplAscii($timer) {
        $lpad = $rpad = 0;
        $lspace = $chars = $rspace = '';

        if ($timer['start'] > $this->min) {
            $lpad = intval(($timer['start'] - $this->min) / $this->step);
        }

        if ($timer['end'] < $this->max) {
            $rpad = intval(($this->max - $timer['end']) / $this->step);
        }

        $mpad = $this->width - $lpad - $rpad;

        if ($lpad > 0) {
            $lspace = str_repeat(' ', $lpad);
        }

        if ($mpad > 0) {
            $chars = str_repeat('=', $mpad);
        }

        if ($rpad > 0) {
            $rspace = str_repeat(' ', $rpad);
        }
        
        $tpl = <<<TPL
({$timer['api']} ::  code={$timer['code']}, start={$timer['start']}, end={$timer['end']}, total={$timer['time']})
[{$lspace}{$chars}{$rspace}]
TPL;

        return $tpl;
    }
}

class EpiTwitter extends EpiOAuth
{
    const EPITWITTER_SIGNATURE_METHOD = 'HMAC-SHA1';
    const EPITWITTER_AUTH_OAUTH = 'oauth';
    const EPITWITTER_AUTH_BASIC = 'basic';

    protected $requestTokenURL = 'https://api.twitter.com/oauth/request_token';
    protected $accessTokenURL = 'https://api.twitter.com/oauth/access_token';
    protected $authorizeURL = 'https://api.twitter.com/oauth/authorize';
    protected $authenticateURL = 'https://api.twitter.com/oauth/authenticate';
    protected $apiURL = 'http://api.twitter.com';
    protected $apiVersionedURL = 'http://api.twitter.com';
    protected $searchURL = 'http://search.twitter.com';
    protected $userAgent = 'EpiTwitter (http://github.com/jmathai/twitter-async/tree/)';
    protected $apiVersion = '1';
    protected $isAsynchronous = false;
        
    public function delete($endpoint, $params = null) {
        return $this->request('DELETE', $endpoint, $params);
    }
    
    public function get($endpoint, $params = null) {
        return $this->request('GET', $endpoint, $params);
    }
    
    public function post($endpoint, $params = null) {
        return $this->request('POST', $endpoint, $params);
    }
        
    public function delete_basic($endpoint, $params = null, $username = null, $password = null) {
        return $this->request_basic('DELETE', $endpoint, $params, $username, $password);
    }
    
    public function get_basic($endpoint, $params = null, $username = null, $password = null) {
        return $this->request_basic('GET', $endpoint, $params, $username, $password);
    }
    
    public function post_basic($endpoint, $params = null, $username = null, $password = null) {
        return $this->request_basic('POST', $endpoint, $params, $username, $password);
    }
    
    public function useApiVersion($version = null) {
        $this->apiVersion = $version;
    }
    
    public function useAsynchronous($async = true) {
        $this->isAsynchronous = (bool) $async;
    }
    
    public function __construct($consumerKey = null, $consumerSecret = null, $oauthToken = null, $oauthTokenSecret = null) {
        parent::__construct($consumerKey, $consumerSecret, self::EPITWITTER_SIGNATURE_METHOD);
        
        $this->setToken($oauthToken, $oauthTokenSecret);
    }
    
    public function __call($name, $params = null) {
        $parts = explode('_', $name);
        $method = strtoupper(array_shift($parts));
        $parts = implode('_', $parts);
        $endpoint = '/'. preg_replace('/[A-Z]|[0-9]+/e', "'/'.strtolower('\\0')", $parts) .'.json';    
        $endpoint = str_replace('//', '/', $endpoint);
        $args = !empty($params) ? array_shift($params) : null;
                
        if ($this->consumerKey === null) {
            $username = null;
            $password = null;
            
            if (!empty($params)) {
                $username = array_shift($params);
                $password = !empty($params) ? array_shift($params) : null;
            }
            
            return $this->request_basic($method, $endpoint, $args, $username, $password);
        }
        
        return $this->request($method, $endpoint, $args);
    }
    
    private function getApiURL($endpoint) {
        if (preg_match('@^/search[./]?(?=(json|daily|current|weekly))@', $endpoint)) {
            return "{$this->searchURL}{$endpoint}";
        } elseif (!empty($this->apiVersion)) {
            return "{$this->apiVersionedURL}/{$this->apiVersion}{$endpoint}";
        } else {
            return "{$this->apiURL}{$endpoint}";
        }
    }
    
    private function request($method, $endpoint, $params = null) {
        $URL = $this->getURL($this->getApiURL($endpoint));

        $response = new EpiTwitterJson(call_user_func(array(
            $this,
            'httpRequest'
        ), $method, $URL, $params, $this->isMultipart($params)), $this->debug);

        if (!$this->isAsynchronous) {
            $response->response;
        }
        
        return $response;
    }
    
    private function request_basic($method, $endpoint, $params = null, $username = null, $password = null) {
        $URL = $this->getApiURL($endpoint);

        if ($method === 'GET') {
            $URL .= is_null($params) ? '' : '?'. http_build_query($params, '', '&');
        }

        $ch = curl_init($URL);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Expect:'
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->requestTimeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        if ($method === 'POST' and $params !== null) {
            if ($this->isMultipart($params)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildHttpQueryRaw($params));
            }
        }

        if (!empty($username) and !empty($password)) {
            curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
        }
        
        $response = new EpiTwitterJson(EpiCurl::getInstance()->addcurl($ch), $this->debug);

        if (!$this->isAsynchronous) {
            $response->response;
        }
        
        return $response;
    }
}

class EpiTwitterJson implements ArrayAccess, Countable, IteratorAggregate
{
    private $debug;
    private $__resp;

    public function __construct($responseonse, $debug = false) {
        $this->__resp = $responseonse;
        $this->debug  = $debug;
    }
        
    public function __destruct() {
        $this->responseText;
    }
    
    public function getIterator() {
        if ($this->__obj) {
            return new ArrayIterator($this->__obj);
        } else {
            return new ArrayIterator($this->response);
        }
    }
        
    public function count() {
        return count($this->response);
    }
        
    public function offsetSet($offset, $value) {
        $this->response[$offset] = $value;
    }
        
    public function offsetExists($offset) {
        return isset($this->response[$offset]);
    }
        
    public function offsetUnset($offset) {
        unset($this->response[$offset]);
    }
        
    public function offsetGet($offset) {
        return isset($this->response[$offset]) ? $this->response[$offset] : null;
    }
    
    public function __get($name) {
        $accessible = array(
            'responseText' => 1,
            'headers'      => 1,
            'code'         => 1
        );

        $this->responseText = $this->__resp->data;
        $this->headers      = $this->__resp->headers;
        $this->code         = $this->__resp->code;
        
        if (isset($accessible[$name]) and $accessible[$name]) {
            return $this->$name;
        } elseif (($this->code < 200 or $this->code >= 400) and !isset($accessible[$name])) {
            EpiTwitterException::raise($this->__resp, $this->debug);
        }
                
        $this->response = json_decode($this->responseText, 1);
        $this->__obj    = json_decode($this->responseText);
        
        if (gettype($this->__obj) === 'object') {
            foreach ($this->__obj as $k => $v) {
                $this->$k = $v;
            }
        }
        
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }
    
    public function __isset($name) {
        $value = self::__get($name);

        return !empty($name);
    }
}

class EpiTwitterException extends Exception
{
    public static function raise($responseonse, $debug) {
        $message = $responseonse->data;

        switch($responseonse->code) {
            case 400:
                throw new EpiTwitterBadRequestException($message, $responseonse->code);

            case 401:
                throw new EpiTwitterNotAuthorizedException($message, $responseonse->code);

            case 403:
                throw new EpiTwitterForbiddenException($message, $responseonse->code);

            case 404:
                throw new EpiTwitterNotFoundException($message, $responseonse->code);

            case 406:
                throw new EpiTwitterNotAcceptableException($message, $responseonse->code);

            case 420:
                throw new EpiTwitterEnhanceYourCalmException($message, $responseonse->code);

            case 500:
                throw new EpiTwitterInternalServerException($message, $responseonse->code);

            case 502:
                throw new EpiTwitterBadGatewayException($message, $responseonse->code);

            case 503:
                throw new EpiTwitterServiceUnavailableException($message, $responseonse->code);

            default:
                throw new EpiTwitterException($message, $responseonse->code);
        }
    }
}

class EpiTwitterBadRequestException extends EpiTwitterException {}
class EpiTwitterNotAuthorizedException extends EpiTwitterException {}
class EpiTwitterForbiddenException extends EpiTwitterException {}
class EpiTwitterNotFoundException extends EpiTwitterException {}
class EpiTwitterNotAcceptableException extends EpiTwitterException {}
class EpiTwitterEnhanceYourCalmException extends EpiTwitterException {}
class EpiTwitterInternalServerException extends EpiTwitterException {}
class EpiTwitterBadGatewayException extends EpiTwitterException {}
class EpiTwitterServiceUnavailableException extends EpiTwitterException {}
class EpiOAuthBadRequestException extends EpiOAuthException {}
class EpiOAuthUnauthorizedException extends EpiOAuthException {}


class EpiCurl
{
    const timeout = 3;

    static $inst = null;
    static $singleton = 0;

    private $mc;
    private $msgs;
    private $running;
    private $execStatus;
    private $selectStatus;
    private $sleepIncrement = 1.1;
    private $requests = array();
    private $responseonses = array();
    private $properties = array();
    private static $timers = array();
    
    public function __construct() {
        if (self::$singleton == 0) {
            throw new Exception('This class cannot be instantiated by the new keyword.  You must instantiate it using: $obj = EpiCurl::getInstance();');
        }
        
        $this->mc = curl_multi_init();

        $this->properties = array(
            'code'   => CURLINFO_HTTP_CODE,
            'time'   => CURLINFO_TOTAL_TIME,
            'length' => CURLINFO_CONTENT_LENGTH_DOWNLOAD,
            'type'   => CURLINFO_CONTENT_TYPE,
            'URL'    => CURLINFO_EFFECTIVE_URL
        );
    }
    
    public function addEasycurl($ch) {
        $key = $this->getKey($ch);

        $this->requests[$key] = $ch;
        
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(
            $this,
            'headerCallback'
        ));

        $done = array(
            'handle' => $ch
        );

        $this->storeResponse($done, false);
        $this->startTimer($key);

        return new EpiCurlManager($key);
    }
    
    public function addcurl($ch) {
        $key = $this->getKey($ch);

        $this->requests[$key] = $ch;

        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(
            $this,
            'headerCallback'
        ));
        
        $code = curl_multi_add_handle($this->mc, $ch);
        
        $this->startTimer($key);
                
        if ($code === curlM_OK or $code === curlM_CALL_MULTI_PERFORM) {
            do {
                $code = $this->execStatus = curl_multi_exec($this->mc, $this->running);
            } while ($this->execStatus === curlM_CALL_MULTI_PERFORM);
            
            return new EpiCurlManager($key);
        } else {
            return $code;
        }
    }
    
    public function getResult($key = null) {
        if ($key != null) {
            if (isset($this->responses[$key])) {
                return $this->responses[$key];
            }
            
            $innerSleepInt = $outerSleepInt = 1;

            while($this->running and ($this->execStatus == curlM_OK or $this->execStatus == curlM_CALL_MULTI_PERFORM)) {
                usleep(intval($outerSleepInt));

                $outerSleepInt = intval(max(1, ($outerSleepInt * $this->sleepIncrement)));
                $ms            = curl_multi_select($this->mc, 0);
                
                if ($ms > 0) {
                    do {
                        $this->execStatus = curl_multi_exec($this->mc, $this->running);
                        usleep(intval($innerSleepInt));
                        $innerSleepInt = intval(max(1, ($innerSleepInt * $this->sleepIncrement)));
                    } while($this->execStatus == curlM_CALL_MULTI_PERFORM);

                    $innerSleepInt = 1;
                }

                $this->storeResponses();

                if (isset($this->responses[$key]['data'])) {
                    return $this->responses[$key];
                }

                $runningCurrent = $this->running;
            }

            return null;
        }

        return false;
    }
    
    public static function getSequence() {
        return new EpiSequence(self::$timers);
    }
    
    public static function getTimers() {
        return self::$timers;
    }
    
    private function getKey($ch) {
        return (string) $ch;
    }
    
    private function headerCallback($ch, $header) {
        $_header  = trim($header);
        $colonPos = strpos($_header, ':');

        if ($colonPos > 0) {
            $key = substr($_header, 0, $colonPos);
            $val = preg_replace('/^\W+/', '', substr($_header, $colonPos));
            
            $this->responses[$this->getKey($ch)]['headers'][$key] = $val;
        }

        return strlen($header);
    }
    
    private function storeResponses() {
        while($done = curl_multi_info_read($this->mc)) {
            $this->storeResponse($done);
        }
    }
    
    private function storeResponse($done, $isAsynchronous = true) {
        $key = $this->getKey($done['handle']);

        $this->stopTimer($key, $done);

        if ($isAsynchronous) {
            $this->responses[$key]['data'] = curl_multi_getcontent($done['handle']);
        } else {
            $this->responses[$key]['data'] = curl_exec($done['handle']);
        }

        foreach ($this->properties as $name => $const) {
            $this->responses[$key][$name] = curl_getinfo($done['handle'], $const);
        }
        
        if ($isAsynchronous) {
            curl_multi_remove_handle($this->mc, $done['handle']);
        }

        curl_close($done['handle']);
    }
    
    private function startTimer($key) {
        self::$timers[$key]['start'] = microtime(true);
    }
    
    private function stopTimer($key, $done) {
        self::$timers[$key]['end']  = microtime(true);
        self::$timers[$key]['api']  = curl_getinfo($done['handle'], CURLINFO_EFFECTIVE_URL);
        self::$timers[$key]['time'] = curl_getinfo($done['handle'], CURLINFO_TOTAL_TIME);
        self::$timers[$key]['code'] = curl_getinfo($done['handle'], CURLINFO_HTTP_CODE);
    }
    
    static function getInstance() {
        if (self::$inst == null) {
            self::$singleton = 1;
            self::$inst      = new EpiCurl();
        }
        
        return self::$inst;
    }
}

class EpiCurlManager
{
    private $key;
    private $EpiCurl;
    
    public function __construct($key) {
        $this->key     = $key;
        $this->EpiCurl = EpiCurl::getInstance();
    }
    
    public function __get($name) {
        $responseonses = $this->EpiCurl->getResult($this->key);

        return isset($responseonses[$name]) ? $responseonses[$name] : null;
    }
    
    public function __isset($name) {
        $val = self::__get($name);

        return empty($val);
    }
}