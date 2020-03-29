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
                         <table  class="table table-striped">
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
       

	                </div>                  
                </div>
                <a href="{$www}/?controller=user&action=edit_profile">
                    <button class="btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>add account</button>
                </a>
            </div>
        </div>
    </div>


    </div>
    
</main>

{include file="{$layout}\\footer.tpl"}