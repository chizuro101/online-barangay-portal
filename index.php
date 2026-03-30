<?php

require_once 'config/config.php';
require_once 'classes/User.php';

$userObj = new User();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Montseratt Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap" rel="stylesheet">
    
    <!-- Local CSS -->
    <link rel="stylesheet" href="assets/css/home.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">

    <!-- Link for Bootstrap Icons -->
    <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
    
    <style>
        .register-modal-heading {
            color: #28a745;
            font-weight: 600;
        }
        
        #register-modal .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0.75rem;
        }
        
        #register-modal .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        #register-modal label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        #register-modal .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 0.5rem 2rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        #register-modal .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        }
        
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>

    <script type="text/javascript">
(function(d, eId) {
	var js, gjs = d.getElementById(eId);
	js = d.createElement('script'); js.id = 'gwt-pst-jsdk';
	js.src = "//gwhs.i.gov.ph/pst/gwtpst.js?"+new Date().getTime();
	gjs.parentNode.insertBefore(js, gjs);
}(document, 'pst-container'));

var gwtpstReady = function(){
	new gwtpstTime('pst-time');
}
</script>

    <title>Home</title>
</head>

<body>
     
    <!-- Navbar Markup -->
    <div>
        <nav class="navbar navbar-expand-lg mb-5">

            <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbar1">
                <span class="navbar-toggler-icon"></span>
            </button>

            <img src="assets/images/Logo.png" alt="logo" id="nav-img" class="ml-md-5">

            <div class="navbar-collapse collapse  justify-content-center" id="navbar1">
                <ul class="navbar-nav">

                    <li class="nav-item pl-3 pr-3 ">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item pl-3 pr-3 ">
                        <a class="nav-link" href="#barangay-officials">Barangay Officials</a>
                    </li>
                    
                </ul>
            </div>

            <button class="btn btn-outline-danger login-btn mr-md-5" type="button" id="login-btn" data-toggle="modal" data-target="#login-modal"> Login </button>

        </nav>
    </div>
    <br><br>


        <!-- Hero Area -->
        <div class="row align-items-center justify-content-center m-0" id="heroarea" style="background-image: url('assets/images/jm.jpg'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 500px; position: relative; margin-top: 0;">
            <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.3); z-index: 1;"></div>
            <div class="col-md-8 col-sm-10" style="position: relative; z-index: 2; padding-top: 0;">
                <h1 class="mt-0 mb-3 text-white">Barangay Sta. Cruz Viejo</h1>
                <p class="pt-0 pb-2 text-white lead">Barangay Sta. Cruz Viejo is a continuously growing barangay that has more than one thousand residents.</p>
                <button id="viewmore-btn" class="btn btn-lg btn-outline-light mt-3 mb-5"><a class="text-light text-decoration-none" href="#mission-vision">VIEW MORE</a></button>
            </div>
        </div>

        <!-- Announcements Section -->
        <div class="row m-0 justify-content-center py-5" id="announcements">
            <div class="col-12 text-center p-5">
                <h3 class="mb-4">LATEST ANNOUNCEMENTS</h3>
            </div>
            <div class="col-lg-10 col-md-11">
                <div class="row" id="announcements-container">
                    <?php
                    try {
                        $announcements = $userObj->getAllAnnouncements();
                        if (!empty($announcements)) {
                            $displayAnnouncements = array_slice($announcements, 0, 3);
                            foreach ($displayAnnouncements as $row) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card announcement-card h-100">
                            <?php if(!empty($row['post_image'])): ?>
                                <?php 
                                // Fix old image paths
                                $imagePath = $row['post_image'];
                                if (strpos($imagePath, 'uploads/') === 0) {
                                    $imagePath = 'storage/' . $imagePath;
                                } elseif (strpos($imagePath, 'documents/') === 0) {
                                    $imagePath = 'storage/uploads/' . $imagePath;
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top" alt="Announcement Image" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0"><?php echo htmlspecialchars($row['post_title']); ?></h6>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?php echo htmlspecialchars(substr($row['post_body'], 0, 150)) . '...'; ?></p>
                                <small class="text-muted">Posted on: <?php echo date('M d, Y', strtotime($row['post_date_time'])); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php
                            }
                        } else {
                    ?>
                    <div class="col-12 text-center">
                        <p>No announcements available at the moment.</p>
                    </div>
                    <?php
                        }
                    } catch (Exception $e) {
                        error_log("Announcements error: " . $e->getMessage());
                    ?>
                    <div class="col-12 text-center">
                        <p>Unable to load announcements at the moment.</p>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="text-center mt-4">
                    <a href="user/announcements.php" class="btn btn-danger">View All Announcements</a>
                </div>
            </div>
        </div>

        <!-- Barangay Officials Section -->
        <div class="row m-0 justify-content-center py-5" id="barangay-officials">
            <div class="col-12 text-center p-5">
                <h3 class="mb-4">BARANGAY OFFICIALS</h3>
            </div>
            <div class="col-lg-10 col-md-11">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th scope="col">Position</th>
                                <th scope="col">Name</th>
                                <th scope="col">Sex</th>
                                <th scope="col">Contact Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $officials = $userObj->getAllOfficials();
                                if (!empty($officials)) {
                                    foreach ($officials as $official) {
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($official['official_position'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($official['official_first_name'] . ' ' . ($official['official_middle_name'] ?? '') . ' ' . $official['official_last_name']); ?></td>
                                <td><?php echo htmlspecialchars($official['official_sex'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($official['official_contact_info'] ?? 'N/A'); ?></td>
                            </tr>
                            <?php
                                    }
                                } else {
                            ?>
                            <tr>
                                <td colspan="4" class="text-center">No officials information available at the moment.</td>
                            </tr>
                            <?php
                                }
                            } catch (Exception $e) {
                                error_log("Officials error: " . $e->getMessage());
                            ?>
                            <tr>
                                <td colspan="4" class="text-center">Unable to load officials information at the moment.</td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mission / Vision -->
        <div class="row align-items-center justify-content-around bg-danger text-light m-0" id="mission-vision"> 
            <div class="col-md-5 text-center py-md-5 px-md-0 p-sm-5" id="mission">
                <h4 class="py-2">MISSION</h4>
                <p class="py-2">Barangay Sta. Cruz Viejo will continuously work in achieving unity and cooperation within the whole barangay to attain of peace, harmony, and progress, together with improving and enhancing the delivery of basic social services and infrastructure facilities. The people’s capacity to manage resources and organizations is strengthened together with coordination and association with several agencies of the government and the private sector.”</p>
            </div>
            <div class="col-md-5 text-center py-md-5 px-md-0 p-sm-5" id="vision">
                <h4 class="py-2">VISION</h4>
                <p class="py-2"> Barangay Sta. Cruz Viejo is a barangay that is peaceful, prosperous, and united. The barangay residents are god-fearing, healthy, industrious, happy, and well-off citezens that are united together for a common purpose of achieving food sufficiency, barangay productivity, barangay efficiency, and clean invironment under a a democratic system of management. Fair and human leadership that shall ultimately result in a better quality of life of the people.”</p>
            </div>
        </div>

        <!-- Contact -->
        <div class="row m-0 justify-content-center" id="contact">
            <div class="col-12 text-center p-5 pb-lg-5 pb-md-0 pb-sm-0">
                <h4>CONTACT</h4>
            </div>
            <div class="col-lg-4 align-items-center col-sm-10 p-5 clearfix" >
                <h5 class="pb-3 pt-sm-0">Get In Touch</h5>
                <p class="pt-4" id="b-top">brgystacruzviejo@gmail.com</p>
                <img src="assets/images/telephone-icon.png" alt="phone-icon">
                <ul class="d-inline-block m-0 p-0 pl-3 clearfix">
                    <li>+ 63 97 5832 1123</li>
                    <li>+ 63 97 5832 1123</li>
                </ul>
                <p class="pt-3" id="b-bot">(02)7576-4567</p>
                <img src="assets/images/fb-icon.png" class="pr-1" alt="fb-icon">
                <img src="assets/images/email-icon.png" alt="email-icon">
            </div>
            <div class="col-lg-5 col-md-10 col-sm-10 align-items-center pl-5 pt-lg-5 mb-lg-5 mb-md-0 mb-sm-0" id="b-left">
                <h6 class="pb-3">Sta. Cruz Viejo, Tanjay City, Negros Oriental</h6> 
                <img src="assets/images/map.png" alt="location" class="mb-lg-5 mb-sm-0 mb-md-0 img-fluid"> 
            </div>
        </div>

        <!-- Footer -->
        <div class="row m-0 p-0">
            <div class="col-12 text-center text-muted">
                <p class="m-0 pb-3 mt-md-2 mt-sm-2">Brgy. Sta. Cruz Viejo, Copyright ©️ 2026 </p>
            </div>
        </div>

            <!-- Markup for Login Modal -->
            <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal-heading">
                <div class="modal-dialog modal-md" role="document">

                    <div class="modal-content">

                        <div class="modal-header">
                            <h1 class="modal-title login-modal-heading" id="login-modal-heading">Login</h1>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-container">
                                <form>

                                    <div class="form-row">
                                        <div class="col-md-12 form-group ">
                                            <div class="input-group mb-2">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-user"> </i> </span>
                                                </div>
                                                <input type="text" name="username_field" id="username_field" class="form-control login-modal-field" placeholder="Enter Username" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 form-group ">
                                            <div class="input-group mb-3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-key"> </i> </span>
                                                </div>
                                                <input type="password" name="password_field" id="password_field" class="form-control login-modal-field" placeholder="Enter Password" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col form-group text-right  pr-2 mr-2">
                                            <button type="submit" class="btn login-modal-btn" id="btn_login" name="btn_login">Sign in</button>                                   
                                        </div>
                                        <div class="col form-group text-left pl-2 ml-2">
                                            <button type="button" data-dismiss="modal" class="btn login-modal-btn" id="btn_cancel" name="btn_cancel">Cancel</button>                                   
                                        </div>
                                    </div>
                                    
                                    <div class="text-center mt-3">
                                        <p class="mb-0">Don't have an account? <a href="#" id="show-register" class="text-danger">Register here</a></p>
                                    </div>
                                    
                                    
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

    </div>

    <!-- Registration Modal -->
    <div class="modal fade" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="register-modal-heading">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title register-modal-heading" id="register-modal-heading">Resident Registration</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="register-form" method="POST" action="admin_Actions.php">
                        <input type="hidden" name="btn_add_resident" value="1">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reg_first_name">First Name *</label>
                                    <input type="text" name="first_name_field" id="reg_first_name" class="form-control" placeholder="Enter First Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reg_last_name">Last Name *</label>
                                    <input type="text" name="last_name_field" id="reg_last_name" class="form-control" placeholder="Enter Last Name" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reg_middle_name">Middle Name</label>
                                    <input type="text" name="middle_name_field" id="reg_middle_name" class="form-control" placeholder="Enter Middle Name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="reg_suffix">Suffix</label>
                                    <input type="text" name="suffix_field" id="reg_suffix" class="form-control" placeholder="Jr., Sr., etc.">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="reg_birthday">Birthday *</label>
                                    <input type="date" name="birthday_field" id="reg_birthday" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reg_sex">Sex *</label>
                                    <select name="sex_field" id="reg_sex" class="form-control" required>
                                        <option value="">Select Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reg_civil_stat">Civil Status *</label>
                                    <select name="civil_stat_field" id="reg_civil_stat" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Separated">Separated</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reg_voter_stat">Voter Status</label>
                                    <select name="voter_stat_field" id="reg_voter_stat" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="Registered">Registered</option>
                                        <option value="Not Registered">Not Registered</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reg_mobile">Mobile Number *</label>
                                    <input type="tel" name="mobile_no_field" id="reg_mobile" class="form-control" placeholder="09XXXXXXXXX" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reg_email">Email Address</label>
                                    <input type="email" name="email_field" id="reg_email" class="form-control" placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reg_religion">Religion</label>
                                    <input type="text" name="religion_field" id="reg_religion" class="form-control" placeholder="Enter Religion">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reg_username">Username *</label>
                                    <input type="text" name="username_field" id="reg_username" class="form-control" placeholder="Choose Username" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reg_password">Password *</label>
                                    <input type="password" name="password_field" id="reg_password" class="form-control" placeholder="Choose Password" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reg_alias">Alias/Nickname</label>
                                    <input type="text" name="alias_field" id="reg_alias" class="form-control" placeholder="Enter Alias or Nickname">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-muted small">By registering, you agree to provide accurate information for barangay records.</p>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn_register">Register</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-primary" id="back-to-login">Back to Login</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>

        // Clear Modal Form when Closing
        function clear_modal()
        {
            $('#login-modal').on('hidden.bs.modal', function (e) {
            $(this)
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();
            })
        }

        //Login Button Function
        function login()
        {
            $('#btn_login').click(function(e){

                var valid = this.form.checkValidity();
                
                if(valid)
                {

                    var username = $('#username_field').val();
                    var password = $('#password_field').val();

                    e.preventDefault();

                    $.ajax({
                        type: 'POST',
                        url: 'admin_Actions.php',
                        data: {current_username: username, current_password: password},
                        success: function(data){
                            
                            if($.trim(data) ==="0")
                            {
                                Swal.fire
                                ({
                                    title: 'Login Success',
                                    text: 'Official Login',
                                    icon: 'success'
                                })

                                setTimeout('window.location.href = "admin/dashboard.php"',2000);

                            }

                            else if($.trim(data) ==="1")
                            {
                                Swal.fire
                                ({
                                    title: 'Login Success',
                                    text: 'Resident Login',
                                    icon: 'success'
                                })

                                setTimeout('window.location.href = "user/dashboard.php"',2000);

                            }

                            else if($.trim(data) ==="2")
                            {
                                Swal.fire
                                ({
                                    title: 'Login Success',
                                    text: 'Captain Login',
                                    icon: 'success'
                                })

                                setTimeout('window.location.href = "admin/dashboard.php"',2000);

                            }

                            else
                            {
                                Swal.fire
                                ({
                                    title: 'Failed',
                                    text: data,
                                    icon: 'error'
                                })
                            }
                        },

                        error: function(data){
                            Swal.fire
                                ({
                                title: 'Failed',
                                text: 'Login Failed',
                                icon: 'error'
                                })
                        }

                    });
                };

                $('#login-modal')
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();

            });

        }

        $(document).ready(function(){
            clear_modal();
            login();
            
            // Handle modal switching
            $('#show-register').click(function(e) {
                e.preventDefault();
                $('#login-modal').modal('hide');
                $('#register-modal').modal('show');
            });
            
            $('#back-to-login').click(function() {
                $('#register-modal').modal('hide');
                $('#login-modal').modal('show');
            });
            
            // Handle registration form submission
            $('#register-form').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: 'admin_Actions.php',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Registration successful! You can now login with your credentials.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#register-modal').modal('hide');
                                    $('#login-modal').modal('show');
                                    clear_modal();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'Registration failed. Please try again.',
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Registration failed. Please try again.',
                            icon: 'error'
                        });
                    }
                });
            });
            
            // Fix modal accessibility issues
            $('#login-modal').on('show.bs.modal', function () {
                // Remove aria-hidden from main content when modal opens
                $('body > .container-fluid').removeAttr('aria-hidden');
            });
            
            $('#login-modal').on('hidden.bs.modal', function () {
                // Clear form when modal closes
                $(this).find("input,textarea,select").val('');
                $(this).find("input[type=checkbox], input[type=radio]").prop("checked", "");
            });
            
            $('#register-modal').on('hidden.bs.modal', function () {
                // Clear form when modal closes
                $(this).find("input,textarea,select").val('');
                $(this).find("input[type=checkbox], input[type=radio]").prop("checked", "");
            });
        });

    </script>

</body>

</html>