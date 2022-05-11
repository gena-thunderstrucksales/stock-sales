<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Application specific global variables
class Globals  
{
    private static $authenticatedMemberId = null;
    private static $initialized = false;
    private static  $temp_list_pictures_products = null;

    private static function initialize()
    {
        if (self::$initialized)
            return;

        self::$authenticatedMemberId = null;
        self::$initialized = true;
        self::$temp_list_pictures_products = array();
    }

    public static function initialize_temp_list_pictures_products($list_pictures_products)
    {
        self::initialize();
        self::$temp_list_pictures_products = $list_pictures_products;
    }

    public static function set_temp_list_pictures_products($item)
    {
        self::initialize();
        array_push(self::$temp_list_pictures_products, $item);
    }

    public static function get_temp_list_pictures_products()
    {
        self::initialize();
        return self::$temp_list_pictures_products;
    }


    public static function setAuthenticatedMemeberId($memberId)
    {
        self::initialize();
        self::$authenticatedMemberId = $memberId;
    }


    public static function authenticatedMemeberId()
    {
        self::initialize();
        return self::$authenticatedMemberId;
    }
}
