<?php

namespace Haya\Http;

/**
 * 模拟请求
 *
 * @create 2016-12-15
 * @author deatil
 */
class Curl 
{
    
    /**
     * 错误代码
     *
     * @create 2016-12-15
     * @author deatil
     */
    public $errno = 0;
    
    /**
     * 错误信息
     *
     * @create 2016-12-15
     * @author deatil
     */
    public $error = '';
    
    /**
     * CURL模拟GET请求
     *
     * @create 2016-12-15
     * @author deatil
     */
    public function get($url, $userOptions = array()) 
    {        
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 500,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => $url,
        );
        
        if (!empty($userOptions) && is_array($userOptions)) {
            $options = array_merge($options, $userOptions);
        }

        return $this->exec($options);
    }

    /**
     * 模拟POST提交数据函数
     *
     * @create 2016-12-15
     * @author deatil
     */
    public function post($url, $data = '', $userOptions = array()) 
    {        
        $data = is_array($data) ? http_build_query($data) : $data;  
        
        /**
         *    CURLOPT_SSL_VERIFYHOST的值
                 *
         *    设为0表示不检查证书
         *    设为1表示检查证书中是否有CN(common name)字段
         *    设为2表示在1的基础上校验当前的域名是否与CN匹配
         */
        $options = array(
            //CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],  // 模拟用户使用的浏览     
            //CURLOPT_HTTPHEADER => isset($GLOBALS['cookie_file']) ? $GLOBALS['cookie_file'] : '',  // 读取上面所储存的Cookie信息     
            CURLOPT_HEADER => 0,  // 显示返回的Header区域内容     
            CURLOPT_COOKIEFILE => array('Expect:'),  // 解决数据包大不能提交     
            
            CURLOPT_URL => $url, // 要访问的地址     
            CURLOPT_SSL_VERIFYPEER => false, // 对认证证书来源的检测 
            CURLOPT_SSL_VERIFYHOST => 2, // 从证书中检查SSL加密算法是否存在  
            CURLOPT_RETURNTRANSFER => true, // 获取的信息以文件流的形式返回 
            CURLOPT_TIMEOUT => 30, // 设置超时限制防止死循  
            
            CURLOPT_FOLLOWLOCATION => 1, // 使用自动跳转     
            CURLOPT_AUTOREFERER => 1, // 自动设置Referer     
            CURLOPT_POST => 1, // 发送一个常规的Post请求     
            CURLOPT_POSTFIELDS => $data, // Post提交的数据包     
        );
        
        if (!empty($userOptions) && is_array($userOptions)) {
            $options = array_merge($options, $userOptions);
        }
        
        return $this->exec($options);
    }  
    

    /**
     * 执行CURL
     *
     * @create 2016-12-15
     * @author deatil
     */
    public function exec($option = array()) 
    {
        //配置为空返回
        if (empty($option)) {
            $this->errno = '99999';
            $this->error = '配置未设置';
            
            return false;
        }
        
        // 启动一个CURL会话 
        $curl = curl_init();      
        
        //配置设置
        if (!empty($option) && is_array($option)) {
            curl_setopt_array($curl, $option);
        }
        
        // 执行操作  
        $result = curl_exec($curl);    
        
        if (curl_errno($curl)) {
            $this->errno = curl_errno($curl);
            $this->error = curl_error($curl);
            
            curl_close($curl);
            return false;
        } else {
            curl_close($curl); // 关键CURL会话
            return $result; // 返回数据      
        }   
    }
    
    /**
     * 获取错误ID
     *
     * @create 2016-12-15
     * @author deatil
     */
    public function getErrno()
    {
        return $this->errno;
    }
    
    /**
     * 获取错误信息
     *
     * @create 2016-12-15
     * @author deatil
     */
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * 获取全部错误信息
     *
     * @create 2016-12-15
     * @author deatil
     */
    public function getErrorInfo()
    {
        $data['errno'] = $this->errno;
        $data['error'] = $this->error;
        
        return $data;
    }

}


/** 
 * CURL批量设置
 *
 * @create 2016-9-12
 * @author deatil
 */  
if (!function_exists('curl_setopt_array')) {
    function curl_setopt_array(&$ch, $curl_options) {
        foreach ($curl_options as $option => $value) {
            if (!curl_setopt($ch, $option, $value)) {
                return false;
            } 
        }
        return true;
    }
}

