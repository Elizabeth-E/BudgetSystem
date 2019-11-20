{include file="{$layout}\\header.tpl"}

<main>
	<div class="container">

		{if $alert == true}
		<!-- ALERT MESSAGE -->
		<div class="alert alert-{$alertType} alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<strong>{$alertType}!</strong> {$alertMessage}
		</div>
		<!-- ALERT MESSAGE -->
		{/if}

		<h2 class="section-heading">register</h2>

		<form method="post" action="{$POST_URL}">
			<div class="form-group">
				<label for="exampleInputEmail1">Email address</label>
				<input type="email" class="form-control" name="email" id="exampleInputEmail1" placeholder="Email">
			</div>
			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" class="form-control" name="username" id="username" placeholder="Username">
			</div>
			<div class="form-group">
				<label for="username">FirstName</label>
				<input type="text" class="form-control" name="FirstName" id="FirstName" placeholder="FirstName">
			</div>
			<div class="form-group">
				<label for="username">LastName</label>
				<input type="text" class="form-control" name="LastName" id="lastname" placeholder="LastName">
			</div>
			<div class="form-group">
				<label for="username">Birthday</label>
				<input type="date" class="form-control" name="birthday" id="birthday">
			</div>
			<div class="form-group">
				<label for="exampleInputPassword1">Password</label>
				<input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
			</div>
			<div class="form-group">
				<label for="exampleInputPassword2">Password Confirmation</label>
				<input type="password" class="form-control" name="password_confirmation" id="exampleInputPassword2" placeholder="Password Confirmation">
			</div>


			<div class="g-recaptcha" data-sitekey="6Lee8HwUAAAAAAnZOLhLmCoGOepDW2rWXnoa0jse"></div>

			<button type="submit" class="btn btn-default">Register</button>
		</form>
	</div>
</main>

{include file="{$layout}\\footer.tpl"}