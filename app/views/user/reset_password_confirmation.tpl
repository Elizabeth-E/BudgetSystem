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

		<h2 class="section-heading">Reset Password</h2>

		{if $tokenIsValid}
		<p>Reset your existing password.</p>
		<form method="post" action="{$POST_URL}">
			<div class="form-group">
				<label for="exampleInputPassword1">New Password</label>
				<input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
			</div>
			<div class="form-group">
				<label for="exampleInputPassword2">Confirm New Password</label>
				<input type="password" class="form-control" name="password_confirmation" id="exampleInputPassword2" placeholder="Password Confirmation">
			</div>
			<button type="submit" class="btn btn-default">Reset Password</button>
		</form>
		{/if}
	</div>
</main>

{include file="{$layout}\\footer.tpl"}