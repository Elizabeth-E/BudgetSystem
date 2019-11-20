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
		<p>Request a password reset for an account based on email-address.</p>

		<form method="post" action="{$POST_URL}">
			<div class="form-group">
				<label for="exampleInputEmail">Email-address</label>
				<input type="email" class="form-control" name="email" id="exampleInputEmail" placeholder="Email-address">
			</div>
			<button type="submit" class="btn btn-default">Request Reset</button>
		</form>
	</div>
</main>

{include file="{$layout}\\footer.tpl"}