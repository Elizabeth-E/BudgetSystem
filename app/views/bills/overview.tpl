{include file="{$layout}\\header.tpl"}

<main>
  <div class="container">
    <div class="row">
      <div class="col-md-offset-1  ">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 style="color:#03b1ce;">{$username} </h4></span>
          </div>
          <div class="container">
            <h2 class="section-heading">Bills Overview</h2>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#basicExampleModal">add
              bill</button>


            <table class="table table-striped">
              <thead>
                <th scope="col-md-3">name</th>
                <th scope="col-md-3">amount</th>
                <th scope="col-md-3">date</th>
                <th scope="col-md-3">frequency</th>
                <th scope="col-md-3">account</th>
              </thead>
              <tbody>
                {foreach from=$bills item=info}
                <tr>
                  <td>{$info["name"]}</td>
                  <td>{$info["amount"]}</td>
                  <td>{$info["date"]}</td>
                  <td>{$info["frequency"]}</td>
                  <td>{$info["account"]}</td>
                </tr>
                {/foreach}
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add a Bill</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-----------------form to add bill----------->
          <form method="post" action="{$POST_URL}">
            <div class="form-group">
              <label for="bill">Name of bill</label>
              <input type="text" class="form-control" name="name" id="exampleInputEmail1" placeholder="e.g. Mortgage">
            </div>
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="text" class="form-control" name="amount" id="username" placeholder="e.g. 466.58">
            </div>
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" class="form-control" name="date" id="FirstName" placeholder="FirstName">
            </div>
            <div class="form-group">
              <label for="username">Frequency</label>
              <select id="frequency" name="frequency">
                <option value="DAILY">daily</option>
                <option value="WEEKLY">weekly</option>
                <option value="MONTHLY">monthly</option>
                <option value="YEARLY">yearly</option>
              </select>
            </div>
            <div class="form-group">
              <label for="username">Account</label>
              <select id="category" name="accountId">
                {foreach from=$accounts item=info}
                <option value="{$info["accountId"]}">{$info["accountname"]}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group">
              <label for="username">Bill Category</label>
              <select id="category" name="billCatId">
                {foreach from=$categories item=info}
                <option value="{$info["billCatId"]}">{$info["name"]}</option>
                {/foreach}
              </select>
            </div>

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-default">Add</button>
          </form>

          <!----------form end--------->
        </div>
      </div>
    </div>
  </div>

</main>

{include file="{$layout}\\footer.tpl"}