<!--
<div id="signup">

	<h2>Sign up</h2>

	<form action='/users/p_signup' method="POST">
		Username: <input type="text" name="username"><br>
		First Name: <input type="text" name="first_name"><br>
		Last Name: <input type="text" name="last_name"><br>
		Email: <input type="text" name="email"><br>
		password: <input type="password" name="password"><br>
		Re-enter password: <input type="password" name="password2"><br>

		<input type="submit" value="Sign up">
	</form>
-->

</div>

<div id="stylized" class="myform">
	<form id="form" name="form" action='/users/p_signup' method="POST">
		<h1>Sign-up</h1>
		<p>Become a myBlog User</p>

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
		<input type="text" name="password" id="password" />

		<label>Password
		<span class="small">Re-enter your password</span>
		</label>
		<input type="text" name="password" id="password2" />

		<button type="submit">Sign-up</button>
		<div class="spacer"></div>
	</form>
</div>