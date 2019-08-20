<?php

namespace system\vendor;
/*
 * 分页类
 */
class Pagination {

    var $page_name = 'p';
    
    var $count;
    var $total;
    var $offset;//生成的偏移量
    var $limit = 10;
    var $page;
    var $now_page;
    var $url;
    var $url_param = [];

    /*
     * 分页类初始化
     * @param int $count 数据总条数
     * @param int $limit 每页显示条数
     */
    public function __construct($count, $limit = NULL) {
        $this->count = $count;
        NULL === $limit || $this->limit = $limit;
        $this->pageInit();
    }

    /*
     * 分页相关数据初始化
     */
    private function pageInit() {
        $this->now_page = input($this->page_name, 1);//自动获取当前页码
        $this->offset = ($this->now_page - 1) * $this->limit;
        $this->total = ceil($this->count / $this->limit);
        $this->urlTamplate();
    }

    /*
     * 分页标签生成
     */
    public function getPage() {
        $this->url_param['p'] = 1;
        $firstPage = '<a page="'.$this->url_param['p'].'" href="' . $this->url . http_build_query($this->url_param) . '">首页</a>';
        $this->url_param['p'] = max(1, $this->now_page - 1);
        $prevPage = '<a page="'.$this->url_param['p'].'" href="' . $this->url . http_build_query($this->url_param) . '">上一页</a>';
        $this->url_param['p'] = min($this->total, $this->now_page + 1);
        $nextPage = '<a page="'.$this->url_param['p'].'" href="' . $this->url . http_build_query($this->url_param) . '">下一页</a>';
        $this->url_param['p'] = $this->total;
        $endPage = '<a page="'.$this->url_param['p'].'" href="' . $this->url . http_build_query($this->url_param) . '">尾页</a>';
        $page = '<div class="page">' . $firstPage . ' | '.  $prevPage .  ' | '.  $nextPage .  ' | '.  $endPage . '</div>';
        return $page;
    }

    /*
     * 分页链接url初始化
     */
    private function urlTamplate() {
        $host = $_SERVER['HTTP_HOST'];
        $agreement = strpos($_SERVER['SERVER_PROTOCOL'], 'HTTPS') === FALSE ? 'http://' : 'https://'; //协议
        $query_string = empty($_SERVER['QUERY_STRING']) ? '' : $_SERVER['QUERY_STRING'];
        $path_info = $_SERVER['PATH_INFO'] ?? '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        parse_str($query_string, $this->url_param);
        $this->url = $agreement . $host . $path_info . '?';
    }

}
