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
                        <a href="{$www}/user/edit_profile"><button class="btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>add account</button></a>
                        <a href="{$www}/accounts/generatePDF" target="_blank"><button class="btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i>Generate PDF</button></a>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#import">Import (CSV)</button>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#export">Export (CSV)</button>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#export">Export (XLS)</button>
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
    <!-- Modal -->
<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Export</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            <div class="modal-body">
                <div class="col-sm-7 ">
                    <form action="export" method="post">
                        {foreach from=$accounts item=info}
                            <div class="custom-control custom-checkbox custom-control-inline" style="border-bottom: 1px solid #000000;"">
                            <input type="checkbox" class="custom-control-input" id="inline1">
                            <label class="custom-control-label" for="inline1">{$info["accountname"]}</label>
                            </div>
                        {/foreach}
                        <div>
                            <input type="checkbox" class="custom-control-input" id="date">
                            <label class="custom-control-label" for="inline2">Date</label>
                            <input type="checkbox" class="custom-control-input" id="name">
                            <label class="custom-control-label" for="inline2">Name</label>
                            <input type="checkbox" class="custom-control-input" id="description">
                            <label class="custom-control-label" for="inline2">Description</label>
                            <input type="checkbox" class="custom-control-input" id="amount">
                            <label class="custom-control-label" for="inline2">Amount</label>
                        </div>
                    </form>
                </div>
            </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Export</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Import</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
       
            <form action="import" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="input-group mb-3">
                    <h5>Select account for importing</h5>
                    {foreach from=$accounts item=info}
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="defaultInline1" name="inlineDefaultRadiosExample">
                            <label class="custom-control-label" for="defaultInline1">{$info["accountname"]}</label>
                        </div>
                        {/foreach}
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputGroupFile01" name="file">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


</main>

{include file="{$layout}\\footer.tpl"}