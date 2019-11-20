{include file="{$layout}\\header.tpl"}

<main>
    <div class="container">
        <div class="row">
            <div class="col-md-7 ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 style="color:#03b1ce;">{$username} </h4></span>
                    </div>
                    	<div class="container">
                            <h2 class="section-heading">Add Account</h2>

                            <form method="post" action="{$POST_URL}">
                                <div class="form-group">
                                    <label for="accountname">Account Name</label>
                                    <input type="text" class="form-control" name="accountname" id="accountname" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="0">
                                </div>
                                <div class="form-group">
                                            <label for="type">Select list:</label>
                                            <select class="form-control" name="accounttype" id="type">
                                                <option>DEBIT</option>
                                                <option>CREDITCARD</option>
                                                <option>SAVINGS</option>
                                            </select>
                                </div>
                                <input type="submit" class="btn btn-default" value="Create">
                            </form>
	                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
    
</main>

{include file="{$layout}\\footer.tpl"}