<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
    } 

    public function index() {
         #Set up the View
        $this->template->content = View::instance('v_users_index');

        #Set header information
        $client_files_head = array('/css/users_profile.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        #Render the view
        echo $this->template;
    }

    public function signup($username_exists = NULL, $email_exists = NULL, $invalid_email = NULL, $password_error = NULL, $empty_field = NULL) {
        if ($this->user)
            Router::redirect('/users/index');

        #Set up the View
        $this->template->content = View::instance('v_users_signup');

        $client_files_head = array('/css/users_signup.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        $this->template->content->username_exists = $username_exists;
        $this->template->content->email_exists = $email_exists;
        $this->template->content->invalid_email = $invalid_email;
        $this->template->content->password_error = $password_error;
        $this->template->content->empty_field = $empty_field;

        #Render the view
        echo $this->template;
    }

    public function p_signup() {
        $input_check = array();

        $username_exists = DB::instance(DB_NAME)->select_field("SELECT username FROM users WHERE username = '".$_POST['username']."'");
        if ($username_exists)
            $username_exists = true;

        $email_exists = DB::instance(DB_NAME)->select_field("SELECT email FROM users WHERE email = '".$_POST['email']."'");
        if ($email_exists)
            $email_exists = true;
        
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            $invalid_email = true;      
        else $invalid_email = false;

        if( $_POST['password'] != $_POST['password2'] || strlen($_POST['password']) < 6)
            $password_error = true;
        else $password_error = false;

        
        $empty_field = false;
        foreach ($_POST as $key => $value) 
            if ( strlen($value) < 1 )
                $empty_field = true;
        

        $_POST['created']   = Time::now();
        $_POST['password']  = sha1(PASSWORD_SALT.$_POST['password']);
        $_POST['token']     = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());
        $_POST['photo_link'] = "default.gif";
        unset($_POST['password2']);

        if (!$username_exists && !$email_exists && !$invalid_email)
        {
            $_POST['last_login']   = Time::now();
            DB::instance(DB_NAME)->insert_row('users', $_POST);
            $token = $_POST['token'];
            setcookie('token', $token, strtotime('+1 week'), '/');
            Router::redirect('/users/index');
        }

        else
            $this->signup($username_exists, $email_exists, $invalid_email, $password_error, $empty_field);
    }

    public function login() {
        if ($this->user)
            Router::redirect('/');
        #Set up View
        $this->template->content = View::instance('v_users_login');

        #Set title
        $this->template->title = "Login";

        #Set header information
        $client_files_head = array('/css/users_login.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        #Display the view
        echo $this->template;

    }

    public function p_login() {
        if ($_POST['submit'] == Register)
            Router::redirect('/users/signup');

        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        $q = "SELECT token FROM users WHERE email='".$_POST['email']."' AND password='".$_POST['password']."'";
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
        if (!$this->user)
            Router::redirect('/users/login');

        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());
        $data = Array('token' =>$new_token);
        DB::instance(DB_NAME)->update('users', $data, 'WHERE user_id ='.$this->user->user_id);
        setcookie('token', '', strtotime('-1 year'), '/');
        Router::redirect('/');
    }

    public function profile($user_name = NULL) {

        #User has to be logged in to access profiles
        if(!$this->user)
        {
            Router::redirect('/users/login');
        }

        #Set header information
        $client_files_head = array('/css/users_profile.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        #Set up View
        $this->template->content = View::instance('v_users_profile');

        #Pass the data to the View
        $this->template->content->user_name = $user_name;

        #Set title
        $this->template->title = "Profile";

        if (!isset($user_name))
        {
            $this->template->content->user_id = $user_id = $this->user->user_id;
            $this->template->content->first_name = $first_name = $this->user->first_name;
            $this->template->content->last_name = $last_name = $this->user->last_name;
            $this->template->content->photo_link = $photo_link = $this->user->photo_link;
        }

        else
        {
            #Getting data from user whose username equals function's argument
            $result = DB::instance(DB_NAME)->select_row("SELECT first_name, last_name, user_id, photo_link FROM users WHERE username = '$user_name'");
            
            #Passing first and last name to view
            $this->template->content->first_name = $first_name = $result['first_name'];
            $this->template->content->last_name = $last_name  = $result['last_name'];
            $this->template->content->user_id = $user_id = $result['user_id'];
            $this->template->content->photo_link = $photo_link = $result['photo_link'];
            
            if ($result == NULL) {
                $this->template->content->no_profile = $no_profile = TRUE;
                Router::redirect('/users/profileNotFound/'.$user_name);
            }
        }

        #Pass the data to the View
        $this->template->content->user_name = $user_name;

        #Get number of followers
        $this->template->content->follows = $follows = DB::instance(DB_NAME)->select_field($q = "SELECT COUNT(user_user_id) FROM users_users WHERE user_id = '".$user_id."'");

        #Get number of people user follows
        $this->template->content->followed = $followed = DB::instance(DB_NAME)->select_field($q = "SELECT COUNT(user_user_id) FROM users_users WHERE user_id_followed = '".$user_id."'");

        #Get number of posts made
        $this->template->content->posted = $posted = DB::instance(DB_NAME)->select_field($q = "SELECT COUNT(post_id) FROM posts WHERE user_id = '".$user_id."'");

        echo $this->template;
    }

    public function profileNotFound($user_name)
    {
        #Set up View
        $this->template->content = View::instance('v_users_noprofile');

        #Set header information
        $client_files_head = array('/css/style.css', '/css/users_profile.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        #Set title
        $this->template->title = "ProfileNotFound";

        #Pass data to view
        $this->template->content->user_name = $user_name;

        echo $this->template;
    }

    public function p_upload_photo()
    {
        if ($_FILES["file"]["error"] == 0)
        {
            
            $image = Upload::upload($_FILES, "/uploads/avatars/", array("jpg", "JPG", "jpeg", "JPEG", "gif", "GIF", "png", "PNG"), $this->user->username);

            if($image == 'Invalid file type.') {
                Router::redirect("/users/profile/"); 
                        }
            else {
                                
                $data = Array("photo_link" => $image);
                DB::instance(DB_NAME)->update("users", $data, "WHERE user_id = ".$this->user->user_id);

                
                $imgObj = new Image($_SERVER["DOCUMENT_ROOT"].'/uploads/avatars/'.$image);
                $imgObj->resize(250,250,"crop");
                $imgObj->save_image($_SERVER["DOCUMENT_ROOT"].'/uploads/avatars/'.$image);
            }
        }
        else
        {
                
                Router::redirect("/users/profile");  
        }

                
                router::redirect('/users/profile'); 
    }

} # end of the class