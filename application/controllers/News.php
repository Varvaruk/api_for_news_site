<?php


class News extends MY_Controller
{
    protected $response_data;

    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->load->model('news_model');
        $this->load->model('comment_model');

        if (ENVIRONMENT === 'production')
        {
            die('Access denied!');
        }
    }

    // костыль для тестов)
    public function index()
    {
        $this->get_last_news();
    }

    public function get_last_news()
    {
        return $this->response_success(['news' => News_model::get_last_news('short_info'),'patch_notes' => []]);
    }

  /*
   * Get All News
   *
   * Method: GET
   * URI:http://example.com/news/get_all_news?type_info=short_info
   * OR
   * URI:http://example.com/news/get_all_news?type_info=full_info
   *
   */
    public function get_all_news()
    {
        $type_info = News_model::API_KEY_SHORT_INFO;
        if(!empty($param = $this->input->get(News_model::API_KEY_TYPE_INFO))){
            $type_info = $param;
        }
        try {

            return $this->response_success(['news' => News_model::get_all($type_info), 'patch_notes' => []]);
        } catch (Exception $e) {

            return $this->response_error($e);
        }
    }

    /*
     *  Create a new News
     *
     *  Method: POST
     *  Headers: Content-Type: application/json
     *  Form values: none
     *  Body:
     *  {
            "header": "News #33",
            "description": "Description",
            "img": "/assets/images/news/cover-news-20180808.png",
            "text": "Text "
        }
     *
     */
    public function create_news(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $param_std = json_decode($stream_clean);
        $param_assoc_array = json_decode(json_encode($param_std), true);
        $param_assoc_array["short_description"] = $param_assoc_array["description"];
        unset($param_assoc_array["description"]);
        $new_news = News_model::create($param_assoc_array);
        try {
            $response = News_model::get_one_news_by_id($new_news->get_id(),'full_info');
        } catch (Exception $e) {
            return $this->response_error($e);
        }
        return $this->response_success(['news' => $response,'patch_notes' => []]);
    }

    /*
   * Get Comments by ID News
   *
   * Method: GET
   * URI:http://example.com/news/get_comments?news_id=4
   *
   */
    public function get_comments()
    {
        if (!empty($param = $this->input->get(News_model::API_KEY_NEWS_ID))) {

            return $this->response_success(['comments' => Comment_model::get_comments_by_news_id($param)]);
        }

        return $this->response_error("Param " . News_model::API_KEY_NEWS_ID . " not found!");
    }
}
