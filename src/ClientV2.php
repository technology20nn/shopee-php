<?php

namespace Shopee;

use function _PHPStan_76800bfb5\RingCentral\Psr7\stream_for;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Shopee\Nodes\NodeAbstract;
use Shopee\Nodes;
use Shopee\Exception\Api\AuthException;
use Shopee\Exception\Api\BadRequestException;
use Shopee\Exception\Api\ClientException;
use Shopee\Exception\Api\Factory;
use Shopee\Exception\Api\ServerException;

use function array_key_exists;
use function array_merge;
use function getenv;
use function json_encode;
use function time;

/**
 * @property Nodes\Shop\Authorization $author
 * @property Nodes\Shop\Shop $shop
 * @property Nodes\Item\Category $category
 * @property Nodes\Item\Brand $brand
 * @property Nodes\Item\Attribute $attribute
 * @property Nodes\Item\Item $item
 * @property Nodes\Image\Image $image
 * @property Nodes\Video\Video $video
 * @property Nodes\Logistics\Logistics $logistic
 * @property Nodes\Order\Order $order
 * @property Nodes\Payment\Payment $payment
 * @property Nodes\Returns\Returns $returns
 * @property Nodes\Discount\Discount $discount
 * @property Nodes\Chat\Chat $chat
 */
class ClientV2
{
    const VERSION = '2';

    const API_TYPE_PUBLIC = 1;
    const API_TYPE_SHOP = 2;
    const API_TYPE_MERCHANT = 3;

    /**
     * Test link
     * https://partner.test-stable.shopeemobile.com
     */
    public const DEFAULT_BASE_URL = 'https://partner.shopeemobile.com';

    public const DEFAULT_USER_AGENT = 'shopee-php/' . self::VERSION;

    public const ENV_SECRET_NAME = 'SHOPEE_API_SECRET';

    public const ENV_PARTNER_ID_NAME = 'SHOPEE_PARTNER_ID';

    public const ENV_SHOP_ID_NAME = 'SHOPEE_SHOP_ID';

    protected $is_prod = false;

    /** @var ClientInterface */
    protected $httpClient;

    /** @var UriInterface */
    protected $baseUrl;

    /** @var string */
    protected $userAgent;

    /** @var string Shopee Partner Secret key */
    protected $secret;

    /** @var int */
    protected $partnerId;

    /** @var int */
    public $shopId;

    public $merchantId;

    /**
     * @var string
     */
    public $accessToken;

    protected $apiType;

    /** @var NodeAbstract[] */
    protected $nodes = [];

    /** @var SignatureGeneratorInterface */
    protected $signatureGenerator;

    public function __construct(array $config = [])
    {
        $config = array_merge([
            'httpClient' => null,
            'baseUrl' => self::DEFAULT_BASE_URL,
            'userAgent' => self::DEFAULT_USER_AGENT,
            'secret' => getenv(self::ENV_SECRET_NAME),
            'partner_id' => (int)getenv(self::ENV_PARTNER_ID_NAME),
            'shop_id' => (int)getenv(self::ENV_SHOP_ID_NAME),
            'access_token' => '',
            'api_type' => self::API_TYPE_PUBLIC,
            'merchant_id' => '',
            SignatureGeneratorInterface::class => null,
        ], $config);

        $this->httpClient = $config['httpClient'] ?: new HttpClient();
        $this->setBaseUrl($config['baseUrl']);
        $this->setUserAgent($config['userAgent']);
        $this->secret = $config['secret'];
        $this->partnerId = $config['partner_id'];
        $this->shopId = $config['shop_id'];
        $this->accessToken = $config['access_token'];
        $this->merchantId = $config['merchant_id'];
        $this->apiType = $config['api_type'];

        $signatureGenerator = $config[SignatureGeneratorInterface::class];
        if (is_null($signatureGenerator)) {
            $this->signatureGenerator = new SignatureGenerator($this->secret);
        } elseif ($signatureGenerator instanceof SignatureGeneratorInterface) {
            $this->signatureGenerator = $signatureGenerator;
        } else {
            throw new InvalidArgumentException('Signature generator not implement SignatureGeneratorInterface');
        }

        $this->nodes['author'] = new Nodes\Shop\Authorization($this);
        $this->nodes['shop'] = new Nodes\Shop\Shop($this);
        $this->nodes['category'] = new Nodes\Item\Category($this);
        $this->nodes['brand'] = new Nodes\Item\Brand($this);
        $this->nodes['attribute'] = new Nodes\Item\Attribute($this);
        $this->nodes['item'] = new Nodes\Item\Item($this);
        $this->nodes['image'] = new Nodes\Image\Image($this);
		$this->nodes['logistic'] = new Nodes\Logistics\Logistics($this);
		$this->nodes['order'] = new Nodes\Order\Order($this);
		$this->nodes['payment'] = new Nodes\Payment\Payment($this);
		$this->nodes['video'] = new Nodes\Video\Video($this);
		$this->nodes['returns'] = new Nodes\Returns\Returns($this);
		$this->nodes['discount'] = new Nodes\Discount\Discount($this);
		$this->nodes['chat'] = new Nodes\Chat\Chat($this);
    }

