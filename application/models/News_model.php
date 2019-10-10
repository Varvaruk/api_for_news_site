<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 10:10
 */
class News_model extends MY_Model
{
    const NEWS_TABLE = 'news';
    const PAGE_LIMIT = 5;
    const TEXT_LENGTH  = 300;
    const NUMBER_OF_RECENT = 3;
    const TIME_CREATED = 'time_created';
    const API_KEY_SHORT_INFO = 'short_info';
    const API_KEY_FULL_INFO = 'full_info';
    const API_KEY_TYPE_INFO = 'type_info';

    protected $id;
    protected $header;
    protected $short_description;
    protected $text;
    protected $img;
    protected $tags;
    protected $time_created;
    protected $time_updated;

    protected $views;

//    protected $comments;
//    protected $likes;

    function __construct($id = FALSE)
    {
        parent::__construct();
        $this->class_table = self::NEWS_TABLE;
        $this->set_id($id);
    }

    /**
     * @return string
     */
    public function get_header()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function set_header($header)
    {
        $this->header = $header;
        return $this->_save('header', $header);
    }

    /**
     * @return string
     */
    public function get_short_description()
    {
        return $this->short_description;
    }

    /**
     * @param mixed $description
     */
    public function set_short_description($description)
    {
        $this->short_description = $description;
        return $this->_save('short_description', $description);
    }

    /**
     * @return string
     */
    public function get_full_text()
    {
        return $this->text;
    }


    /**
     * @return mixed
     */
    public function get_image()
    {
        return $this->img;
    }

    /**
     * @param mixed $image
     */
    public function set_image($image)
    {
        $this->img = $image;
        return $this->_save('image', $image);
    }

    /**
     * @return string
     */
    public function get_tags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function set_tags($tags)
    {
        $this->tags = $tags;
        return $this->_save('tags', $tags);
    }

    /**
     * @return mixed
     */
    public function get_time_created()
    {
        return $this->time_created;
    }

    /**
     * @param mixed $time_created
     */
    public function set_time_created($time_created)
    {
        $this->time_created = $time_created;
        return $this->_save('time_created', $time_created);
    }

    /**
     * @return int
     */
    public function get_time_updated()
    {
        return strtotime($this->time_updated);
    }

    /**
     * @param mixed $time_updated
     */
    public function set_time_updated($time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->_save('time_updated', $time_updated);
    }

//    /**
//     * @return News_like_model
//     */
//    public function get_likes()
//    {
//        return $this->likes;
//    }
//
//    /**
//     * @return News_comments_model[]
//     */
//    public function get_comments()
//    {
//        return $this->comments;
//    }

    /**
     * @param bool|string $preparation
     * @return array
     * @throws Exception
     */
    public static function get_all($preparation = FALSE)
    {

        $CI =& get_instance();

        $_data = $CI->s->from(self::NEWS_TABLE)->many();

        $news_list = [];
        foreach ($_data as $_item) {
            $news_list[] = (new self())->load_data($_item);
        }

        if ($preparation === FALSE) {
            return $news_list;
        }

        return self::preparation($news_list, $preparation);
    }

    /**
     * @param int $number_of_recent
     * @param bool $preparation
     * @return array
     * @throws Exception
     */
    public static function get_last_news($preparation = FALSE, $number_of_recent = self::NUMBER_OF_RECENT)
    {
        $CI =& get_instance();

        $_data = $CI->s->from(self::NEWS_TABLE)->sortDesc(self::TIME_CREATED)->many();

        $news_list = [];
        foreach ($_data as $_item) {
            $news_list[] = (new self())->load_data($_item);
            if (count($news_list) === $number_of_recent) {
                break;
            }
        }

        if ($preparation === FALSE) {
            return $news_list;
        }

        return self::preparation($news_list, $preparation);
    }


    /**
     * @param $news_id
     * @param bool $preparation
     * @return array|News_model
     * @throws Exception
     */
    public static function get_one_news_by_id($news_id, $preparation = FALSE){
        $CI =& get_instance();

        $_data = $CI->s->from(self::NEWS_TABLE)->where('id',$news_id)->one();
        $news = (new self())->load_data($_data);

        if ($preparation === FALSE) {
            return $news;
        }

        return self::preparation(array($news), $preparation);
    }

    /**
     * @param $data
     * @param $preparation
     * @return array
     * @throws Exception
     */
    public static function preparation($data, $preparation)
    {

        switch ($preparation) {
            case 'short_info':
                return self::_preparation_info($data);
            case 'full_info':
                return self::_preparation_info($data,true);
            default:
                throw new Exception('undefined preparation type');
        }
    }

    /**
     * @param News_model[] $data
     * @param bool $full
     * @return array
     */
    private static function _preparation_info($data, $full = FALSE)
    {
        $res = [];
        foreach ($data as $item) {
            $_info = new stdClass();
            $_info->img = $item->get_image();
            $_info->header = $item->get_header();
            $_info->time_created = $item->get_time_created();
            $_info->text = mb_substr($item->get_full_text(), 0, self::TEXT_LENGTH);
            if ($full) {
                $_info->id = $item->get_id();
                $_info->description = $item->get_short_description();
                $_info->text = $item->get_full_text();
                $_info->tags = $item->get_tags();
                $_info->time_updated = $item->get_time_updated();
            }
            $res[] = $_info;
        }

        return $res;
    }

    /**
     * @param array
     * @return News_model
     */
    public static function create($data){

        $CI =& get_instance();
	    $res = $CI->s->from(self::NEWS_TABLE)->insert($data)->execute();
	    if(!$res){
	        return FALSE;
        }
	    return new self($CI->s->insert_id);
    }

}
