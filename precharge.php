<?php
  session_save_path("../../cgi-bin");
  session_start();
  require_once('config/db.php');
  require_once('models/DBUtil.php');
 // Sanitize POST Array
 $POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
 $_SESSION['mid'] = $POST['mid'];

// Connect to database
  $connection = @ mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
  // Check connection
  if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

 $mid = $POST['mid'];
 $memrec = [];
 $cat="GD";
 $fn="";
 $ln="";
 $addr1="";
 $addr2="";
 $city="";
 $state="";
 $zip="";

 // check if MID in DB
 if ($mid > 0) {
  if(isIDinDB($connection,$mid)){
    // get the member details
    $memrec = getMemberDetails($connection,$mid);
    $cat=$memrec["cat"];
    $fn=$memrec["fn"];
    $ln=$memrec["ln"];
    $addr1=$memrec["addr1"];
    $addr2=$memrec["addr2"];
    $city=$memrec["city"];
    $state=$memrec["state"];
    $zip=$memrec["zip"];
  } else {
    $mid = 0;
  }
 }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Payment</title>

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
        <h4>HDBS Donations</h4>
        <!--p class="lead">Review Information & Submit for Payment</p-->
    </div>

    <div class="container">
      <div class="py-1 text-center">
        <p class="p-1 mb-1 bg-info text-white">Fill in your email and donation amount, before submitting your Credit Card details for payment.
        </br><small></small></p>
      </div>


          <h5 class="mb-3">Donor Details</h5>
          <form class="needs-validation" action="charge.php" method="post" id="payment-form">
            <div class="row">
              <div class="col-md-6 mb-1">
                <div class="input-group mb-2 mr-sm-2">
                  <label >Member ID:&nbsp&nbsp</label>
                  <div><?php echo $mid; ?></div>
                </div>
              </div>
              <div class="col-md-6 mb-1">
                <div class="input-group mb-2 mr-sm-2">
                <label >Member Category:&nbsp&nbsp</label>
                  <div class="text-info"><?php echo $cat; ?></div>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <div class="input-group mr-sm-3">
                <?php if ($mid > 0): ?>
                  <div> <span style="font-weight:bold"><label>Name:&nbsp&nbsp </label></span></div>
                  <?php echo $fn; ?>&nbsp&nbsp<?php echo $ln; ?>
                <?php else: ?>
                  <div class="mt-1"> <span style="font-weight:bold"><label>Name:&nbsp&nbsp </label></span></div>
                  <input type="text" class="form-control mr-2" id="first_name" name="first_name" placeholder="First Name" value="" required>
                  <div class="invalid-feedback">
                    Valid first name is required.
                  </div>
                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $ln; ?>" required>
                  <div class="invalid-feedback">
                    Valid last name is required.
                  </div>
                <?php endif ?>
              </div>
            </div>
            <?php if ($mid > 0): ?>
                  <div> <span style="font-weight:bold"><label>Address:&nbsp&nbsp </label></span><?php echo $addr1; ?>&nbsp&nbsp<?php echo $addr2; ?>
                  </div>
                  <div>
                    <span style="font-weight:bold"><label>City:&nbsp&nbsp </label></span><?php echo $city; ?>
                    <span style="font-weight:bold"><label>State:&nbsp&nbsp </label></span><?php echo $state; ?>
                    <span style="font-weight:bold"><label>Zip:&nbsp&nbsp </label></span><?php echo $zip; ?>
                  </div>
            <?php else: ?>
		          <div class="row">
                  <div class="col-md-2"> <span style="font-weight:bold"><label>Address:&nbsp&nbsp </label></span><?php echo $addr1; ?>&nbsp&nbsp<?php echo $addr2; ?>
                  </div>
                  <div class="col-md-2 mb-3">
                    <input type="text" class="form-control" id="addr1" name="addr1" placeholder="Street #" value="<?php echo $addr1; ?>" >
                  </div>
                  <div class="col-md-8 mb-3">
                    <input type="text" class="form-control" id="addr2" name="addr2" placeholder="Street" value="<?php echo $addr2; ?>">
                  </div>
		          </div>
            <div class="row">
              <div class="col-md-5 mb-3">
                <!--label for="city">City</label-->
                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?php echo $city; ?>" required>
                <div class="invalid-feedback">
                  Please enter a City.
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <!--label for="state">State</label-->
                <select class="custom-select d-block w-100" id="state" name="state" required>
                  <option value="<?php echo $state; ?>">Select State</option>
                  <optgroup label="U.S. States/Territories">
                  <option value="AK">Alaska</option>
                  <option value="AL">Alabama</option>
                  <option value="AR">Arkansas</option>
                  <option value="AZ">Arizona</option>
                  <option value="CA">California</option>
                  <option value="CO">Colorado</option>
                  <option value="CT">Connecticut</option>
                  <option value="DC">District of Columbia</option>
                  <option value="DE">Delaware</option>
                  <option value="FL">Florida</option>
                  <option value="GA">Georgia</option>
                  <option value="HI">Hawaii</option>
                  <option value="IA">Iowa</option>
                  <option value="ID">Idaho</option>
                  <option value="IL">Illinois</option>
                  <option value="IN">Indiana</option>
                  <option value="KS">Kansas</option>
                  <option value="KY">Kentucky</option>
                  <option value="LA">Louisiana</option>
                  <option value="MA">Massachusetts</option>
                  <option value="MD">Maryland</option>
                  <option value="ME">Maine</option>
                  <option value="MI">Michigan</option>
                  <option value="MN">Minnesota</option>
                  <option value="MO">Missouri</option>
                  <option value="MS">Mississippi</option>
                  <option value="MT">Montana</option>
                  <option value="NC">North Carolina</option>
                  <option value="ND">North Dakota</option>
                  <option value="NE">Nebraska</option>
                  <option value="NH">New Hampshire</option>
                  <option value="NJ">New Jersey</option>
                  <option value="NM">New Mexico</option>
                  <option value="NV">Nevada</option>
                  <option value="NY">New York</option>
                  <option value="OH">Ohio</option>
                  <option value="OK">Oklahoma</option>
                  <option value="OR">Oregon</option>
                  <option value="PA">Pennsylvania</option>
                  <option value="PR">Puerto Rico</option>
                  <option value="RI">Rhode Island</option>
                  <option value="SC">South Carolina</option>
                  <option value="SD">South Dakota</option>
                  <option value="TN">Tennessee</option>
                  <option value="TX">Texas</option>
                  <option value="UT">Utah</option>
                  <option value="VA">Virginia</option>
                  <option value="VT">Vermont</option>
                  <option value="WA">Washington</option>
                  <option value="WI">Wisconsin</option>
                  <option value="WV">West Virginia</option>
                  <option value="WY">Wyoming</option>
                  </optgroup>
                  <optgroup label="Canadian Provinces">
                  <option value="AB">Alberta</option>
                  <option value="BC">British Columbia</option>
                  <option value="MB">Manitoba</option>
                  <option value="NB">New Brunswick</option>
                  <option value="NF">Newfoundland</option>
                  <option value="NT">Northwest Territories</option>
                  <option value="NS">Nova Scotia</option>
                  <option value="NU">Nunavut</option>
                  <option value="ON">Ontario</option>
                  <option value="PE">Prince Edward Island</option>
                  <option value="QC">Quebec</option>
                  <option value="SK">Saskatchewan</option>
                  <option value="YT">Yukon Territory</option>
                  </optgroup>
                </select>
                <div class="invalid-feedback">
                  Please provide a valid state.
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <!--label for="zip">Zip</label-->
                <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" value="<?php echo $zip; ?>" required>
                <div class="invalid-feedback">
                  Zip code required.
                </div>
              </div>
            </div>
          <?php endif ?>
          <div class="mb-3">
              <div class="input-group mr-sm-3">
                <div class="mt-1"> <span style="font-weight:bold"><label for="email">Email:&nbsp&nbsp </label></span></div>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com (Mandatory - email receipts)" value="" required>
                <div class="invalid-feedback">
                  Please enter a valid email address for payment processing and purchase confirmation.
                </div>
              </div>
          </div>
            <div class="input-group mb-2 mr-sm-2">
                <div class="mt-1"> <span style="font-weight:bold"><label for="amount">Donation Amount:&nbsp&nbsp </label></span></div>
                <div class="input-group-prepend">
                  <div class="input-group-text">$</div>
                </div>
                <input type="number" class="form-control" id="amount" name="amount" step="1.00" min="20" required>
                <div class="invalid-feedback">
                  Minimum donation amount is $20.
                </div>
            </div>

            <div class="mb-3">
              <div id="card-element" class="form-control">
			          <!-- a Stripe Element will be inserted here. -->
			        </div>
                <!-- Used to display form errors -->
              <div id="card-errors" role="alert"></div>
            </div>
            <button class="btn btn-primary" type="submit">Submit Payment</button>
            <p class="text-danger text-center font-weight-bold" ><small>(Donations to HDBS are non-refundable.)</small></p>

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
    <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict';

        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');

          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="./js/charge.js"></script>
  </body>
</html>
