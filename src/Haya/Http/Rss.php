<?php

namespace Haya\Http;

/**
 * RSS输出和生成类
 * 
 * @create 2017-6-28
 * @author deatil 
 */
class Rss 
{

    // 配置
    private $config = [
        'encoding'              =>  'UTF-8',                    // 编码
        'rssVer'                =>  '2.0',                      // 版本
        'channelTitle'          =>  '',                         // 网站标题
        'channelLink'           =>  '',                         // 网站首页地址
        'channelDescrīption'    =>  '',                         // 描述
        'language'              =>  'zh_CN',                    // 使用的语言（zh-cn表示简体中文）
        'copyright'             =>  '',                         // 授权信息
        'webMaster'             =>  '',                         // 管理员邮箱
        'managingEditor'        =>  '',                         // 编辑的邮箱地址
        'docs'                  =>  '',                         // rss地址
        'pubDate'               =>  '',                         // 最后发布的时间
        'lastBuildDate'         =>  '',                         // 最后更新的时间
        'generator'             =>  'RSS Generator',            // 生成器
        'category'              =>  '',
    ];
    
    // 生成的原RSS
    private $content = '';
    
    // Items部分
    private $items = [];
    
    /**
     * 获取配置信息
     *
     * @access public
     *
     * @return null
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function __get($name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }
        return null;
    }
    
    /**
     * 设置配置信息
     *
     * @access public
     *
     * @return null
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function __set($name, $value)
    {
        if (isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 判断配置信息
     *
     * @access public
     *
     * @return null
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }
    
    /**
     * 架构函数
     *
     * @access public
     * @param array $config  上传参数
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function __construct($config = []) 
    {
        $this->config['pubDate']        = Date('Y-m-d H:i:s',time());
        $this->config['lastBuildDate']  = Date('Y-m-d H:i:s', time());
        if (is_array($config)) {
            $this->config   =   array_merge($this->config, $config);
        }
    }
    
    /**
     * 获取Rss信息
     *
     * @access public
     *
     * @return string
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function getContent()
    {
        if (empty($this->content)) {
            $this->buildRSS();
        }
        
        return $this->content;
    }
    
    /**
     * 添加一个节点
     *
     * @access public
     * @param string $title
     * @param string $link
     * @param descrīption $descrīption
     *
     * @return self
     *
     * @create 2017-6-28
     * @author deatil
     */
    public function addItem(
        $title, 
        $link, 
        $descrīption, 
        $pubDate, 
        $guid, 
        $author, 
        $category 
    ) {
        $this->items[] = [
            'title'         => $title ,
            'link'          => $link,
            'descrīption'   => $descrīption,
            'pubDate'       => $pubDate,
            'category'      => $category,//实现分组
            'author'        => $author,
            'guid'          => $guid
        ];
        
        return $this;
    }
    
    /**
     * 清空节点信息
     *
     * @access public
     *
     * @return self
     *
     * @create 2017-6-28
     * @author deatil
     */
    public function clearItem() 
    {
        $this->items = [];
        
        return $this;
    }
    
