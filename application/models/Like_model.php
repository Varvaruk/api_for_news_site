<?php


class Like_model extends MY_Model
{
    const LIKE_TABLE = 'likes';
    const LIKE_TYPE_COMMENT = 'comment';
    const LIKE_TYPE_NEWS = 'news';
    const API_KEY_UID = 'uid';
    const API_KEY_LIKE_ID = 'like_id';

    protected $id;
    protected $uid;
    protected $news_id;
    protected $comment_id;
    protected $time_created;

    function __construct($id = FALSE)
    {
        parent::__construct();
        $this->class_table = self::LIKE_TABLE;
        $this->set_id($id);
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     * @return bool
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this->_save('uid', $uid);
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
    public function getCommentId()
    {
        return $this->comment_id;
    }

    /**
     * @param mixed $comment_id
     * @return bool
     */
    public function setCommentId($comment_id)
    {
        $this->comment_id = $comment_id;
        return $this->_save('comment_id', $comment_id);
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
     * @param $data
     * @return bool|Like_model
     */
    public static function create($data){
        $CI =& get_instance();
        $res = $CI->s->from(self::LIKE_TABLE)->insert($data)->execute();
        if(!$res){
            return FALSE;
        }

        return new self($CI->s->insert_id);
    }

    /**
     * @param null $id
     * @param null $uid
     * @param null $news_id
     * @param null $comment_id
     * @return |null
     */
    public static function find_by_params($id=null, $uid=null, $news_id=null, $comment_id=null){
        $CI =& get_instance();
        $like= null;
        if($comment_id){
             $like = $CI->s->from(self::LIKE_TABLE)->where('comment_id',$comment_id)->where('uid',$uid)->one();
         }
        if($news_id){
            $like = $CI->s->from(self::LIKE_TABLE)->where('news_id',$news_id)->where('uid',$uid)->one();
        }
        if($id){
            $like = $CI->s->from(self::LIKE_TABLE)->where('id',$id)->one();
        }

        return $like;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function deleted_by_id($id){
        $CI =& get_instance();

       return $CI->s->from(self::LIKE_TABLE)->where('id',$id)->delete()->execute();
    }
}