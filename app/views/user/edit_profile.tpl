{include file="{$layout}\\header.tpl"}

<main>
    <div class="container">
        <h2 class="section-heading">user profile</h2>
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

                                    <form method="post" action="{$POST_URL}">
                                        <div class="col-sm-12 col-xs-12 tital ">First Name:</div>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="firstName" id="FirstName"
                                                value="{$profile["firstname"]|escape}">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="bot-border"></div>

                                        <div class="col-sm-12 col-xs-12 tital ">Last Name:</div>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" name="lastName" id="MiddleName"
                                                value="{$profile["lastname"]|escape}">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="bot-border"></div>

                                        <div class="col-sm-12 col-xs-12 tital ">Email:</div>
                                        <div class="col-sm-12">
                                            <input type="email" class="form-control" name="email" id="email" value="{$profile["email"]|escape}">
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="bot-border"></div>

                                        <div class="col-sm-6  col-xs-6 tital ">Date Joined:</div>
                                        <div class="col-sm-6">{$profile["registration_date"]|date_format:"%d-%m-%Y"}
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="bot-border"></div>

                                        <div class="clearfix"></div>
                                        <div class="bot-border"></div>

                                        <div class="col-sm-12 col-xs-12 tital ">Date Of Birth:</div>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control" name="dateOfBirth" id="BirthDate">
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="bot-border"></div>

                                        <button type="submit" class="btn btn-default"><i class="fa fa-pencil-square-o"
                                                aria-hidden="true"></i>Submit Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


{include file="{$layout}\\footer.tpl"}