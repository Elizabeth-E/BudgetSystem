{include file="{$layout}\\header.tpl"}

<main>
    <div class="container">
        <div class="row">
            <div class="col-md-7 ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 style="color:#03b1ce;">{$profile["username"]} </h4></span>
                    </div>
                    <div class="panel-body">

                        <div class="box box-info">

                            <div class="box-body">
                                <div class="col-sm-12">
                                    <div align="center"> <img alt="User Pic" src="https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg"
                                            id="profile-image1" class="img-circle img-responsive">

                                        <input id="profile-image-upload" class="hidden" type="file">
                                        <div style="color:#999;">click here to change profile image</div>
                                        <!--Upload Image Js And Css-->

                                    </div>

                                    <br>

                                    <!-- /input-group -->
                                </div>

                                <div class="clearfix"></div>
                                <hr style="margin:5px 0 5px 20;">


                                <div class="col-sm-6 col-xs-6 tital ">First Name:</div>
                                <div class="col-sm-6 col-xs-6 ">{$profile["firstname"]|escape} </div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                <div class="col-sm-6  col-xs-6 tital ">Last Name:</div>
                                <div class="col-sm-6"> {$profile["lastname"]|escape} </div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                <div class="col-sm-6  col-xs-6 tital ">Email:</div>
                                <div class="col-sm-6"> {$profile["email"]|escape} </div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                <div class="col-sm-6  col-xs-6 tital ">Date Joined:</div>
                                <div class="col-sm-6">{$profile["registration_date"]|date_format:"%d-%m-%Y"} </div>
                                <div class="clearfix"></div>
                                <div class="bot-border"></div>

                                <div class="col-sm-6  col-xs-6 tital ">Date Of Birth:</div>
                                <div class="col-sm-6">{$profile["birthdate"]|date_format:"%d-%m-%Y"}</div>
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
    </div>


    </div>
</main>


{include file="{$layout}\\footer.tpl"}