<?php

namespace linkphp\cookie;

use Config;

class Cookie
{

    /**
     * COOKIE配置
     */
    protected $config = array();

    public function __construct(array $config = [])
    {
        if(empty($this->config)){
            $this->config = Config::get('cookie.');
        }

        $this->config = array_merge($this->config, array_change_key_case($config));

    }

    /**
     * 获取COOKIE
     *
     * @param string $name 待获取的COOKIE名字
     * @return string/NULL/array $name为NULL时返回整个$_COOKIE，存在时返回COOKIE，否则返回NULL
     */
    public function get($name = NULL)
    {
        if ($name === NULL) {
            return $_COOKIE;
        }
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : NULL;
    }

    /**
     * 设置COOKIE
     *
     * @param string $name 待设置的COOKIE名字
     * @param string/int $value 建议COOKIE值为一些简单的字符串或数字，不推荐存放敏感数据
     * @param int $expire 有效期的timestamp，为NULL时默认存放一个月
     * @param boolean
     * @return bool
     */
    public function set($name, $value, $expire = NULL)
    {
        if ($expire === NULL) {
            $expire = !empty($this->config['expire']) ?
                $_SERVER['REQUEST_TIME'] + intval($this->config['expire']) :
                0;
        }
        return setcookie(
            $name,
            $value,
            $expire,
            $this->config['path'],
            $this->config['domain'],
            $this->config['secure'],
            $this->config['httponly']
        );
    }

    /**
     * 删除COOKIE
     *
     * @param strint $name 待删除的COOKIE名字
     * @param boolean
     * @see Cookie::set()
     * @return bool
     */
    public function delete($name)
    {
        return $this->set($name, '', 0);
    }

    /**
     * 获取COOKIE的配置
     */
    public function getConfig()
    {
        return $this->config;
    }
}