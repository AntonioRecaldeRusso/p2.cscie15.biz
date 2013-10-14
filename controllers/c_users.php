<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
    } 

    public function index() {
        echo "This is the index page";
    }

    public function signup() {
        echo "This is the signup page";
    }

    public function login() {
        echo "This is the login page";
    }

    public function logout() {
        echo "This is the logout page";
    }

    public function profile($user_name = 'JeanAlesi') {
        #Set up View
        $this->template->content = View::instance('v_users_profile');

        #Pass the data to the View
        $this->template->content->user_name = $user_name;

        #Set title
        $this->template->title = "Profile";

        $client_files_head = array('/css/profile.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        $client_files_body = array('/js/profile.js');
        $this->template->client_files_body = Utils::load_client_files($client_files_body);


        #Display the view
        echo $this->template;

/*
        $view = view::instance('v_users_profile');
        echo $view;
*/

    }

} # end of the class