    public function __get(string $name)
    {
        if (!array_key_exists($name, $this->nodes)) {
            throw new InvalidArgumentException(sprintf('Property "%s" not exists', $name));
        }

        return $this->nodes[$name];
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param ClientInterface $client
     * @return $this
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getBaseUrl(): UriInterface
    {
        return $this->baseUrl;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setBaseUrl(string $url)
    {
        $this->baseUrl = new Uri($url);

        return $this;
    }

    public function getDefaultParameters($type_api = self::API_TYPE_SHOP): array
    {
        if($type_api == self::API_TYPE_PUBLIC){
            return [
                'partner_id' => $this->partnerId,
                'timestamp' => time(), // Put the current UNIX timestamp when making a request
            ];
        }else{
            return [
                'partner_id' => $this->partnerId,
                'shopid' => $this->shopId,
                'timestamp' => time(), // Put the current UNIX timestamp when making a request
            ];
        }
    }

    /**
     * Create HTTP JSON body
     *
     * The HTTP body should contain a serialized JSON string only
     *
     * @param array $data
     * @return string
     */
    protected function createJsonBody(array $data, $type_api = self::API_TYPE_SHOP): string
    {
        $data = array_merge($this->getDefaultParameters($type_api), $data);

        return json_encode($data);
    }

    /**
     * Generate an HMAC-SHA256 signature for a HTTP request
     *
     * @param UriInterface $uri
     * @param integer $api_type
     * @return string
     */
    protected function signature($uri, $api_type): string
    {
        switch ($api_type) {
            case self::API_TYPE_PUBLIC:
                return $auth_query = $this->signatureGenerator->generateSignaturePublicLevel($uri, $this->partnerId);
            case self::API_TYPE_SHOP:
                return $auth_query = $this->signatureGenerator->generateSignatureShopLevel($uri, $this->partnerId, $this->accessToken, $this->shopId);
            case self::API_TYPE_MERCHANT:
                return $auth_query = $this->signatureGenerator->generateSignatureMerchantLevel($uri, $this->partnerId, $this->accessToken, $this->merchantId);
            default:
                return "";
        }
    }

    /**
     * @param string|UriInterface $uri
     * @param $api_type integer
     * @param array $headers
     * @param array $data
     * @return RequestInterface
     */
    public function newRequest($uri, $api_type, array $headers = [], $data = []): RequestInterface
    {
        $uri = Utils::uriFor($uri);
        $auth_query = $this->signature($uri, $api_type);
        $path = $this->baseUrl->getPath() . $uri->getPath();
        $uri = $uri
            ->withScheme($this->baseUrl->getScheme())
            ->withUserInfo($this->baseUrl->getUserInfo())
            ->withHost($this->baseUrl->getHost())
            ->withPort($this->baseUrl->getPort())
            ->withPath($path)
            ->withQuery($auth_query);

        $jsonBody = $this->createJsonBody($data, $api_type);

        $headers['User-Agent'] = $this->userAgent;
        $headers['Content-Type'] = 'application/json';

        return new Request(
            'POST', // All APIs should use POST method
            $uri,
            $headers,
            $jsonBody
        );
    }

    public function upload(RequestInterface $request, $file_url, $field_name = 'image'): ResponseInterface
    {
        try {
            list($tempImageDownload, $fileName) = $this->downloadFile($file_url);
            $response = $this->httpClient->request(
                "POST",
                $request->getUri(),
                [
                'multipart' => [
                    [
                        'name' => $field_name,
                        'contents' => fopen($tempImageDownload, 'r'),
                        'file_name' => $fileName
                    ],
                ]
            ]);
        } catch (GuzzleClientException $exception) {
            switch ($exception->getCode()) {
                case 400:
                    $className = BadRequestException::class;
                    break;
                case 403:
                    $className = AuthException::class;
                    break;
                default:
                    $className = ClientException::class;
            }

            throw Factory::create($className, $exception);
        } catch (GuzzleServerException $exception) {
            throw Factory::create(ServerException::class, $exception);
        } catch (GuzzleException $exception) {
            throw Factory::create(ServerException::class, $exception);
        }

        return $response;
    }

    public function uploadVideoPart(RequestInterface $request, $video_upload_id, $file_name, $chunk, $part_seq): ResponseInterface
    {
        try {
            $part_md5 = md5($chunk);
            $boundary  = '----iCEBrkUploaderBoundary' . uniqid();
            $response = $this->httpClient->request(
                "POST",
                $request->getUri(),
                [
                    'headers' => [
                        'Transfer-Encoding'   => 'chunked',
                        'Accept-Encoding'     => 'gzip, deflate, br',
                        'Accept'              => 'application/json, text/javascript, */*; q=0.01',
                        'Content-disposition' => 'attachment; filename="' . $file_name . '"',
                        'Content-length'      => strlen($chunk)-1,
                    ],
                    'multipart' => [
                        [
                            'name' => 'video_upload_id',
                            'contents' => $video_upload_id,
                        ],
                        [
                            'name' => 'part_seq',
                            'contents' => $part_seq,
                        ],
                        [
                            'name' => 'content_md5',
                            'contents' => $part_md5,
                        ],
                        [
                            'name' => 'part_content',
                            'contents' => $chunk,
                            'filename' => $file_name,
                            'headers' => [
                                'Content-Type' => 'multipart/form-data; boundary=' . $boundary
                            ]
                        ]
                    ]
                ]);

        } catch (GuzzleClientException $exception) {
            switch ($exception->getCode()) {
                case 400:
                    $className = BadRequestException::class;
                    break;
                case 403:
                    $className = AuthException::class;
                    break;
                default:
                    $className = ClientException::class;
            }

            throw Factory::create($className, $exception);
        } catch (GuzzleServerException $exception) {
            throw Factory::create(ServerException::class, $exception);
        } catch (GuzzleException $exception) {
            throw Factory::create(ServerException::class, $exception);
        }

        return $response;
    }

    public function send(RequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->httpClient->send($request);
        } catch (GuzzleClientException $exception) {
            switch ($exception->getCode()) {
                case 400:
                    $className = BadRequestException::class;
                    break;
                case 403:
                    $className = AuthException::class;
                    break;
                default:
                    $className = ClientException::class;
            }

            throw Factory::create($className, $exception);
        } catch (GuzzleServerException $exception) {
            throw Factory::create(ServerException::class, $exception);
        }

        return $response;
    }

    public function getAuthorizationUrl($redirect_url)
    {
        $uri = Utils::uriFor("/api/v2/shop/auth_partner");
        $auth_query = $this->signature($uri, self::API_TYPE_PUBLIC);
        $uri = Utils::uriFor($uri);
        $path = $this->baseUrl->getPath() . $uri->getPath();

        $uri = $uri
            ->withScheme($this->baseUrl->getScheme())
            ->withUserInfo($this->baseUrl->getUserInfo())
            ->withHost($this->baseUrl->getHost())
            ->withPort($this->baseUrl->getPort())
            ->withPath($path)
            ->withQuery($auth_query);
        $uri = Uri::withQueryValue($uri, 'redirect', $redirect_url);
        return $uri->__toString();
    }

    public function downloadFile($urlDownload)
    {
        $path_info = pathinfo($urlDownload);
        $filename = $path_info['basename'];
        $tempImage = tempnam(sys_get_temp_dir(), 'shopee').$filename;
        copy($urlDownload, $tempImage);
        return [$tempImage, $filename];
    }

}
