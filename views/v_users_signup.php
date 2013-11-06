</div>

<div id="stylized" class="myform">

	<!-- which ever variable gets passed as TRUE into the template, enables the corresponding "if" condition to
		allow execution... thus, echoing error messages in accord to the situation. -->
	<div id="error"><h3><?php if ($username_exists) echo 'Username already Exists. '; if ($email_exists) echo 'Email already exists. '; if ($invalid_email) echo 'Invalid email address. '; if ($password_error) echo 'Password error. '; if ($empty_field) echo 'An empty field was submitted.' ?>	</h3></div>
	<form id="form" name="form" action='/users/p_signup' method="POST">
		<h1>Sign-up</h1>
		<p>Become a myBlog User <a id="login_link" href="/users/login">Login</a></p>

		<label>Username
		<span class="small">Choose a username</span>
		</label>
		<input type="text" name="username" id="username" />

		<label>First Name
		<span class="small">Enter First Name</span>
		</label>
		<input type="text" name="first_name" id="first_name" />

		<label>Last Name
		<span class="small">Enter Last Name</span>
		</label>
		<input type="text" name="last_name" id="last_name" />

		<label>Email
		<span class="small">Add a valid address</span>
		</label>
		<input type="text" name="email" id="email" />

		<label>Password
		<span class="small">Min. size 6 chars</span>
		</label>
		<input type="password" name="password" id="password" />

		<label>Password
		<span class="small">Re-enter your password</span>
		</label>
		<input type="password" name="password2" id="password2" />

		<button type="submit">Sign-up</button>
		<div class="spacer"></div>
	</form>
</div>