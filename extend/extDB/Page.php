<?php
/**
 * @file paging_class.php
 * @brief 分页处理类
 * @date 2011-04-09
 * @version 0.6
 * @note
 */
/**
 * @brief IPaging 分页处理类
 * @class IPaging
 * @note
 */
namespace extDB;
class Page
{
	private $fields;
	private $db;
	private $sql;
	private $rows;
	public $cache;
	public $index;//当前页数
	public $totalpage;//总页数
	public $pagesize;//每页的条数
	public $firstpage;//第一页
	public $lastpage;//最后一页
	public $pagelength;//要展示的页面数
    /**
     * @brief 构造函数
     * @param int $count 数据数量
     * @param int $pagesize 每页的记录
     * @param int $pagelength 展示pageBar的页数
     */
	public function __construct($count=0,$pagesize=20,$pagelength=10)
	{
		$this->rows = isset($count) ? $count : 0;
		$this->pagesize=$pagesize;
		$this->pagelength=$pagelength;

		$this->firstpage=1;
		$this->totalpage=floor(($this->rows-1)/$this->pagesize)+1;
		$this->lastpage=$this->firstpage+$this->totalpage-1;
		if($this->lastpage>$this->totalpage)$this->lastpage=$this->totalpage;
	}

    /**
     * @brief 得到对应要查询分页的数据内容
     * @param int  $page要查询的页数
     * @param int  $p  查询的页码大于最大页面的标示 
     * @return Array 数据
     */
	public function getPageLimit($page,&$p=0)
	{
		$page=intval($page);
		$this->index=$page;
		if($page<=0)$this->index=1;
		if($this->totalpage>0)
		{
			if($page>$this->totalpage){$this->index=$this->totalpage;$p=1;}
			$this->firstpage=$this->index-floor($this->pagelength/2);
			if($this->firstpage<=0)$this->firstpage=1;
			$this->lastpage=$this->firstpage+$this->pagelength-1;
			if($this->lastpage>$this->totalpage)
			{
				$this->lastpage=$this->totalpage;
				$this->firstpage=($this->totalpage-$this->pagelength+1)>1?$this->totalpage-$this->pagelength+1:1;
			}

			return " limit ".($this->index-1)*$this->pagesize.",".($this->pagesize);
		}

	}
    /**
     * @brief 获取当前分页数
	 * @return int 分页数
	 */
	public function getIndex()
	{
		return $this->index;
	}
    /**
     * @brief 获取分页总数
	 * @return int 分页总数
	 */
	public function getTotalPage()
	{
		return $this->totalpage;
	}
    /**
     * @brief 设置展示的分页数量
	 * @return int 分页数量
	 */
	public function setPageLength($legth)
	{
		$this->pagelength=$legth;
	}
    /**
     * @brief 获取展示的分页数量
	 * @return int 分页长度
	 */
	public function getPageLength()
	{
		return $this->pagelength;
	}
    /**
     * @brief 设置每页的数据条数
     * @return int 数据条数
	 */
	public function setPageSize($size)
	{
		$this->pagesize  = $size;
		$this->totalpage = floor(($this->rows-1)/$this->pagesize)+1;
	}
    /**
     * @brief 得到单页要展示的数据条数
     * @return int 数据条数
     */
	public function getPageSize()
	{
		return $this->pagesize;
	}
    /**
     * @brief 当前pageBar的第一页
     * @return int 当前pageBar的第一页
     */
	public function getFirstPage()
	{
		return $this->firstpage;
	}
    /**
     * @brief 当前pageBar最得最后一页的页数
     * @return int 当前pageBar最后一页的页数
     */
	public function getLastPage()
	{
		return $this->lastpage;
	}
    /**
     * @brief 取得pageBar
     * @param string $url URL地址，一般为空！
     * @param string $attrs URL后接参数
     * @return array 分页数据
     */
	public function getPageData($url='', $attrs='')
    {
        $attr = '';
        if ($attrs != '') {
            $ajax_attr = " {$attrs} ";
        }
        $flag = false;
        if ($url == '') {
            $flag = true;
            $url = self::getUri();
            $url = preg_replace('/page=\d?&/', '', $url);
            $url = preg_replace('/(\?|&|\/)page(\/|=).*/i', '', $url);
            $url = str_replace('//', '/', $url);
            $mark = '=';
            if (strpos($url, '?') !== false)
                $index = '&page';
            else
                $index = '?page';
        } else {
            $flag = false;
            $index = '';
            $mark = '';
        }

        $baseUrl = "{$url}{$index}{$mark}";


        $pageData = array(
            'totalPage' => $this->totalpage,
            'current' => $this->getIndex(),
            'firstPage' => $this->firstpage,
			'lastPage' => $this->lastpage
        );

        $pageData['page'] = array(
            'head' => array('text' => '首页', 'url' => $baseUrl.'1', 'enable' => 1),
            'last' => array('text' => '尾页', 'url' => $baseUrl.$this->totalpage, 'enable' => 1),
            'prev' => array('text' => '上一页', 'url' => $baseUrl),
            'next' => array('text' => '下一页', 'url' => $baseUrl)
        );
        if ($this->index == $this->totalpage) {
            $pageData['page']['next']['enable'] = 0;
            $pageData['page']['next']['url'] ='';
        }else{
            $pageData['page']['next']['url'] .= $this->index + 1;
		}
        if ($this->index == 1) {
            $pageData['page']['prev']['enable'] = 1;
            $pageData['page']['prev']['url'] ='';
        }else{
            $pageData['page']['prev']['url'] .=$this->index - 1;
		}


        for ($i = $this->firstpage; $i <= $this->lastpage; $i++) {
            $pageData['page'][$i] = array(
                'text' => $i,
                'url' => $baseUrl.$i,
                'enable' => $i == $this->index ? 0 : 1
            );


        }

        return $pageData;


    }

    /**
     * @brief 获取当前URI地址
     * @return String 当前URI地址
     */
    public static function getUri()
    {
        if( !isset($_SERVER['REQUEST_URI']) ||  $_SERVER['REQUEST_URI'] == "" )
        {
            // IIS 的两种重写
            if (isset($_SERVER['HTTP_X_ORIGINAL_URL']))
            {
                $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
            }
            else if (isset($_SERVER['HTTP_X_REWRITE_URL']))
            {
                $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
            }
            else
            {
                //修正pathinfo
                if ( !isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO']) )
                    $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];


                if ( isset($_SERVER['PATH_INFO']) ) {
                    if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
                        $_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
                    else
                        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
                }

                //修正query
                if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
                {
                    $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
                }

            }
        }
        return $_SERVER['REQUEST_URI'];
    }
}
?>
