<?php 
include('include/header.php');
?>
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-home" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-contact" aria-selected="false">Change Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Change Password</a>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="card-body">
                    <div class="form-group">
                        <h4>Profile Details</h4>
                        <hr class="colorgraph">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Full Name</strong>
                            </div>
                            <div class="col-md-6" id="view_full_name">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <strong>E-mail</strong>
                            </div>
                            <div class="col-md-6" id="view_email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Gender</strong>
                            </div>
                            <div class="col-md-6" id="view_gender">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="card-body">
                    <div class="form-group">
                        <h4>Update Information</h4>
                        <hr class="colorgraph">
                        <form id="update_profile_form">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Full Name <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" maxlength="50" placeholder="Enter your full name">
                                    <div id="full_name_error_message" class="text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label>E-mail <i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" id="email" name="email" maxlength="30" readonly>
                                    <div id="email_error_message" class="text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label>Gender <i class="text-danger">*</i></label>
                                    <select name="gender" id="gender" class="form-control">
                                    <option value="" hidden>Gender</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    </select>
                                    <div id="gender_error_message" class="text-danger"></div>
                                </div>
                                </div>
                            </div>
                            <hr class="colorgraph">
                            <div>
                                <button type="button" id="cancel_button"  class="btn btn-light">Cancel</button>
                                <button type="submit" id="update_profile_button" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                <div class="card-body">
                    <div class="form-group">
                        <h4>Update Password</h4>
                        <hr class="colorgraph">
                        <form id="update_password_form">       
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6">
                                <input type="text" hidden="" id="id_User" name="idUser">
                                <div class="form-group">
                                    <label>Current Password <i class="text-danger">*</i></label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current Password">
                                    <div id="current_password_error_message" class="text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label>New Password <i class="text-danger">*</i></label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" maxlength="50" placeholder="Enter password">
                                    <div id="new_password_error_message" class="text-danger"></div>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password <i class="text-danger">*</i></label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" maxlength="50" placeholder="Enter confirm password">
                                    <div id="confirm_password_error_message" class="text-danger"></div>
                                </div>
                                </div>
                            </div>
                            <hr class="colorgraph">
                            <div>
                                <button type="button" id="cancel_button_2"  class="btn btn-light">Cancel</button>
                                <button type="submit" id="update_password_button" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php  
include 'include/footer.php';
?>

