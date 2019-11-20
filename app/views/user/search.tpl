{include file="{$layout}\\header.tpl"}

<div class="container">
	<div class="row">
		<div class="container">
			<h2 class="section-heading">Member search</h2>
			<p>Use the form below to search for other using</p>
			<ul>
				<li>Username</li>
				<li>Email</li>
				<li>Registration date</li>
			</ul>
		</div>

		<form action="{$PUT_URL}" method="post"> 
			<div class="row">
				<div class="col-xs-6 col-md-4">
				<div class="input-group">
					<input type="text" name="query" class="form-control" placeholder="Search" id="txtSearch"/>
					<div class="input-group-btn">
					<button class="btn btn-primary" type="submit">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					</div>
				</div>
				</div>
			</div>
			<div class="row">
				<div class="g-recaptcha" data-sitekey="6Lee8HwUAAAAAAnZOLhLmCoGOepDW2rWXnoa0jse"></div>
			</div>
		</form>

		<hr />
		<section>
		{if $resultsFound gt 0}
			<table class="table">
				<thead>
					<th>ID</th>
					<th>Username</th>
					<th>email</th>
					<th>Role</th>
					<th>Registration date</th>
				</thead>
				<tbody>
					{foreach from=$searchResults item=result}
						<tr>
							<td>{$result["id"]}</td>
							<td>{$result["username"]}</td>
							<td>{$result["email"]}</td>
							<td>{$result["role"]}</td>
							<td>{$result["registration_date"]|date_format:"%d-%m-%Y"}</td>
						</td>
					{/foreach}
				</tbody>
			</table>
		{/if}
		</section>
	</div>
</div>




{include file="{$layout}\\footer.tpl"}