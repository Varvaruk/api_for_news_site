<?php


class Comment_model extends MY_Model

{
    const COMMENT_TABLE = 'comment';
    const API_KEY_TEXT = 'text';
    const API_KEY_AUTHOR_NAME = 'authors_name';
    const API_KEY_NEWS_ID = 'news_id';
    const API_KEY_COMMENT_ID = 'comment_id';

    protected $id;
    protected $text;
    protected $authors_name;
    protected $news_id;
    protected $time_created;
    protected $time_updated;
    protected $deleted = false;

    function __construct($id = FALSE)
    {
        parent::__construct();
        $this->class_table = self::COMMENT_TABLE;
        $this->set_id($id);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return bool
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this->_save('text', $text);
    }

    /**
     * @return mixed
     */
    public function getAuthorsName()
    {
        return $this->authors_name;
    }

    /**
     * @param mixed $authors_name
     * @return bool
     */
    public function setAuthorsName($authors_name)
    {
        $this->authors_name = $authors_name;

        return $this->_save('authors_name', $authors_name);
    }

    /**
     * @return mixed
     */
    public function getNewsId()
    {
        return $this->news_id;
    }

    /**
     * @param mixed $news_id
     * @return bool
     */
    public function setNewsId($news_id)
    {
        $this->news_id = $news_id;

        return $this->_save('news_id', $news_id);
    }

    /**
     * @return mixed
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * @param mixed $time_created
     * @return bool
     */
    public function setTimeCreated($time_created)
    {
        $this->time_created = $time_created;

        return $this->_save('time_created', $time_created);
    }

    /**
     * @return mixed
     */
    public function getTimeUpdated()
    {
        return $this->time_updated;
    }

    /**
     * @param mixed $time_updated
     * @return bool
     */

    public function setTimeUpdated($time_updated)
    {
        $this->time_updated = $time_updated;

        return $this->_save('time_updated', $time_updated);
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     * @return bool
     */
    public function setDeleted(bool $deleted)
    {
        $this->deleted = $deleted;

        return $this->_save('deleted', $deleted);
    }

    /**
     * @param $data
     * @return bool|Comment_model
     */
    public static function create($data)
    {

        $CI =& get_instance();
        $res = $CI->s->from(self::COMMENT_TABLE)->insert($data)->execute();
        if (!$res) {

            return FALSE;
        }

        return new self($CI->s->insert_id);
    }

    /**
     * @param $data
     * @return array
     */
    public static function preparation($data)
    {
        $res = [];

        foreach ($data as $item) {
            $_info = new stdClass();
            $_info->id = $item->get_id();
            $_info->text = $item->getText();
            $_info->authors_name = $item->getAuthorsName();
            $_info->news_id = $item->getNewsId();
            $_info->time_created = $item->getTimeCreated();
            $_info->time_updated = $item->getTimeUpdated();
            $_info->deleted = $item->isDeleted();

            $res[] = $_info;
        }

        return $res;
    }

    /**
     * @param $id
     * @return Comment_model|null
     */
    public static function delete($id)
    {
        $CI =& get_instance();
        $_data = $CI->s->from(self::COMMENT_TABLE)->where('id', $id)->one();
        $comment = (new self())->load_data($_data);
        if (!$comment->get_id()) {

            return null;
        }
        $comment->setDeleted(true);
        $comment->setTimeUpdated(date("Y-m-d H:i:s"));

        return $comment;
    }

    /**
     * @param $news_id
     * @return array
     */
    public static function get_comments_by_news_id($news_id)
    {
        $CI =& get_instance();
        $_data = $CI->s->from(self::COMMENT_TABLE)->where('news_id', $news_id)->many();
        $comments_list = [];
        foreach ($_data as $_item) {
            $comments_list[] = (new self())->load_data($_item);
        }

        return self::preparation($comments_list);
    }

    /**
     * @param $comment_id
     * @return Comment_model
     */
    public static function get_one_comment_by_id($comment_id){
        $CI =& get_instance();
        $_data = $CI->s->from(self::COMMENT_TABLE)->where('id',$comment_id)->one();
        $comment = (new self())->load_data($_data);

        return $comment;
    }
}