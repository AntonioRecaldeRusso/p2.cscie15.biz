<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
    } 

    public function index() {
         #Set up the View
        $this->template->content = View::instance('v_users_index');
        #Render the view
        echo $this->template;
    }

    public function signup() {
        #Set up the View
        $this->template->content = View::instance('v_users_signup');

        $client_files_head = array('/css/signup.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        #Render the view
        echo $this->template;
    }

    public function p_signup() {

        $_POST['created']   = Time::now();
        $_POST['password']  = sha1(PASSWORD_SALT.$_POST['password']);
        $_POST['token']     = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());
        unset($_POST['password2']);

        DB::instance(DB_NAME)->insert_row('users', $_POST);

        $token = $_POST['token'];
        setcookie('token', $token, strtotime('+1 week'), '/');
        Router::redirect('./index');
    }

    public function login() {
        #Set up View
        $this->template->content = View::instance('v_users_login');

        #Set title
        $this->template->title = "Login";

        #Display the view
        echo $this->template;

    }

    public function p_login() {
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        $q = "SELECT token FROM users WHERE email='".mysql_real_escape_string($_POST['email'])."' AND password='".$_POST['password']."'";
        $token = DB::instance(DB_NAME)->select_field($q);
        
        #Success
        if($token)
        {
            setcookie('token', $token, strtotime('+1 week'), '/');
            Router::redirect('./index');
        }
        else
        {
            echo "Login Failed!";
        }
    }


    public function logout() {
        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());
        $data = Array('token' =>$new_token);
        DB::instance(DB_NAME)->update('users', $data, 'WHERE user_id ='.$this->user->user_id);
        setcookie('token', '', strtotime('-1 year'), '/');
        Router::redirect('/');
    }

    public function profile($user_name = NULL) {

        if(!$this->user)
        {
            Router::redirect('/users/login');
        }

        else
        {
        #Set up View
        $this->template->content = View::instance('v_users_profile');

        #Pass the data to the View
        $this->template->content->user_name = $user_name;

        #Set title
        $this->template->title = "Profile";

        $client_files_head = array('/css/style.css', '/css/users_profile.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        $username = $this->user->username;
        $q = "SELECT user_id FROM users WHERE username = '$username'";
        $id = DB::instance(DB_NAME)->select_field($q);
        $q = "SELECT COUNT(*) FROM posts WHERE user_id = '$id'";
        $num_rows = DB::instance(DB_NAME)->query($q);
        $result = mysql_fetch_array($num_rows);
        print $result['COUNT(*)'];
       /* $q = "SELECT * FROM users WHERE username = $user_name"; 

        //Execute query against DB
        $user = DB::instance(DB_NAME)->select_rows($q);*/

        // $this->template->content->user = $user;

        #Display the view
        // echo $this->template;      
        }
    }

} # end of the class