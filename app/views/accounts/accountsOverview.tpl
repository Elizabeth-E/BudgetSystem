{include file="{$layout}\\header.tpl"}

<main>
	<div class="container">
		<div class="row">
			<div class="col-md-offset-1 ">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 style="color:#03b1ce;">{$username}'s Accounts </h4></span>
					</div>
					<div class="container">
						<table class="table table-striped">
							<thead>
								<th scope="col-md-3">Account name</th>
								<th scope="col-md-3">Account number</th>
								<th scope="col-md-3">Amount</th>
								<th scope="col-md-3">Type</th>
							</thead>
							<tbody>
								{foreach from=$accounts item=info}
								<tr>
									<td>{$info["accountname"]}</td>
									<td>{$info["accountnum"]}</td>
									<td>{$info["amount"]}</td>
									<td>{$info["type"]}</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
						<a href="{$www}/user/edit_profile"><button class="btn btn-default"><i
									class="fa fa-pencil-square-o" aria-hidden="true"></i>add account</button></a>
						<a href="{$www}/accounts/generatePDF" target="_blank"><button class="btn btn-default"><i
									class="fa fa-pencil-square-o" aria-hidden="true"></i>Generate PDF</button></a>
						<button type="button" class="btn btn-default" data-toggle="modal" data-action="import" data-type="csv" data-target="#import">Import
							(CSV)</button>
						<button type="button" class="btn btn-default" data-toggle="modal" data-action="export" data-type="csv" data-target="#export">Export
							(CSV)</button>
						<button type="button" class="btn btn-default" data-toggle="modal" data-action="export" data-type="xls" data-target="#export">Export
							(XLS)</button>
					</div>
				</div>

				{foreach from=$accounts item=info}
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 style="color:#03b1ce;">{$info["accountname"]} </h4></span>
					</div>
					<div class="container">
						<table class="table table-striped">
							<thead>
								<th scope="col-md-3">date</th>
								<th scope="col-md-3">name</th>
								<th scope="col-md-3">description</th>
								<th scope="col-md-3">amount</th>
							</thead>
							<tbody>

								{foreach from=$transactions item=action}
								{if $info["id"] eq $action["accounts_id"]}
								<tr>
									<td>{$action["date"]}</td>
									<td>{$action["name"]}</td>
									<td>{$action["description"]}</td>
									<td>{$action["amount"]}</td>
								</tr>
								{/if}
								{/foreach}
							</tbody>
						</table>


					</div>
				</div>
				{/foreach}

			</div>
		</div>
	</div>

	<!-- START MODAL EXPORT -->
	<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel">Create <span id="export-name"></span> export</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- Acttion will be filled by JS -->
					<form action="" method="post">
						<div class="form-group">
							<!-- Show accounts -->
							<label>Accounts to export:</label>

							{foreach from=$accounts item=info}
							<div class="checkbox">
								<label><input type="checkbox" name="accounts[{$info["accountname"]}]"" value="{$info["id"]}"> {$info["accountname"]}</label>
							</div>
							{/foreach}

							<!-- Show fields -->
							<label>Columns to export:</label>

							<div class="checkbox"><label><input type="checkbox" name="fields[date]" value="true"> Date</label></div>
							<div class="checkbox"><label><input type="checkbox" name="fields[name]" value="true"> Name</label></div>
							<div class="checkbox"><label><input type="checkbox" name="fields[description]" value="true"> Description</label></div>
							<div class="checkbox"><label><input type="checkbox" name="fields[amount]" value="true"> Amount</label></div>							
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- END MODAL EXPORT -->

	<!-- START MODAL IMPORT -->
	<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel">Import <span id="import-name"></span> file</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- Acttion will be filled by JS -->
					<form action="" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<!-- Show accounts -->
							<label>Select an account to import too</label>

							{foreach from=$accounts item=info}
							<div class="checkbox">
								<label><input type="radio" name="accountname" value="{$info["id"]}"> {$info["accountname"]}</label>
							</div>
							{/foreach}

							<!-- Show fields -->
							<div class="form-group">
								<div class="input-group mb-3">
										<div class="custom-file">
											<input type="file" class="custom-file-input" id="inputGroupFile01" name="file">
											<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
										</div>
								</div>
							</div>				
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<!-- END MODAL IMPORT -->
</main>

<script>
let formAction = '{$www}/accounts';
{literal}
// Update modals on button click (title, checkboxes & form action)
$('button[data-target]').click(function(event) {
	// Update modal title
	const type = $(this).data('type');
	const action = $(this).data('action');
	const form = $('#' + action + ' div.modal-body form');

	$('#export-name').text(type);
	$('#import-name').text(type);

	// Auto check checkboxes
	if (action == 'export') {
		const checkboxes = form.find(':checkbox');

		checkboxes.each(function(key, val) {
			$(val).prop('checked', true);
		});
	}

	// Update form action
	$(form).attr('action', formAction + '/' + action + type);
});
{/literal}
</script>

{include file="{$layout}\\footer.tpl"}