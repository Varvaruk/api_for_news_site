<?php


class Like extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->load->model('comment_model');
        $this->load->model('news_model');
        $this->load->model('like_model');

        if (ENVIRONMENT === 'production') {
            die('Access denied!');
        }
    }

    /*
   *  Like Comment
   *
   *  Method: POST
   *  Headers: Content-Type: application/json
   *  Body:
   *  form-data
   *  key = comment_id or news_id
   */
    public function like()
    {
        $uid = $_SERVER['REMOTE_ADDR'];
        if ($comment_id = $this->input->post(Comment_model::API_KEY_COMMENT_ID)) {
            $like = Like_model::find_by_params(null, $uid, null, $comment_id);
            $comment = Comment_model::get_one_comment_by_id($comment_id);
            if (!$comment->get_id()) {

                return $this->response_error("Comment id=" . $comment_id . " not found!");
            }
            if (!$like) {
                $data[Comment_model::API_KEY_COMMENT_ID] = $comment_id;
                $data[Like_model::API_KEY_UID] = $uid;
                $like = Like_model::create($data);

                return $this->response_success();
            }

            return $this->response_error("Like already exists!");
        }

        if ($news_id = $this->input->post(News_model::API_KEY_NEWS_ID)) {
            $like = Like_model::find_by_params(null, $uid, $news_id, null);
            try {
                $news = News_model::get_one_news_by_id($news_id);
            } catch (Exception $e) {

                return $this->response_error($e);
            }
            if (!$news->get_id()) {

                return $this->response_error("News id=" . $news_id . " not found!");
            }
            if (!$like) {
                $data[News_model::API_KEY_NEWS_ID] = $news_id;
                $data[Like_model::API_KEY_UID] = $uid;
                $like = Like_model::create($data);

                return $this->response_success();
            }

            return $this->response_error("Like already exists!");
        }
    }


    /*
       *  Unlike Comment
       *  Method: POST
       *  Headers: Content-Type: application/json
       *  Body:
       *  form-data
       *  key = like_id
       */
    public function unlike()
    {
        if ($like_id = $this->input->post(Like_model::API_KEY_LIKE_ID)) {
            $uid = $_SERVER['REMOTE_ADDR'];
            $like = Like_model::find_by_params($like_id, null, null, null);

            if (!$like) {

                return $this->response_error("Like id = " . $like_id . " not found!");
            }
            try {
                $deleted = Like_model::deleted_by_id($like_id);

            } catch (Exception $e) {

                return $this->response_error($e);
            }

            return $this->response_success();
        }
    }
}