<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newballot extends Controller {
    private $sunlightAPI_key = '882d9fa7e1cf63bfb17fb3a9c6a3df19';
    private $sunlightAPI_url = 'http://services.sunlightlabs.com/api';
    private $congressNumber = 111;

    function Newballot() { parent::Controller(); }

    // **************
    //     helper functions 
    // **************

    private function get_all_post() {
        $retval = array();
        foreach ($_POST as $key => $val) {
            if ($key != 'submit' && $_POST[$key]) { $retval[$key] = $this->input->post($key); }
        }
        return $retval;
    }

    private function setup_page($pagename, array $extraJs = array()) {
        $data = array();

        $styleUrls = $this->Site_model->getDefaultStyleUrls();
        $jsPreLoadUrls = $this->Site_model->getDefaultJsPreLoadUrls();
        $jsPostLoadUrls = $this->Site_model->getDefaultJsPostLoadUrls();
        $jsPostLoadUrls = array_merge($jsPostLoadUrls, $extraJs);

        // additional page-specific style
        $styleUrls = $this->Site_model->getDefaultStyleUrls();
        array_push($styleUrls, "/style/newballot_$pagename.css");

        $data['stylesheets'] = $this->Site_model->getStylesheets($styleUrls);
        $data['pre_load_js'] = $this->Site_model->getJavascript($jsPreLoadUrls);
        $data['post_load_js'] = $this->Site_model->getJavascript($jsPostLoadUrls);

        $data['google_analytics_tracking'] = $this->Site_model->getGoogleAnalyticsTracking();

        if ($pagename == 'index') { $data['currentUrl'] = '/'; }
        else { $data['currentUrl'] = "/$pagename"; }
        
        $data['logged_in'] = $this->erkanaauth->try_session_login();
        $data['userData'] = $this->Site_model->get_user($this->erkanaauth->getField('id'), null);
        $data['footer'] = $this->load->view('footer_view', $data, TRUE);
        $data['header'] = $this->load->view('header_view', $data, TRUE);

        $data['doctype'] = $this->Site_model->getDoctype();
        $data['favicon'] = $this->Site_model->getFavicon();
        $data['page_title'] = $this->Site_model->getSiteTitle();

        return $data;
    }

    // **************
    //     pages
    // **************
    function about() {
        $data = $this->setup_page('about');
        $this->load->view('newballot_about_view.php', $data);
    }



    function bill($article_no = -1)
    {
        $article_no = str_replace('_', '.', $article_no); // PHP changes . to _ for some reason, remember.
        $billNumber = substr($article_no, strrpos($article_no, '.')+1 );
        $billType = str_replace('.', '', substr($article_no, 0, strrpos($article_no, '.') ));

        $data['yui_path'] = $this->Site_model->getYUIPath();
        $data = $this->setup_page('bill', 
                            array(
                                $data['yui_path'] .'connection/connection-min.js',
                                '/js/newballot.js',
                                '/js/newballot_vote.js'
                                ));


        $data['bill'] = $this->Site_model->get_bill($this->congressNumber, $billType, $billNumber);
        if (!$data['bill']) {
           return $this->index(); 
        }

        $data['bill']['comments'] = $this->Site_model->get_legislation_comments($data['bill']['id']);

        $this->load->view('newballot_bill_view.php', $data);
    }

    function blog() {
        $data = $this->setup_page('blog');
        $this->load->view('newballot_blog.php', $data);
    }


    function feedback()
    {
        $data['yui_path'] = $this->Site_model->getYUIPath();
        $data = $this->setup_page('feedback',
                           array(
                                $data['yui_path'] ."connection/connection-min.js",
                                '/js/newballot.js',
                                '/js/newballot_feedback.js'
                                ));

        $this->load->helper(array('form', 'url'));
        $this->form_validation->set_rules('feedbacktxt', 'Feedback', 'trim|required|min_length[3]|max_length[1023]|xss_clean');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('newballot_feedback_view', $data);
            return;
        }
        
        $this->Site_model->save_feedback($this->input->post('feedbacktxt'));
        $this->load->view('newballot_feedback_success_view.php', $data);
    }


    function index()
    {
        $data['yui_path'] = $this->Site_model->getYUIPath();
        $data = $this->setup_page('index',
                            array(
                                $data['yui_path'] ."connection/connection-min.js",
                                    '/js/newballot.js',
                                    '/js/newballot_vote.js'
                                ));

        $data['bills'] = $this->Site_model->get_bills_in_media($this->congressNumber, true);
        $this->load->view('newballot_index_view.php', $data);
    }



    function login()
    {
        $data = $this->setup_page('login');
        
        $this->load->helper('url');
        $uri_string = uri_string();
        $referrer = str_replace('/login', '', $uri_string);

        $this->load->helper(array('form', 'url'));
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[32]|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('newballot_login_view', $data);
            return;
        }
            
        if ($this->_check_login($this->input->post('username')))
        {
            $this->Site_model->login();
            redirect('/'. $referrer);
        }
        else
        {
            $this->load->view('newballot_login_view', $data);
        }
    }



    function logout()
    {
        $this->erkanaauth->logout();
        redirect('');
    }
    function _check_login($username)
    {
        $this->load->helper('security');

        $password = dohash($this->input->post('password'));
        if ($this->erkanaauth->try_login(array('username' => $username, 'password' => $password)))
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('_check_login', 'Incorrect login info.');
            return FALSE;
        }
    }

    function my()
    {
        $data['yui_path'] = $this->Site_model->getYUIPath();
        $data = $this->setup_page('my',
                    array(
                        "{$data['yui_path']}element/element-min.js",
                        "{$data['yui_path']}yahoo/yahoo-min.js",
                        "{$data['yui_path']}connection/connection-min.js",
                        "{$data['yui_path']}dom/dom-min.js",
                        '/js/newballot.js'
                    ));

        // handle form submission
        $this->form_validation->set_rules('firstname', 'First name', 'trim|min_length[3]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('lastname', 'Last name', 'trim|min_length[3]|max_length[100]|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email address', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[3]|max_length[127]|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|min_length[3]|max_length[64]|xss_clean');
        $this->form_validation->set_rules('state', 'State', 'trim|required|exact_length[2]|alpha|xss_clean');
        $this->form_validation->set_rules('zip', 'Zip Code', 'trim|required|exact_length[5]|integer|xss_clean');
        
        if (!$this->form_validation->run()) {
            $data['userData'] = $this->Site_model->get_user($this->erkanaauth->getField('id'), null);
            if (!$data['userData']) {
                redirect('/login/my');
            }
            $data['congressmembers'] = $this->Site_model->get_congressmembers(array(
                                                                            'state' => $data['userData']->state,
                                                                            'district' => $data['userData']->district
                                                                             ));
            
            $this->load->view('newballot_my_view', $data);
            return;
        }

        $userdata = $this->get_all_post(); 
        $this->Site_model->update_user($userdata, $this->erkanaauth->getField('id') );

        $data['userData'] = $this->Site_model->get_user($this->erkanaauth->getField('id'), null);
        $data['congressmembers'] = $this->Site_model->get_congressmembers(array(
                                                                      'state' => $data['userData']->state,
                                                                      'district' => $data['userData']->district
                                                                      ));
        $this->load->view('newballot_my_view.php', $data);
    }
    
    function privacy()
    {
        $data = $this->setup_page('privacy');
        $this->load->view('newballot_privacy_view.php', $data);
    }

    function register()
    {
        $data['yui_path'] = $this->Site_model->getYUIPath();
        $data = $this->setup_page('register',
                array(
                    "{$data['yui_path']}element/element-min.js",
                    "{$data['yui_path']}yahoo/yahoo-min.js",
                    "{$data['yui_path']}connection/connection-min.js",
                    "{$data['yui_path']}dom/dom-min.js",
                    '/js/newballot.js',
                    '/js/newballot_register.js'
                )
            );

        $this->load->helper('security');
        $this->load->view('newballot_register_view.php', $data);
    }


    function search($terms = '')
    {
        $data['yui_path'] = $this->Site_model->getYUIPath();
        $data = $this->setup_page('search',
                             array( $data['yui_path'] ."connection/connection-min.js",
                                    '/js/newballot.js',
                                    '/js/newballot_vote.js' ));

        if ($this->input->post('q', TRUE)) {
            $terms = $this->input->post('q', TRUE);
        }

        if ($this->input->post('page', TRUE)) {
            $page = $this->input->post('page', TRUE);
        } else {
            $page = 1;
        }


        if (!$terms) {
            $this->load->view('newballot_search_view.php', $data);
            return;
        }

        $data['searchresults'] = $this->Site_model->search_legislation($terms, $this->congressNumber, TRUE, $page);
        $this->load->view('newballot_searchresults_view.php', $data);
    }




    // ---- xhr responses ----
    function check_email()
    {
        $data['result'] = ($this->Site_model->checkEmail($this->input->post('email', TRUE))? 1 : 0);
        $this->load->view('xhr_response_view', $data);
    }

    function check_username()
    {
        $data['result'] = $this->Site_model->checkUsername($this->input->post('username', TRUE));
        $this->load->view('xhr_response_view', $data);
    }



    function create_user()
    {
        $username = $this->input->post('username', TRUE);
        $email = $this->input->post('email', TRUE);

        if ($this->Site_model->checkUsername($username))
        {
            $this->load->view('xhr_response_view', array('result'=>'1'));
            return;
        }

        if ($this->Site_model->checkEmail($email))
        {
            $this->load->view('xhr_response_view', array('result'=>'2'));
            return;
        }

        $this->load->helper('security');
        $this->Site_model->update_user(array(
                                            'username' => $username,
                                            'password' => dohash($this->input->post('password')),
                                            'email'    => $email
                                      ));
        
        if ($this->_check_login($username)) {
            $this->Site_model->login();
        } else {
            // problem logging in despite registering fine ???
            $this->load->view('xhr_response_view', array('result'=>'3'));
            return;
        }

        $this->load->view('xhr_response_view', array('result'=>'0'));

    }

    function get_district() {
        $userId = $this->erkanaauth->getField('id');

        $q = "SELECT district FROM users WHERE id= $userId";
        $query = $this->db->query($q);
        $result = $query->result_array();
        $district = (isset($result['district'])? $result['district'] : 'no district');
        
        $this->load->view('xhr_response_view', array('result' => $district));
    }

    function get_legislators() {
        $state = $this->input->post('state', TRUE);
        $district = $this->input->post('district', TRUE);

        $this->load->database();
        $q = "SELECT * FROM legislators WHERE state = '$state' AND (is_senator = TRUE OR district = $district)";
        $query = $this->db->query($q);

        $legislators = $query->result_array();

        $this->load->view('xhr_response_view', array('result' => json_encode($legislators)));
    }


    function vote()
    {
        $logged_in = $this->erkanaauth->try_session_login();

        $billId = $this->input->post('bill');
        $vote = $this->input->post('vote');

        if (!$logged_in) {
            $this->load->view('xhr_response_view', array('result'=>'1')); // error code 1 - user not logged in
            return false;
        }
        $userId = $this->erkanaauth->getField('id');

        if ($vote != 'true' && $vote != 'false') {
            $this->load->view('xhr_response_view', array('result'=>'2')); // error code 2 - bad parameter used for vote
            return false;
        }
        $vote = ($vote == 'true'? TRUE : FALSE);

        // todo: if billno is not in the db return an error

        
        if (!$this->Site_model->vote($userId, $billId, $vote)) {
            // if this failed to affect any rows, an error occurred
            $this->load->view('xhr_response_view', array('result'=>'3')); // error code 3 - sql query failed
            return false;
        }

        $this->load->view('xhr_response_view', array('result'=>'0')); // success code 0
        return true;
    }

}
