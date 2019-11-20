{include file="{$layout}\\header.tpl"}

<div id="page-content-wrapper">
	<main>
		<div class="container">
			<h2 class="section-heading">login</h2>
		</div>
		{if $alert == true}
			<!-- ALERT MESSAGE -->
			<div class="alert alert-{$alertType} alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>{$alertType}!</strong> {$alertMessage}
			</div>
			<!-- ALERT MESSAGE -->
		{/if}

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
			<button type="submit" class="btn btn-default">Login</button>
		</form>

		<p><br />Don't have an account yet?<br /><a type="button" href="{$www}/user/register">Create Account</a></p>
		<p>Forgot password?<br /><a type="button" href="{$www}/user/reset_password">Request a password reset</a></p>
	</main>
</div>

{include file="{$layout}\\footer.tpl"}