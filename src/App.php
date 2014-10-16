<?php
use BillingSystem\Config;

/**
 * Class App
 * example for xml importing
 */
class App implements ArrayAccess
{

    /**
     * @var array variables stored for view
     */
    protected $viewVars = array();
    /**
     * @var null|DreamCommerce\Client
     */
    protected $client = null;
    /**
     * @var string default locale
     */
    protected $locale = 'pl_PL';

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
        $shopData = $this->isShopInstalled($_GET['shop']);
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

        $categories = $this->client->categories->get();

        $this['categories'] = $categories;
        $this->render('index');

    }

    /**
     * get client resource
     * @param $shopData
     * @return \DreamCommerce\Client
     */
    protected function getClient($shopData)
    {

        $c = new DreamCommerce\Client($shopData['url'], Config::APPID, Config::APP_SECRET);
        $c->setAccessToken($shopData['token']);

        return $c;
    }

    /**
     * refresh OAuth token
     * @param array $shopData
     * @return mixed
     * @throws Exception
     */
    protected function refreshToken($shopData)
    {
        $c = new DreamCommerce\Client($shopData['url'], Config::APPID, Config::APP_SECRET);
        $tokens = $c->refreshToken($shopData['refresh_token']);

        try {
            $db = Config::dbConnect();
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
    protected function validateRequest()
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


        $hash = hash_hmac('sha512', $p, Config::APPSTORE_SECRET);

        if ($hash != $_GET['hash']) {
            throw new Exception(_('Invalid request'));
        }

    }

    /**
     * get installed shop info
     * @param $shop
     * @return array|bool
     */
    protected function isShopInstalled($shop)
    {
        $db = Config::dbConnect();
        $stmt = $db->prepare('select a.access_token as token, a.refresh_token as refresh_token, s.shop_url as url, a.expires_at as expires, a.shop_id as id from access_tokens a join shops s on a.shop_id=s.id where s.shop=?');
        if (!$stmt->execute(array($shop))) {
            return false;
        }

        return $stmt->fetch();

    }

    /**
     * shows more friendly exception message
     * @param Exception $ex
     */
    public function handleException(Exception $ex)
    {
        $this['message'] = $ex->getMessage();
        $this->render('exception');
    }


    // region templating

    /**
     * render application view
     * @param $tpl
     */
    public function render($tpl)
    {

        static $called = false;

        if ($called) {
            return;
        }

        $called = true;

        $vars = $this->viewVars;

        // separate scopes
        $render = function () use ($tpl, $vars) {
            extract($vars);
            require __DIR__ . '/../views/' . basename($tpl, '.php') . '.php';
        };

        $render();
    }


    public function offsetExists($offset)
    {
        return isset($this->viewVars[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->viewVars[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->viewVars[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->viewVars[$offset]);
    }

    // endregion
}