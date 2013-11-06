<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
    } 

    /**
     *  Renders the users index page
     */
    public function index() {
         #Set up the View
        $this->template->content = View::instance('v_users_index');

        #Set header information
        $client_files_head = array('/css/users_profile.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        #Render the view
        echo $this->template;
    }

    /**
     *  Renders signup page. The parameters are used when this function gets called by p_signup, asking to display error messages. 
     *  If any of the parameters are passed as TRUE, the function will print the corresponding error message.
     *
     *  @param $username_exists     username chosen already exists in the database
     *  @param $email_exists        email already exists
     *  @param $invalid_email       the email format was incorrect
     *  @param $password_error      true when password too short or password1 and password2 do not match in the signup form
     *  @param $empty_field         one of the fields was empty
     */
    public function signup($username_exists = NULL, $email_exists = NULL, $invalid_email = NULL, $password_error = NULL, $empty_field = NULL) {
        
        #Make sure this page cannot be accessed when the user is logged in
        if ($this->user)
            Router::redirect('/users/index');

        #Set up the View
        $this->template->content = View::instance('v_users_signup');

        #Set up header files
        $client_files_head = array('/css/users_signup.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        #Pass info to the view.
        $this->template->content->username_exists = $username_exists;
        $this->template->content->email_exists = $email_exists;
        $this->template->content->invalid_email = $invalid_email;
        $this->template->content->password_error = $password_error;
        $this->template->content->empty_field = $empty_field;

        #Render the view
        echo $this->template;
    }

    /*
     *  This function processes the data sent by the form in the signup page.
     */
    public function p_signup() {

        #Sanitize
        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        #Check to see if the username has already been chosen. Usernames must be UNIQUE in the database. If the username is found, set variable to true
        $username_exists = DB::instance(DB_NAME)->select_field("SELECT username FROM users WHERE username = '".$_POST['username']."'");
        if ($username_exists)
            $username_exists = true;

        #Check for email's uniqueness
        $email_exists = DB::instance(DB_NAME)->select_field("SELECT email FROM users WHERE email = '".$_POST['email']."'");
        if ($email_exists)
            $email_exists = true;
        
        #Check for valid email format
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            $invalid_email = true;      
        else $invalid_email = false;

        #check for password match
        if( $_POST['password'] != $_POST['password2'] || strlen($_POST['password']) < 6)
            $password_error = true;
        else $password_error = false;

        #check for empty fields
        $empty_field = false;
        foreach ($_POST as $key => $value) 
            if ( strlen($value) < 1 )
                $empty_field = true;
        

        #Update the post data in order to inject into database
        $_POST['created']   = Time::now();
        $_POST['password']  = sha1(PASSWORD_SALT.$_POST['password']);
        $_POST['token']     = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());
        $_POST['photo_link'] = "default.gif";
        unset($_POST['password2']);     //password2 cannot go into the database


        #Insert into database if there are no errors or impediments
        if (!$username_exists && !$email_exists && !$invalid_email && !$empty_field)
        {
            $_POST['last_login']   = Time::now();
            DB::instance(DB_NAME)->insert_row('users', $_POST);
            $token = $_POST['token'];
            setcookie('token', $token, strtotime('+1 week'), '/');
            Router::redirect('/users/index');
        }

        #There are errors, display the signup page again, returning to the user the causes
        else
            $this->signup($username_exists, $email_exists, $invalid_email, $password_error, $empty_field);
    }

    /**
     *  Renders login page
     */
    public function login() {
        
        #Make sure logged in users cannot access this page
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

    /**
     *  Processes the login form and starts the session.
     */
    public function p_login() {

        #If the user clicked on the register link, redirect...
        if ($_POST['submit'] == Register)
            Router::redirect('/users/signup');

        #Encrypt
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        #Get token from DB
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

    /**
     *  Ends the current session
     */
    public function logout() {

        #Only logged in users can logout
        if (!$this->user)
            Router::redirect('/users/login');

        #Change token data so if connected via multiple windows or computers, it logs out on all of them.. among other things
        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());
        $data = Array('token' =>$new_token);
        DB::instance(DB_NAME)->update('users', $data, 'WHERE user_id ='.$this->user->user_id);
        setcookie('token', '', strtotime('-1 year'), '/');
        Router::redirect('/');
    }

    /**
     *  Renders the profile page
     */
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

        #no parameters passed.. This profile page belongs to the logged in user
        if (!isset($user_name))
        {
            $this->template->content->user_id = $user_id = $this->user->user_id;
            $this->template->content->first_name = $first_name = $this->user->first_name;
            $this->template->content->last_name = $last_name = $this->user->last_name;
            $this->template->content->photo_link = $photo_link = $this->user->photo_link;
        }

        #If $user_name was passed as a parameter, it means we are trying to see a 3rd person's profile. Hence get and pass the appropriate data
        else
        {
            #Getting data from user whose username equals function's argument
            $result = DB::instance(DB_NAME)->select_row("SELECT first_name, last_name, user_id, photo_link FROM users WHERE username = '$user_name'");
            
            #Passing first and last name to view
            $this->template->content->first_name = $first_name = $result['first_name'];
            $this->template->content->last_name = $last_name  = $result['last_name'];
            $this->template->content->user_id = $user_id = $result['user_id'];
            $this->template->content->photo_link = $photo_link = $result['photo_link'];
            
            #Nothing was found in the database. The user does not exist.
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

        #Render
        echo $this->template;
    }

    /**
     *  Render page to be used when a profile was not found
     */
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

    /**
     *  Processes the upload of pictures
     */
    public function p_upload_photo()
    {
        #Ensure there are no errors
        if ($_FILES["file"]["error"] == 0)
        {
            #Use the Upload class to store pointer to the image in a variable, in the conditions set by parameters
            $image = Upload::upload($_FILES, "/uploads/avatars/", array("jpg", "JPG", "jpeg", "JPEG", "gif", "GIF", "png", "PNG"), $this->user->username);

            #If file type is invalid, return
            if($image == 'Invalid file type.') {
                Router::redirect("/users/profile/"); 
                        }
            else {
                #Create array to be updated in the database                
                $data = Array("photo_link" => $image);

                #Update DB
                DB::instance(DB_NAME)->update("users", $data, "WHERE user_id = ".$this->user->user_id);

                #Create new object
                $imgObj = new Image($_SERVER["DOCUMENT_ROOT"].'/uploads/avatars/'.$image);
                #Resizes the image.. (temporary)
                $imgObj->resize(250,250,"crop");
                #Make changes permanent
                $imgObj->save_image($_SERVER["DOCUMENT_ROOT"].'/uploads/avatars/'.$image);
            }
        }
        else {                
                Router::redirect("/users/profile");  
        } 
        router::redirect('/users/profile'); 
    }

} # end of the class