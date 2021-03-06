<?php

  ini_set("session.save_path","/var/www/html/session/");

  session_start();

?>

<!DOCTYPE html>

<html>

  <head>
    
    <?php

        if(@!$_SESSION['hoa_username'])
          header("Location: logout.php");

        $community_id = $_SESSION['hoa_community_id'];

        include 'includes/dbconn.php';

        $query = "SELECT * FROM board_committee_details WHERE user_id=".$_SESSION['hoa_user_id'];
        $result = pg_query($query);
        $board = pg_num_rows($result);

        $hoa_id = $_SESSION['hoa_hoa_id'];
        $home_id = $_SESSION['hoa_home_id'];

    ?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <title><?php echo $_SESSION['hoa_community_name']; ?></title>
    
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
   
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    
    <div class="wrapper">

        <header class="main-header">
        
          <a class="logo">
          
              <span class="logo-mini"><?php echo $_SESSION['hoa_community_code']; ?></span>
          
              <span class="logo-lg"><?php echo $_SESSION['hoa_community_name']; ?></span>

          </a>
        
          <nav class="navbar navbar-static-top">
          
              <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                
                <span class="sr-only">Toggle navigation</span>

              </a>

              <div class="navbar-custom-menu">
              
                <ul class="nav navbar-nav">

                  <?php

                    if($board)
                    echo "<li class='dropdown user user-menu'>
                  
                      <a href='boardDashboard.php'>Board Dashboard</a>

                    </li>";

                  ?>

                  <li class="dropdown user user-menu">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  
                        <i class="fa fa-user"></i> <span class="hidden-xs"><?php echo $_SESSION['hoa_username']; ?></span>

                    </a>

                    <ul class="dropdown-menu">
                    
                        <li class="user-header">
                      
                          <i class="fa fa-user fa-5x"></i>

                          <p>
                        
                              <?php echo $_SESSION['hoa_username']; ?>

                              <br>

                              <small><?php echo $_SESSION['hoa_address']; ?></small>

                              <a href="logout.php" class="btn btn-warning">Log Out</a>

                            <br>

                          </p>

                        </li>

                    </ul>

                  </li>

                </ul>

              </div>

          </nav>

        </header>
      
        <aside class="main-sidebar">
        
          <section class="sidebar">
          
              <ul class="sidebar-menu">
            
                <li class="header text-center"> Quick Links </li>

                <li class="treeview">
              
                    <a href='https://hoaboardtime.com/residentDashboard.php'>
                
                      <i class="fa fa-dashboard"></i> <span><?php if($board) echo "Resident "; ?>Dashboard</span>

                    </a>

                </li>
            
                <li class="treeview">
              
                    <a href='https://hoaboardtime.com/residentDocumentManagement.php'>

                      <i class="glyphicon glyphicon-hdd"></i> <span>Document Management</span>

                    </a>

                </li>
             
                <li class="treeview">

                    <a href='https://hoaboardtime.com/residentViewMeetingMinutes.php'>

                      <i class='fa fa-folder'></i> <span>Meeting Minutes</span>
              
                    </a>

                </li>
            
                <li class="active treeview">
              
                    <a>

                      <i class='fa fa-dollar'></i> <span>Quick Pay</span>

                    </a>

                </li>

                <li class="treeview">

                    <a href='https://hoaboardtime.com/residentRecurringPay.php'>

                      <i class='fa fa-repeat'></i> <span>Recurring Pay</span>
              
                    </a>

                </li>

              </ul>

          </section>

        </aside>

      <div class="content-wrapper">

        <?php

          $year = date("Y");
          $month = date("m");
          $end_date = date("t");

          $result = pg_query("SELECT firstname, lastname, email, cell_no FROM hoaid WHERE hoa_id=".$hoa_id);

          $row = pg_fetch_assoc($result);

          $fname = $row['firstname'];
          $lname = $row['lastname'];
          @$email = $row['email'];
          @$cell_no = base64_decode($row['cell_no']);

          $result = pg_query("SELECT * FROM homeid WHERE home_id=".$home_id);

          $row = pg_fetch_assoc($result);

          $address = $row['address1'];
          @$city = $row['city_id'];
          @$state = $row['state_id'];
          @$zip = $row['zip_id'];

          @$result = pg_query("SELECT * FROM zip WHERE zip_id=".$zip);
          @$row = pg_fetch_assoc($result);
          @$zip = $row['zip_code'];

          @$result = pg_query("SELECT * FROM city WHERE city_id=".$city);
          @$row = pg_fetch_assoc($result);
          @$city = $row['city_name'];

          @$result = pg_query("SELECT * FROM state WHERE state_id=".$state);
          @$row = pg_fetch_assoc($result);
          @$state = $row['state_code'];

          $result=pg_query("SELECT * FROM assessment_amounts WHERE community_info_community_id=".$community_id." AND year=".date('Y'));

          $row = pg_fetch_assoc($result);

          $amount = $row['amount'];
          $url = $row['url'];
          $api_key = $row['api_key'];

        ?>

        <section class="content-header">

          <h1><strong>Quick Pay</strong></h1>

        </section>

        <br>

        <section class="box content">
          
          <div class="row">

            <br>

            <div class="col-xl-offset-2 col-lg-offset-2 col-md-offset-1 col-xl-8 col-lg-8 col-md-10 col-xs-12">

              <form method="POST" action="https://swp.paymentsgateway.net/co/default.aspx">

                <div class="row">

                  <div class="form-group col-xl-4 col-xl-offset-2 col-lg-4 col-lg-offset-2 col-md-6 col-xs-12">

                    <label for="pg_billto_postal_name_first">First Name</label>
                    <input class="form-control m-b" disabled type="text" value="<?php echo $fname; ?>">
                    <input class="form-control m-b" hidden type="hidden" name="pg_billto_postal_name_first" id="pg_billto_postal_name_first" value="<?php echo $fname; ?>">

                  </div>

                  <div class="form-group col-xl-4 col-lg-4 col-md-6 col-xs-12">

                    <label for="pg_billto_postal_name_last">Last Name</label>
                    <input class="form-control m-b" type="text" disabled value="<?php echo $lname; ?>">
                    <input class="form-control m-b" type="hidden" name="pg_billto_postal_name_last" id="pg_billto_postal_name_last" hidden value="<?php echo $lname; ?>">

                  </div>

                </div>

                <div class="row">

                  <div class="form-group col-xl-4 col-xl-offset-2 col-lg-4 col-lg-offset-2 col-md-6 col-xs-12">

                    <label for="pg_billto_postal_name_last">HOA ID</label>
                    <input class="form-control m-b" disabled type="text" value="<?php echo $hoa_id; ?>">
                    <input class="form-control m-b" hidden type="hidden" name="pg_consumer_id" id="pg_consumer_id" value="<?php echo $_SESSION['hoa_hoa_id']; ?>">

                  </div>

                  <div class="form-group col-xl-4 col-lg-4 col-md-6 col-xs-12">

                    <label for="pg_billto_postal_name_last">Total Amount (in $)</label>
                    <input class="form-control m-b" type="number" step='0.01' name="pg_total_amount" id="pg_total_amount" value="<?php echo $amount; ?>" required>

                    <input type="hidden" name="pg_tc_show" id="pg_tc_show" value="True">
                    <input type="hidden" name="pg_billto_postal_street_line1" id="pg_billto_postal_street_line1" value="<?php echo $address; ?>">
                    <input type="hidden" name="pg_billto_postal_city" id="pg_billto_postal_city" value="<?php echo $city; ?>">
                    <input type="hidden" name="pg_billto_postal_stateprov" id="pg_billto_postal_stateprov" value="<?php echo $state; ?>">
                    <input type="hidden" name="pg_billto_postal_postalcode" id="pg_billto_postal_postalcode" value="<?php echo $zip; ?>">
                    <input type="hidden" name="pg_billto_online_email" id="pg_billto_online_email" value="<?php echo $email; ?>">
                    <input type="hidden" name="pg_billto_telecom_phone_number" id="pg_billto_telecom_phone_number" value="<?php echo $cell_no; ?>">
                    <input type="hidden" name="pg_merchant_data_1" id="pg_merchant_data_1" value="<?php echo $hoa_id; ?>">
                    <input type="hidden" name="pg_consumerorderid" id="pg_consumerorderid" value="<?php echo $hoa_id; ?>">
                    <input type="hidden" name="pg_wallet_id" id="pg_wallet_id" value="<?php echo $hoa_id; ?>">
                    <input type="hidden" name="pg_continue_url" id="pg_continue_url" value="<?php echo $url; ?>">
                    <input type="hidden" name="pg_api_login_id" id="pg_api_login_id" value="<?php echo $api_key; ?>">
                    <input type="hidden" name="pg_transaction_type" id="pg_transaction_type" value="20">

                  </div>

                </div>

                <div class="row text-center">

                  <button type="submit" class="btn btn-success">Pay Now</button>
                  <br><br>

                </div>

              </form>
              
            </div>

            <div class="clearfix"></div>

          </div>

        </section>

      </div>

      <footer class="main-footer">

        <div class="pull-right hidden-xs"></div>
        
        <strong>Copyright &copy; <?php echo date('Y'); ?> <a target='_blank' href="<?php echo $_SESSION['hoa_community_website_url']; ?>"><?php echo $_SESSION['hoa_community_name']; ?></a>.</strong> All rights reserved.

      </footer>

      <div class="control-sidebar-bg"></div>

    </div>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="plugins/knob/jquery.knob.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/pages/dashboard.js"></script>
    <script src="dist/js/demo.js"></script>

  </body>

</html>