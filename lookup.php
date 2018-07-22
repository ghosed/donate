<?php
  require_once('config/db.php');
  require_once('models/DBUtil.php');

// Connect to database
  $connection = @ mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
  // Check connection
  if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

 // Sanitize POST Array
 $POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

 $fn = $POST['fname'];
 $ln = $POST['lname'];
 $zip = $POST['zip'];
// create search criteria
$s_criteria = create_crit($fn,$ln,$zip);

$query = "SELECT Member_id,Category,F_Name,L_Name,Sp_FName,City,State,Zip FROM details WHERE $s_criteria";

// ... and then complete the query.
$query .= " ORDER BY L_Name,F_Name;";

// run search query
$memrecs = [];
$memrecs = LUhdbsDB($connection,$query);
$numrec = count($memrecs);
if($numrec == 0) $mid = 0;

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Lookup</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="css/form-validation.css" rel="stylesheet">
  </head>

  <body class="bg-light">
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
        <a href="http://www.durgabari.org" target="_blank"><img class="mr-3" width="50" height="48" src="img\logo.jpg"></a><h5 class="my-0 mr-md-auto font-weight-normal">Houston Durgabari Society<br /><small>13944 Schiller Road, Houston TX 77082</small></h5>
    </div>
    <div class="pricing-header mx-auto text-center">
        <h4>HDBS Membership Lookup</h4>
        <!--p class="lead">Review Information & Submit for Payment</p-->
    </div>

    <div class="container">
      <div class="py-1 text-center">
        <p class="p-1 mb-1 bg-info text-white">Select your membership ID below. If not in database, select 0 (the last row)</p>
      </div>


          <h5 class="mb-3">Search Results</h5>
          <form class="needs-validation" action="precharge.php" method="post" id="payment-form">

                <div class="table-responsive">          
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Select</th>
                        <th>Member ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Spouse</th>
                        <th>City</th>
                        <th>Zip</th> 
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        for ($i = 0; $i < $numrec; $i++) {
                    ?>
                    <tr>
                        <td><input type='radio' name='mid' value='<?php echo $memrecs[$i]["mid"]; ?>' /></td>
                        <td><?php echo $memrecs[$i]["mid"]; ?></td>
                        <td><?php echo $memrecs[$i]["fn"]; ?></td>
                        <td><?php echo $memrecs[$i]["ln"]; ?></td>
                        <td><?php echo $memrecs[$i]["spn"]; ?></td>
                        <td><?php echo $memrecs[$i]["city"]; ?></td>
                        <td><?php echo $memrecs[$i]["zip"]; ?></td>
                    </tr>
                    <?php
                    } // end for
                    ?>
                    <tr style="color:red">
                        <td><input type='radio' name='mid' value='0' /></td>
                        <td>0</td>
                        <td>New</td>
                        <td>New</td>
                        <td>New</td>
                        <td>New</td>
                        <td>New</td>
                    </tr>
                    </tbody>
                </table>
                </div>

            
            <button class="btn btn-primary btn-block " type="submit">Continue</button>
            <p class="text-danger text-center font-weight-bold" ><small>(Complete payment details on next page)</small></p>

          </form>
          <form action="index.php">
            <button type="submit" class="mt-3 btn btn-block btn-warning">  Go Back  </button>
          </form>

          <footer class="pt-2 my-md-3 pt-md-5 border-top">
            <div class="pricing-header mx-auto text-center">
              <img class="mb-2 align-middle" src="favicon.ico">
              <small class="text-center text-muted">HDBS 2018-20</small>
            </div>
          </footer>


    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"><\/script>')</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.4/holder.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  </body>
</html>