    /**
     * 添加配置
     *
     * @access public
     * @param string $name 键
     * @param string $value 值
     *
     * @return self
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function withConfig($name, $value = null) 
    {
        $new = clone $this;
        if (is_array($name)) {
            $new->config = array_merge($new->config, $name);
        } else {
            $new->config[$name] = $value;
        }
        
        return $new;
    }
    
    /**
     * 获取配置
     *
     * @access public
     * @param string $name 键
     *
     * @return string
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function getConfig($name = null) 
    {
        if (empty($name)) {
            return $this->config;
        } elseif (isset($new->config[$name])) {
            return $new->config[$name];
        } else {
            return null;
        }
    }
    
    /**
     * 生成rss xml文件内容
     *
     * @access public
     *
     * @return string
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function buildRSS() 
    {
        $s = "<?xml version='1.0' encoding='{$this->encoding}'?>";
        $s .= "\r\n<rss version=\"{$this->rssVer}\">\n";
        $s .= "\t<channel>\r\n";
        $s .= "\t\t<title><![CDATA[{$this->channelTitle}]]></title>\r\n";
        $s .= "\t\t<link><![CDATA[{$this->channelLink}]]></link>\r\n";
        $s .= "\t\t<descrīption><![CDATA[{$this->channelDescrīption}]]></descrīption>\r\n";
        $s .= "\t\t<language>{$this->language}</language>\r\n";
        if (!empty($this->docs)) {
            $s .= "\t\t<docs><![CDATA[{$this->docs}]]></docs>\r\n";
        }
        if (!empty($this->copyright)) {
            $s .= "\t\t<copyright><![CDATA[{$this->copyright}]]></copyright>\r\n";
        }
        if (!empty($this->webMaster)) {
            $s .= "\t\t<webMaster><![CDATA[{$this->webMaster}]]></webMaster>\r\n";
        }
        if (!empty($this->managingEditor)) {
            $s .= "\t\t<managingEditor><![CDATA[{$this->managingEditor}]]></managingEditor>\r\n";
        }
        if (!empty($this->pubDate)) {
            $s .= "\t\t<pubDate>{$this->pubDate}</pubDate>\r\n";
        }
        if (!empty($this->lastBuildDate)) {
            $s .= "\t\t<lastBuildDate>{$this->lastBuildDate}</lastBuildDate>\r\n";
        }
        if (!empty($this->generator)) {
            $s .= "\t\t<generator>{$this->generator}</generator>\r\n";
        }
        // items
        for ($i = 0; $i < count($this->items); $i++) {
            $s .= "\t\t<item>\n";
            $s .= "\t\t\t<title><![CDATA[{$this->items[$i]['title']}]]></title>\r\n";
            $s .= "\t\t\t<link><![CDATA[{$this->items[$i]['link']}]]></link>\r\n";
            $s .= "\t\t\t<descrīption><![CDATA[{$this->items[$i]['descrīption']}]]></descrīption>\r\n";
            $s .= "\t\t\t<pubDate>{$this->items[$i]['pubDate']}</pubDate>\r\n";
            if (!empty($this->items[$i]['category'])) {
                $s .= "\t\t\t<category>{$this->items[$i]['category']}</category>\r\n";
            }
            if (!empty($this->items[$i]['author'])) {
                $s .= "\t\t\t<author>{$this->items[$i]['author']}</author>\r\n";
            }
            if (!empty($this->items[$i]['guid'])) {
                $s .= "\t\t\t<guid>{$this->items[$i]['guid']}</guid>\r\n";
            }
            $s .= "\t\t</item>\n";
        }
        // close
        $s .= "\t</channel>";
        $s .= "\r\n</rss>";
        $this->content = $s;
        
        return $this;
    }

    /**
     * 生成rss 将产生的rss内容直接打印输出
     *
     * @access public
     *
     * @return null
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function show() 
    {
        if (empty($this->content)) {
            $this->buildRSS();
        }
        
        echo($this->content);
    }
    
    /**
     * 将产生的rss内容保存到文件
     *
     * @access public
     * @access string $fname 要保存的文件名
     *
     * @return self
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function saveToFile($fname) 
    {
        if (empty($this->content)) {
            $this->buildRSS();
        }
        
        $handle = fopen($fname, 'w+');
        if ($handle === false) {
            return false;
        }
        
        fwrite($handle, $this->content);
        fclose($handle);
    }
    
    /**
     * 从文件中获取输出
     *
     * @access public
     * @access string $fname 文件名
     *
     * @return null
     * 
     * @create 2017-6-28
     * @author deatil
     */
    public function getFile($fname) 
    {
        $handle = fopen($fname, 'r');
        if ($handle === false) {
            return false;
        }
        
        while (!feof($handle)) {
            echo fgets($handle);
        }
        fclose($handle);
    }
}
