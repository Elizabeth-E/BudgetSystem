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
            <button type="button" class="btn btn-default"  onclick="editbills()">edit bill(s)</button>


            <table class="table table-striped" id="viewbills">
              <thead>
                <th scope="col-md-2">name</th>
                <th scope="col-md-2">amount</th>
                <th scope="col-md-2">date</th>
                <th scope="col-md-2">frequency</th>
                <th scope="col-md-2">account</th>
                <th scope="col-md-2">status</th>
              </thead>
              <tbody>
                {foreach from=$bills item=info}
                <tr>
                  <td>{$info["name"]}</td>
                  <td>{$info["amount"]}</td>
                  <td>{$info["date"]}</td>
                  <td>{$info["frequency"]}</td>
                  <td>{$info["account"]}</td>
                  <td>{$info["status"]}</td>
                </tr>
                {/foreach}
              </tbody>
            </table>
            
            <!-- Editable table -->
            <div class="card" id="editbills">
              <h3 class="card-header text-center font-weight-bold text-uppercase py-4">Edit Bills</h3>
              <div class="card-body">
                <div id="table" class="table-editable">
                  <table class="table table-bordered table-responsive-md table-striped text-center" style="width: inherit;">
                    <thead>
                      <th>name</th>
                      <th>amount</th>
                      <th>date</th>
                      <th>frequency</th>
                      <th>account</th>
                      <th></th>
                    </thead>
                    <tbody>
                      {foreach from=$bills item=info}
                      <tr>
                        <td class="pt-3-half" contenteditable="true">{$info["name"]}</td>
                        <td class="pt-3-half" contenteditable="true">{$info["amount"]}</td>
                        <td class="pt-3-half" contenteditable="true">{$info["date"]}</td>
                        <td class="pt-3-half" contenteditable="true">{$info["frequency"]}
                        <select id="frequency" name="frequency">
                            <option value="DAILY">daily</option>
                            <option value="WEEKLY">weekly</option>
                            <option value="MONTHLY">monthly</option>
                            <option value="YEARLY">yearly</option>
                          </select>
                        </td>
                        <td class="pt-3-half" contenteditable="true">{$info["account"]}</td>
                        <td>
                          <span class="table-remove"><button type="button"
                              class="btn btn-danger btn-rounded btn-sm my-0">Remove</button></span>
                        </td>
                      </tr>
                      
                      {/foreach}

                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- Editable table -->

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
              <label for="tags">Name of bill</label>
              <input type="text" class="form-control typeahead twitter-typeahead" data-provide="typeahead" name="name"
                autocomplete="off" placeholder="e.g. Mortgage">
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
              <label for="frequency">Frequency</label>
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
                <option value="{$info[" accountId"]}">{$info["accountname"]}</option>
                {/foreach}
              </select>
            </div>
            <div class="form-group">
              <label for="username">Bill Category</label>
              <select id="category" name="billCatId">
                {foreach from=$categories item=info}
                <option value="{$info[" billCatId"]}">{$info["name"]}</option>
                {/foreach}
              </select>
            </div>

            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="RefreshPage" onclick="viewbills()">Close</button>
            <button type="submit" class="btn btn-default" id="RefreshPage" onclick="viewbills()">Add</button>
          </form>

          <!----------form end--------->
        </div>
      </div>
    </div>
  </div>

</main>

<script>
  $(document).ready(function () {
    $(".typeahead").typeahead({
      source: {$billNames},
      autoSelect: true
    });

  });

  var a = document.getElementById("viewbills");
  var b = document.getElementById("editbills");

  a.style.display = "block";
  b.style.display = "none";

  function viewbills() {
    if (a.style.display === "none") {
      a.style.display = "block";
      b.style.display = "none";
    }
  }

  function editbills() {
    if (b.style.display === "none") {
      b.style.display = "block";
      a.style.display = "none";
    }
  }

  const $tableID = $('#table');
  const $BTN = $('#export-btn');
  const $EXPORT = $('#export');

  const newTr = `
<tr class="hide">
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half">
    <span class="table-up"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-up" aria-hidden="true"></i></a></span>
    <span class="table-down"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-down" aria-hidden="true"></i></a></span>
  </td>
  <td>
    <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Remove</button></span>
  </td>
</tr>`;

  $('.table-add').on('click', 'i', () => {

    const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');

    if ($tableID.find('tbody tr').length === 0) 
    {

      $('tbody').append(newTr);
    }

    $tableID.find('table').append($clone);
  });

  $tableID.on('click', '.table-remove', function () {

    $(this).parents('tr').detach();
  });

  $tableID.on('click', '.table-up', function () {

    const $row = $(this).parents('tr');

    if ($row.index() === 0) {
      return;
    }

    $row.prev().before($row.get(0));
  });

  $tableID.on('click', '.table-down', function () {

    const $row = $(this).parents('tr');
    $row.next().after($row.get(0));
  });

  // A few jQuery helpers for exporting only
  jQuery.fn.pop = [].pop;
  jQuery.fn.shift = [].shift;

  $BTN.on('click', () => {

    const $rows = $tableID.find('tr:not(:hidden)');
    const headers = [];
    const data = [];

    // Get the headers (add special header logic here)
    $($rows.shift()).find('th:not(:empty)').each(function () {

      headers.push($(this).text().toLowerCase());
    });

    // Turn all existing rows into a loopable array
    $rows.each(function () {
      const $td = $(this).find('td');
      const h = {};

      // Use the headers from earlier to name our hash keys
      headers.forEach((header, i) => {

        h[header] = $td.eq(i).text();
      });

      data.push(h);
    });

    // Output the result
    $EXPORT.text(JSON.stringify(data));
  });
</script>

{include file="{$layout}\\footer.tpl"}