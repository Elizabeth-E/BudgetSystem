{include file="{$layout}\\header.tpl"}

<div id="page-content-wrapper">
	<main>
		<div class="container">
			<h2 class="section-heading">Account Activation</h2>

			{if $alert == true}
				<!-- ALERT MESSAGE -->
				<div class="alert alert-{$alertType} alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>{$alertType}!</strong> {$alertMessage}
				</div>
				<!-- ALERT MESSAGE -->
			{/if}
		</div>
	</main>
</div>

{include file="{$layout}\\footer.tpl"}