<?php 
include('include/header.php');
?>
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Company-info</li>
    </ol>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-company" role="tab" aria-controls="nav-home" aria-selected="true">Company-info</a>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-company" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="card-body">
                    <div class="form-group">
                    <h4>Update Company Information</h4>
                        <hr class="colorgraph">
                        <form id="update_company_form">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label>Company Name <i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" maxlength="100" placeholder="Enter company name">
                                        <div id="company_name_error_message" class="text-danger"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input type="text" class="form-control" id="website" name="website" maxlength="100" placeholder="Enter website url">
                                        <div id="website_error_message" class="text-danger"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <input type="text" class="form-control" id="email" name="email" maxlength="100" placeholder="Enter email">
                                        <div id="email_error_message" class="text-danger"></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea id="address" name="address" class="form-control" rows="2" maxlength="500" autocomplete="off" placeholder="Enter address"></textarea>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>City</label>
                                            <input type="text" class="form-control" id="city" name="city" maxlength="100" placeholder="Enter city">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Country</label>
                                            <input type="text" class="form-control" id="country" name="country" maxlength="100" placeholder="Enter country">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Postal/Zip Code</label>
                                            <input type="text" class="form-control" id="zip_code" name="zip_code" maxlength="100" placeholder="Enter zip code">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone" maxlength="100" placeholder="Enter phone">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Fax</label>
                                            <input type="text" class="form-control" id="fax" name="fax" maxlength="100" placeholder="Enter fax">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Vat Number</label>
                                            <input type="text" class="form-control" id="vat_number" name="vat_number" maxlength="100" placeholder="Enter vat number">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Company Number</label>
                                            <input type="text" class="form-control" id="company_number" name="company_number" maxlength="100" placeholder="Enter company number">
                                        </div>
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
        </div>
    </div>
<?php  
include 'include/footer.php';
?>

<script>

    $(document).ready(function () {

        getCompany();

        var error_company_name = false;
        var error_email = false;
        var error_website = false;

        $("#company_name").focusout(function() {
            check_company_name();
        });

        $("#email").focusout(function () {
            check_email();
        });

        $("#website").focusout(function() {
            check_website();
        });

        function check_company_name() {

            if( $.trim( $('#company_name').val() ) == '' ){
                $("#company_name_error_message").html("Company name is a required field.");
                $("#company_name_error_message").show();
                $("#company_name").addClass("is-invalid");
                error_company_name = true;
            } else {
                $("#company_name_error_message").hide();
                $("#company_name").removeClass("is-invalid");
            }
        }

        function check_email() {

            var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
            var email_length = $("#email").val().length;

            if ($.trim($('#email').val()) == ''){
                $("#email_error_message").hide();
            }else if (!(pattern.test($("#email").val()))){
                $("#email_error_message").html("Invalid email address.");
                $("#email_error_message").show();
                error_email = true;
            }else{
                error_email = false;
                $("#email_error_message").hide();
            }
        }

        function check_website() {

            var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
            var website = $("#website").val();

            if( $.trim( $('#website').val() ) == '' ){
                $("#website_error_message").hide();
            }else if(!website.match(pattern)) {
                $("#website_error_message").html("Enter a valid URL.");
                $("#website_error_message").show();
                error_website = true;
            } else{
                $("#website_error_message").hide();
            }
        }

        $('#cancel_button').click(function(){
            swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: ["No", "Yes, cancel!"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    location.reload();
                }
            });
        });

        function getCompany() {
            $.ajax({
                type: "POST",
                data: {action: 'profile_fetch'},
                url: "company_action.php",
                dataType: "json",
                success: function (data) {
                    $('#company_name').val(data.company_name);
                    $('#website').val(data.company_website);
                    $('#email').val(data.company_email);
                    $('#address').val(data.company_address);
                    $('#city').val(data.company_city);
                    $('#country').val(data.company_country);
                    $('#zip_code').val(data.company_zip_code);
                    $('#phone').val(data.company_phone);
                    $('#fax').val(data.company_fax);
                    $('#vat_number').val(data.company_vat_number);
                    $('#company_number').val(data.company_number);
                }
            });
        }

        $('#update_company_form').on('submit', function (event) {

            event.preventDefault();
            error_company_name = false;
            error_email = false;
            error_website = false;

            check_company_name();
            check_email();
            check_website();

            if (error_company_name == false && error_email == false && error_website == false) {
                $.ajax({
                    type: "POST",
                    data: $('#update_company_form').serialize()+'&action=update_company',
                    url: "company_action.php",
                    dataType: "json",
                    success: function (data) {
                        if (data.status == 'success') {
                            swal("Success!", data.message, "success");
                            getCompany();
                        }
                    },
                    error: function () {
                        swal("Oops..!", "Something went wrong.", "error");
                    }
                });
            }
        });
    });

</script>