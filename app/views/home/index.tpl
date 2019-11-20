{include file="{$layout}\\header.tpl"}

<main>
	<div class="container">
		<h2 class="section-heading">Home Index</h2>
		<p>Welcome to the home page!</p>
		
		<input type="submit" class="btn btn-success" value="Learn More">

		<form method="post" action="{$POST_URL}">
			<div class="form-group">
				<label for="exampleInputEmail1">Email address</label>
				<input type="email" class="form-control" name="email" id="InputEmail" placeholder="Email" value="{$rememberMeInput|escape}">
			</div>
			<div class="form-group">
				<label for="exampleInputPassword1">Password</label>
				<input type="password" class="form-control" name="password" id="InputPassword" placeholder="Password">
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="remember_username"> Remember Username
				</label>
			</div>
			<button type="submit" class="btn btn-success">Login</button>
			<a href="{$www}/user/register" class="btn btn-success">Register</a>
		</form>
	</div>
</main>

{include file="{$layout}\\footer.tpl"}