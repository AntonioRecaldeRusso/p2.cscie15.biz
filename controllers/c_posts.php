<?php

class posts_controller extends base_controller
{
	 public function __construct() {
        parent::__construct();
    } 

    /**
     *	This function renders the "Add Posts" page, and displays the last post inputted.
     *
     *	@param $last_post	The last post inputted into the database
     */
	public function add($last_post = NULL)
	{
		#Ensure user is logged in to continue
		if (!$this->user)
			Router::redirect('/users/login');

		#Set up the View
		$this->template->content = View::instance("v_posts_add");

		#Add header info
		$client_files_head = array('/css/style.css', '/css/posts_add.css');
        $this->template->client_files_head = Utils::load_client_files($client_files_head);
		
		#If there was a $last_post parameter, pass info about the last post to the view.. 
		if ($last_post == 'last_post')
		{

			$this->template->content->last_input = $last_input = DB::instance(DB_NAME)->select_field($q = "SELECT content FROM posts WHERE user_id = '".$this->user->user_id."' ORDER BY post_id DESC LIMIT 1");
		}

		#Render
		echo $this->template;
	}

	/**
	 *	Processes from info and adds post into database
	 */
	public function p_add() {
		#Ensure user is logged in
		if (!$this->user)
			Router::redirect('/users/login');

		#If the content inside the text area form is empty, do not do anything.. just return to the add() page.
		if ( strlen($_POST['content']) < 1 )
			Router::redirect('/posts/add');

        # Associate this post with this user
        $_POST['user_id']  = $this->user->user_id;

        # Unix timestamp of when this post was created / modified
        $_POST['created']  = Time::now();
        $_POST['modified'] = Time::now();

        # Insert
        # Note we didn't have to sanitize any of the $_POST data because we're using the insert method which does it for us
        DB::instance(DB_NAME)->insert('posts', $_POST);

        #Redirect to origin
       Router::redirect('/posts/add/last_post');        
    }


    /**
     *	Renders /posts/index which displays a list of all users.
     */
	public function index() {
	
	#Ensure login
	if (!$this->user)
		Router::redirect('/users/login');

    # Set up the View
    $this->template->content = View::instance('v_posts_index');
    $this->template->title   = "All Posts";

    #Setting header info
    $client_files_head = array('/css/posts_posts.css');
    $this->template->client_files_head = Utils::load_client_files($client_files_head);
		

    # Query
    $q = 'SELECT 
            posts.content,
            posts.created,
            posts.user_id AS post_user_id,
            users_users.user_id AS follower_id,
            users.first_name,
            users.last_name
        FROM posts
        INNER JOIN users_users 
            ON posts.user_id = users_users.user_id_followed
        INNER JOIN users 
            ON posts.user_id = users.user_id
        WHERE users_users.user_id = '.$this->user->user_id;

    # Run the query, store the results in the variable $posts
    $posts = DB::instance(DB_NAME)->select_rows($q);

    # Pass data to the View
    $this->template->content->posts = $posts;

    # Render the View
    echo $this->template;

}

/**
 *	Renders page displaying all posts made by logged in user
 */
public function myPosts()
{
	#Ensure login to access this page
	if (!$this->user)
		Router::redirect('/users/login');

	# Set up the View
    $this->template->content = View::instance('v_posts_myposts');
    $this->template->title   = "My Posts";

    #Setting header info
    $client_files_head = array('/css/posts_posts.css');
    $this->template->client_files_head = Utils::load_client_files($client_files_head);
	
    # Build the query
    $q = "SELECT 
            posts .* , 
            users.first_name, 
            users.last_name
        FROM posts
        INNER JOIN users 
            ON posts.user_id = users.user_id
        WHERE posts.user_id = '".$this->user->user_id."'";

    # Run the query
    $posts = DB::instance(DB_NAME)->select_rows($q);

    # Pass data to the View
    $this->template->content->posts = $posts;

    # Render the View
    echo $this->template;
}

