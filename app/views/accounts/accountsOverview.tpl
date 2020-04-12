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
                         <a href="{$www}/user/edit_profile">
                    <button class="btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>add account</button>
                </a>
                      <a href="{$www}/accounts/generatePDF">
                    <button class="btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Generate PDF</button>
                </a>
       

	                </div>                  
                </div>

                {foreach from=$accounts item=info}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 style="color:#03b1ce;">{$info["accountname"]} </h4></span>
                    </div>
                    <div class="container">
                         <table  class="table table-striped">
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


    </div>
    
</main>

{include file="{$layout}\\footer.tpl"}