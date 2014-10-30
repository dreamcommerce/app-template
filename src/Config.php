<?php
/**
 * Configuration data class
 */
class Config
{
    /**
     * appstore application ID
     */
    const APPID = '<app-id>';

    /**
     * appsecret used within communication APP->SHOP
     */
    const APP_SECRET = '<app-secret>';

    /**
     * appsecret used within communication SHOP->APP
     */
    const APPSTORE_SECRET = '<appstore-secret>';

    /**
     * instantiate db connection
     * @return PDO
     */
    public static function dbConnect()
    {
        static $handle = null;
        if (!$handle) {
            $handle = new \PDO('mysql:host=127.0.0.1;dbname=app', 'app', 'app');
        }

        return $handle;
    }
}