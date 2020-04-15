{include file="{$layout}\\header.tpl"}
<style>
.modify {
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-1  ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 style="color:#03b1ce;">{$profile["username"]} </h4></span>
          
               
                    </div>
                    <div class="panel-body">

                        <div class="box box-info">

                            <div class="box-body">
                                <div class="col-sm-8">
                                    <div align="center"> <img alt="User Pic" src="https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg"
                                            id="profile-image1" class="img-circle img-responsive">

                                        <input id="profile-image-upload" class="hidden" type="file">
                                        <form method="post" enctype="multipart/form-data" name="formUploadFile">      
                                            <label>Select file to upload:</label>
                                            <input type="file" name="files[]" multiple="multiple" />
                                            <input type="submit" value="Upload File" name="btnSubmit"/>
                                        </form>
                                        <div style="color:#999;">click here to change profile image</div>
                                        <!--Upload Image Js And Css-->

                                    </div>

                                    <br>

                                    <!-- /input-group -->
                                </div>

                                <div class="clearfix"></div>
                                <hr style="margin:5px 0 5px 20;">


                                <div class="col-sm-6 col-xs-6 tital ">First Name:</div>
                                <div class="col-sm-6 col-xs-6 modify" contenteditable="false" id="firstname">{$profile["firstname"]|escape} </div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                <div class="col-sm-6  col-xs-6 tital ">Last Name:</div>
                                <div class="col-sm-6 modify" contenteditable="false" id="lastname"> {$profile["lastname"]|escape} </div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                <div class="col-sm-6  col-xs-6 tital ">Email:</div>
                                <div class="col-sm-6 modify" contenteditable="false" id="email123"> {$profile["email"]|escape} </div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                <div class="col-sm-6  col-xs-6 tital ">Date Joined:</div>
                                <div class="col-sm-6">{$profile["registration_date"]|date_format:"%d-%m-%Y"} </div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                <div class="col-sm-6  col-xs-6 tital ">Date Of Birth:</div>
                                <div class="col-sm-6 modify" contenteditable="false">{$profile["birthdate"]|date_format:"%d-%m-%Y"}</div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                 <div class="col-sm-6  col-xs-6 tital ">API Key:</div>
                                <div class="col-sm-6 modify" contenteditable="false">{$profile["validation_token"]}</div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                 
            </div>
           
        </div>
        <a href="{$www}/?controller=user&action=edit_profile">
            <button class="btn btn-default"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Modify your profile</button>
        </a>
        <div class = "paypal">
            <div id="paypal-button" ></div>
         </div>
        
    </div>


    </div>
</main>


<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
var totalAmount = parseFloat('0.73').toFixed(2);
let url = '{$www}/checkout/process_order';

{literal}
$('#paypal-checked').change(function() {
    if ( ! this.checked) {
        alert('not checked');
    }

    let data = {
        firstName: $('#firstname').val(),
        lastName: $('#lastname').val(),
        email: $('#email123').val(),
    };
    
    $.post(url, data, function(response) { 
        console.log(response);
    });

});
  paypal.Button.render({
    // Configure environment
    env: 'sandbox',
    client: {
      sandbox: 'demo_sandbox_client_id',
      production: 'demo_production_client_id'
    },
    // Customize button (optional)
    locale: 'en_US',
    style: {
      size: 'large',
      color: 'black',
      shape: 'rect',
    },

    // Enable Pay Now checkout flow (optional)
    commit: true,

    // Set up a payment
    payment: function(data, actions) {
      return actions.payment.create({
        transactions: [{
          amount: {
            total: totalAmount,
            currency: 'EUR'
          }
        }]
      });
    },
    // Execute the payment
    onAuthorize: function(data, actions) {
      return actions.payment.execute().then(function() {
        // Show a confirmation message to the buyer
        window.alert('Thank you for your purchase!');
      });
    }
  }, '#paypal-button');
{/literal}
</script>

{include file="{$layout}\\footer.tpl"}