<script>

    $(document).ready(function () {

        getProfile();

        var error_full_name = false;
        var error_current_password = false;
        var error_new_password = false;
        var error_confirm_password = false;

        $("#full_name").focusout(function() {
            check_full_name();
        });

        $("#current_password").focusout(function() {
            check_current_password();
        });

        $("#new_password").focusout(function() {
            check_new_password();
        });

        $("#confirm_password").focusout(function() {
            check_confirm_password();
        });

        function check_full_name() {

            if( $.trim( $('#full_name').val() ) == '' ){
                $("#full_name_error_message").html("Full name is a required field.");
                $("#full_name_error_message").show();
                $("#full_name").addClass("is-invalid");
                error_full_name = true;
            } else {
                $("#full_name_error_message").hide();
                $("#full_name").removeClass("is-invalid");
            }

        }

        function check_current_password() {

            var current_password_length = $("#current_password").val().length;

            if( $.trim( $('#current_password').val() ) == '' ){
                $("#current_password_error_message").html("Current password is a required field.");
                $("#current_password_error_message").show();
                $("#current_password").addClass("is-invalid");
                error_current_password = true;
            }else if(current_password_length < 8) {
                $("#current_password_error_message").html("At least 8 characters.");
                $("#current_password_error_message").show();
                $("#current_password").addClass("is-invalid");
                error_current_password = true;
            } else {
                $("#current_password_error_message").hide();
                $("#current_password").removeClass("is-invalid");
            }
        }

        function check_new_password() {

            var current_password = $("#current_password").val();
            var new_password = $("#new_password").val();
            var new_password_length = $("#new_password").val().length;

            if( $.trim( $('#new_password').val() ) == '' ){
                $("#new_password_error_message").html("New password is a required field.");
                $("#new_password_error_message").show();
                $("#new_password").addClass("is-invalid");
                error_new_password = true;
            }else if(new_password_length < 8) {
                $("#new_password_error_message").html("At least 8 characters.");
                $("#new_password_error_message").show();
                $("#new_password").addClass("is-invalid");
                error_new_password = true;
            }else if(new_password == current_password) {
                $("#new_password_error_message").html("New password cannot be same as your current password.");
                $("#new_password_error_message").show();
                $("#new_password").addClass("is-invalid");
                error_confirm_password = true;
            }else{
                $("#new_password_error_message").hide();
                $("#new_password").removeClass("is-invalid");
            }
        }

        function check_confirm_password() {

            var new_password = $("#new_password").val();
            var confirm_password = $("#confirm_password").val();

            if( $.trim( $('#confirm_password').val() ) == '' ){
                $("#confirm_password_error_message").html("Confirm password is a required field.");
                $("#confirm_password_error_message").show();
                $("#confirm_password").addClass("is-invalid");
                error_confirm_password = true;
            }else if(new_password !=  confirm_password) {
                $("#confirm_password_error_message").html("Passwords do not match.");
                $("#confirm_password_error_message").show();
                $("#confirm_password").addClass("is-invalid");
                error_confirm_password = true;
            } else {
                $("#confirm_password_error_message").hide();
                $("#confirm_password").removeClass("is-invalid");
            }
        }

        function getProfile() {
            $.ajax({
                type: "POST",
                data: {action: 'profile_fetch'},
                url: "profile_action.php",
                dataType: "json",
                success: function (data) {
                    $('#view_full_name').text(data['user_full_name']);
                    $('#full_name').val(data.user_full_name);
                    $('#view_email').text(data['user_email']);
                    $('#email').val(data.user_email);
                    $('#view_gender').text(data['user_gender']);
                    $('#gender').val(data.user_gender);
                }
            });
        }

        $('#update_profile_form').on('submit', function (event) {
            event.preventDefault();
            error_full_name = false;

            check_full_name();

            if (error_full_name == false) {
                $.ajax({
                    type: "POST",
                    data: $('#update_profile_form').serialize()+'&action=update_profile',
                    url: "profile_action.php",
                    dataType: "json",
                    success: function (data) {
                        if (data.status == 'success') {
                            swal("Success!", data.message, "success");
                            getProfile();
                        }
                    },
                    error: function () {
                        swal("Oops..!", "Something went wrong.", "error");
                    }
                });
            }
        });

        $('#cancel_button').click(function(){
            cancelUpdate();
        });

        $('#cancel_button_2').click(function(){
            cancelUpdate();
        });

        function cancelUpdate(){
            swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: ["No", "Yes, cancel!"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    location.reload();
                }
            });
        }

        $('#update_password_form').on('submit', function (event) {
            event.preventDefault();
            
            error_current_password = false;
            error_new_password = false;
            error_confirm_password = false;

            check_current_password();
            check_new_password();
            check_confirm_password();

            if(error_current_password == false && error_new_password == false && error_confirm_password == false) {
                $.ajax({
                    type:"POST",
                    data: $('#update_password_form').serialize()+'&action=update_password',
                    url:"profile_action.php",
                    dataType:"json",
                    success:function(data){
                        if (data.status == 'success') {
                            swal("Success!", data.message, "success");
                            $('#update_password_form')[0].reset();       
                        } else if (data.status=='error') {
                            $("#current_password_error_message").html(data.message);
                            $("#current_password_error_message").show();
                            $("#current_password").addClass("is-invalid");
                        }
                    },
                    error:function(){
                        swal("Oops..!", "Something went wrong.", "error");
                    }
                });
            }
        });
    });

</script>