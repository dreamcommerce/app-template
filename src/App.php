<?php
use XmlImporter\Adapters\AdapterException;

/**
 * Class App
 * example for xml importing
 */
class App
{

    /**
     * @var null|DreamCommerce\Client
     */
    protected $client = null;
    /**
     * @var string default locale
     */
    protected $locale = 'pl_PL';

    /**
     * @var array configuration storage
     */
    protected $config = array();

    /**
     * instantiate
     * @param array $config
     */
    public function __construct($config){
        $this->config = $config;
    }

    /**
     * main application bootstrap
     * @throws Exception
     */
    public function bootstrap()
    {

        // check request hash and variables
        $this->validateRequest();

        $this->locale = basename($_GET['translations']);

        // detect if shop is already installed
        $shopData = $this->getShopData($_GET['shop']);
        if (!$shopData) {
            throw new Exception(_('An application is not installed in this shop'));
        }

        // preserve shop URL for tpl
        $this['shopUrl'] = $shopData['url'];

        // refresh token
        if (strtotime($shopData['expires']) - time() < 86400) {
            $shopData = $this->refreshToken($shopData);
        }

        // instantiate SDK client
        $this->client = $this->getClient($shopData);

        // fire
        $this->dispatch();

    }

    /**
     * dispatcher
     * @throws Exception
     */
    protected function dispatch()
    {

        $query = empty($_GET['q']) ? 'index/index' : $_GET['q'];
        $query = str_replace('\\', '', $query);

        $queryData = explode('/', $query);

        $class = '\\Controllers\\'.$queryData[0];

        if(!class_exists($class)){
            throw new Exception('Controller not found');
        }

        $actionName = strtolower($queryData[1]).'Action';
        $controller = new $class($this);
        if(!method_exists($controller, $actionName)){
            throw new Exception('Action not found');
        }

        call_user_func_array(array($controller, $actionName), array_slice($queryData, 1));

        $viewName = strtolower($queryData[0]).'_'.strtolower($queryData[1]);

        $controller->render($viewName);

    }

    /**
     * get client resource
     * @param $shopData
     * @return \DreamCommerce\Client
     */
    public function getClient($shopData)
    {

        $c = new DreamCommerce\Client($shopData['url'], $this->config['appId'], $this->config['appSecret']);
        $c->setAccessToken($shopData['token']);

        return $c;
    }

    /**
     * refresh OAuth token
     * @param array $shopData
     * @return mixed
     * @throws Exception
     */
    public function refreshToken($shopData)
    {
        $c = new DreamCommerce\Client($shopData['url'], $this->config['appId'], $this->config['appSecret']);
        $tokens = $c->refreshToken($shopData['refresh_token']);

        try {
            $db = $this->db();
            $stmt = $db->prepare('update access_tokens set refresh_token=?, access_token=?, expires=? where shop_id=?');
            $stmt->execute(array($tokens['refresh_token'], $tokens['access_token'], $tokens['expires']));
        } catch (PDOException $ex) {
            throw new Exception(_('Database error'), 0, $ex);
        }

        $shopData['refresh_token'] = $tokens['refresh_token'];
        $shopData['access_token'] = $tokens['access_token'];

        return $shopData;
    }

    /**
     * checks variables and hash
     * @throws Exception
     */
    public function validateRequest()
    {
        if (empty($_GET['translations'])) {
            throw new Exception(_('Invalid request'));
        }

        $params = array(
            'place' => $_GET['place'],
            'shop' => $_GET['shop'],
            'timestamp' => $_GET['timestamp'],
        );

        ksort($params);
        $parameters = array();
        foreach ($params as $k => $v) {
            $parameters[] = $k . "=" . $v;
        }
        $p = join("&", $parameters);


        $hash = hash_hmac('sha512', $p, $this->config['appstoreSecret']);

        if ($hash != $_GET['hash']) {
            throw new Exception(_('Invalid request'));
        }

    }

    /**
     * get installed shop info
     * @param $shop
     * @return array|bool
     */
    public function getShopData($shop)
    {
        $db = $this->db();
        $stmt = $db->prepare('select a.access_token as token, a.refresh_token as refresh_token, s.shop_url as url, a.expires_at as expires, a.shop_id as id from access_tokens a join shops s on a.shop_id=s.id where s.shop=?');
        if (!$stmt->execute(array($shop))) {
            return false;
        }

        return $stmt->fetch();

    }

    /**
     * instantiate db connection
     * @return PDO
     */
    public function db()
    {
        static $handle = null;
        if (!$handle) {
            $handle = new PDO(
                $this->config['db']['connection'],
                $this->config['db']['user'],
                $this->config['db']['pass']
            );
        }

        return $handle;
    }

}