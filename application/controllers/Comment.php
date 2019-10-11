<?php


class Comment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->load->model('comment_model');
        $this->load->model('news_model');

        if (ENVIRONMENT === 'production') {
            die('Access denied!');
        }
    }

    /*
        *  Create a new Comment
        *
        *  Method: POST
        *  Headers: Content-Type: application/json
        *  Form values: none
        *  Body:
        *  {
	             "text": "Test",
	             "authors_name" : "AUTHOR",
	             "news_id":"5"

            }
        *
        */
    public function create_comment()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $param_std = json_decode($stream_clean);
        $param_assoc_array = json_decode(json_encode($param_std), true);

        if (!empty(Comment_model::API_KEY_NEWS_ID)) {
            $news = News_model::get_one_news_by_id($param_assoc_array[Comment_model::API_KEY_NEWS_ID]);
            if (!($news->get_id())) {
                return $this->response_error("News not found!");
            }
        }

        $comment = Comment_model::create($param_assoc_array);
        $response = Comment_model::preparation(array($comment));

        return $this->response_success(['comments' => $response]);
    }

    /*
     *  Delete Comment
     *  Method: POST
     *  Headers: Content-Type: application/json
     *  Body:
     *  form-data
     *  key = comment_id
     */
    public function deleted_comment()
    {
        if ($comment_id = $this->input->post(Comment_model::API_KEY_COMMENT_ID)) {
            $deleted_comment = Comment_model::delete($comment_id);
            if ($deleted_comment) {
                return $this->response_success(['comments' => Comment_model::preparation(array($deleted_comment))]);
            }
            return $this->response_error("Comment not found!");
        }
        return $this->response_error("Param " . Comment_model::API_KEY_COMMENT_ID . " not found!");
    }
}