<?php

namespace Haya\Http;

use Exception;

/**
 * SOCKET文件
 * 
 * @create 2017-6-30
 * @author deatil 
 */
class Socket 
{
    
    /**
     * 地址
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public $address;

    /**
     * 端口
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public $port;

    /**
     * 发送的数据
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public $data = null;

    /**
     * 创建句柄
     * 
     * @create 2017-6-30
     * @author deatil
     */
    protected $sock;
    
    /**
     * 连接的次数
     * 
     * @create 2017-6-30
     * @author deatil
     */
    private $link = 0;
    
    /**
     * 构造函数 
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function __construct()
    {
    }

    /**
     * 设置发送地址
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function withAddress($address) 
    {
        $new = clone $this;
        $new->address = $address;
        return $new;
    }

    /**
     * 获取发送地址
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function getAddress($address) 
    {
        return $this->address;
    }

    /**
     * 设置端口
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function withPort($port) 
    {
        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    /**
     * 获取端口
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function getPort($port) 
    {
        return $this->port;
    }

    /**
     * 设置端口
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function withData($data) 
    {
        $new = clone $this;
        $new->data = $data;
        return $new;
    }

    /**
     * 获取端口
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function getData() 
    {
        return $this->data;
    }
    
    /**
     * 发送 
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function send()
    {
        $this->create()->connect()->wirte();
    }

    /**
     * socket远程连接
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function connect() 
    {
        if (!socket_connect($this->sock, $this->address, $this->port)) {
            $this->halt($this->getStrerror($this->getLastError()));
        }
        
        ++ $this->link;
        
        return $this;
    }

    /**
     * 创建SOCKET
     * 
     * @create 2017-6-30
     * @author deatil
     */
    protected function create() 
    {
        $this->sock = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));
        if (!$this->sock) {
            $this->halt('create socket error.');
        }
        
        return $this;
    }

    /**
     * socket寫入數據
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function wirte($data = null) 
    {
        if (!empty($data)) {
            $this->data = $data;
        }
        socket_write($this->sock, $this->data, strlen($this->data));
    }

    /**
     * socket读取状态
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function read() 
    {
        return socket_read($this->sock, 4096);
    }

    /**
     * 设置配置
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function setOption(
        $sock = null, 
        $level = SOL_SOCKET, 
        $optname = SO_REUSEADDR, 
        $optval = 1
    ) {
        if (empty($sock)) {
            $sock = $this->sock;
        }
        socket_set_option($sock, $level, $optname, $optval);
        
        return $this;
    }

    /**
     * 绑定地址
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function bind($address, $port)
    {
        socket_bind($this->sock, $address, $port);

        return $this;
    }

    /**
     * 监听端口
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function listen($port = 20)
    {
        socket_listen($this->sock, $port);

        return $this;
    }

    /**
     * 选择主机
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function select(
        $read = null, 
        $write = null, 
        $except = null, 
        $tvSec = null, 
        $tvUsec = 0
    ) {
        socket_select($read, $write, $except, $tvSec, $tvUsec);

        return $this;
    }

    /**
     * 选择
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function accept($sock)
    {
        socket_accept($sock);

        return $this;
    }

    /**
     * 大小
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function recv($sock, $buffer, $len = 2048, $flags = 0)
    {
        socket_recv($sock, $buffer, $len, $flags);

        return $this;
    }

    /**
     * 关闭socket
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function colse() 
    {
        socket_close($this->sock);
    }

    /**
     * 获取最后错误信息
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function getLastError() 
    {
        return socket_last_error();
    }

    /**
     * 获取错误信息
     * 
     * @create 2017-6-30
     * @author deatil
     */
    public function getStrerror($error) 
    {
        return socket_strerror($error);
    }
    
    /**
     * 抛出异常
     * 
     * @create 2017-6-30
     * @author deatil
     */
    protected function halt($info)
    {
        throw new Exception($info);
    }
    
}