	/**
	 *	Renders page displaying
	 */
	public function users() 
	{
		#Ensure login
		if (!$this->user)
			Router::redirect('/users/login');

	    # Set up the View
	    $this->template->content = View::instance("v_posts_users");
	    $this->template->title   = "Users";

	    #Setting header info
	    $client_files_head = array('/css/posts_users.css');
	    $this->template->client_files_head = Utils::load_client_files($client_files_head);
	

	    # Build the query to get all the users
	    $q = "SELECT *
	        FROM users";

	    # Execute the query to get all the users. 
	    # Store the result array in the variable $users
	    $users = DB::instance(DB_NAME)->select_rows($q);

	    # Build the query to figure out what connections does this user already have? 
	    # I.e. who are they following
	    $q = "SELECT * 
	        FROM users_users
	        WHERE user_id = ".$this->user->user_id;

	    # Execute this query with the select_array method
	    # select_array will return our results in an array and use the "users_id_followed" field as the index.
	    # This will come in handy when we get to the view
	    # Store our results (an array) in the variable $connections
	    $connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');

	    # Pass data (users and connections) to the view
	    $this->template->content->users       = $users;
	    $this->template->content->connections = $connections;

	    #Render
	    echo $this->template;
	}

	/**
	 *	Allows the user to track other users's posts
	 *
	 *	@param $users_id_followed  	The user id of the user to be followed
	 */
	public function follow($user_id_followed) 
	{
		#Ensure user is logged in. Not really necessary.. But, playing on the safe side.
		if (!$this->user)
			Router::redirect('/users/login');

	    # Prepare the data array to be inserted
	    $data = Array(
	        "created" => Time::now(),
	        "user_id" => $this->user->user_id,
	        "user_id_followed" => $user_id_followed
	        );

	    # Do the insert
	    DB::instance(DB_NAME)->insert('users_users', $data);

	    # Send them back
	    Router::redirect("/posts/users");

	}

	/**
	 * 	Unfollows user
	 *
	 *	@param User to be unfollowed
	 */
	public function unfollow($user_id_followed) 
	{
		#Ensure only logged in users can perform this action. This is not really necessary as they cannot even access the page.. Double-safety.
		if (!$this->user)
			Router::redirect('/users/login');

	    # Delete this connection
	    $where_condition = 'WHERE user_id = '.$this->user->user_id.' AND user_id_followed = '.$user_id_followed;
	    DB::instance(DB_NAME)->delete('users_users', $where_condition);

	    # Send them back
	    Router::redirect("/posts/users");
	}

	public function edit($post_id = NULL)
	{
		# Setup view
        $this->template->content = View::instance('v_posts_edit');
        $this->template->title   = $this->user->first_name."Edit Post";

        #Setting header info
	    $client_files_head = array('/css/posts_add.css');
	    $this->template->client_files_head = Utils::load_client_files($client_files_head);
	
	    #Get the post's id from the database
        $post = DB::instance(DB_NAME)->select_field("SELECT posts.content FROM posts WHERE post_id = '".$post_id."'");

        #Pass the info to the view page
        $this->template->content->post = $post;
        $this->template->content->post_id = $post_id;

        #Render
        echo $this->template;
	}

	/**
	 * 	Processes editing already existing posts
	 *
	 *	@param $post_id 	The id of the post to be edited
	 */
	public function p_edit($post_id = NULL)
	{
		#Store the id passed by the <form> into the object's equivalent field
		$_POST['user_id'] = $this->user->user_id;

		#Set modified timestamp value
		$_POST['modified'] = Time::now();

		#Update the database with the new data
		DB::instance(DB_NAME)->update_row('posts', $_POST, "WHERE post_id =".$post_id);

		#After it's all done, return to displaying all the user's posts
		Router::redirect('/posts/myposts');
	}
}
?>