<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>HDBS - Donate</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="css/pricing.css">
    <!-- Validate Input -->
    <!--script src="js/val_tickets.js"></script-->

  </head>

  <body>

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
      <a href="http://www.durgabari.org" target="_blank"><img class="mr-3" width="50" height="48" src="img\logo.jpg"></a><h5 class="my-0 mr-md-auto font-weight-normal">Houston Durgabari Society<br /><small>13944 Schiller Road, Houston TX 77082</small></h5>
      <!--a class="btn btn-outline-primary" href="http://www.durgabari.org" target="_blank">HDBS Home</a-->
    </div>

    <div class="pricing-header mx-auto text-center">
      <h1 class="display-6">HDBS Donations</h1>
      <p class="lead">Specify Member ID or Look it up</p>
    </div>
    <div class="container">
      <!--form action="precharge.php" method="post" id="item-form"-->
      <form method="post" name="item-form" id="item-form" action="precharge.php" >

      <div class="card-deck text-center">
              <div class="card border-dark mb-3">
                <div class="card-header" style="background-color:#00cc00; color:#fff">
                  <h3 class="my-0 font-weight-normal">Donate</br><small></small></h3>
                </div>
                <div class="card-body">
                  <h4 class="card-title pricing-card-title">Specify Member ID<p class="card-text font-italic text-danger"><small>(For donation credits)</small></p><br></h4>
                      <input type="number" min="0" class="form-control" id="mid" name="mid" placeholder="0">
                      <p>(Use 0 if not in member database)</p>
                  <button type="submit" class="btn btn-primary">Continue</button>
                </div>
              </div>
              <div class="card border-dark mb-3">
                <div class="card-header" style="background-color:#800000; color:#fff">
                  <h3 class="my-0 font-weight-normal">Lookup</br><small></small></h3>
                </div>
                <div class="card-body">
                  <h4 class="card-title pricing-card-title">Search By</h4>
                      <input type="text" class="form-control form-control-sm mb-1" id="fname" name="fname" placeholder="First Name">
                      <input type="text" class="form-control form-control-sm mb-1" id="lname" name="lname" placeholder="Last Name">
                      <input type="text" class="form-control form-control-sm mb-1" id="zip" name="zip" placeholder="Zip">
                      <p class="card-text font-italic text-danger"><small>(Specify atleast one criteria)</small></p>
                  <button type="submit" class="btn btn-primary" formaction="lookup.php">Continue</button>
                  <!-- a href="#" class="btn btn-primary">Purchase</a -->
                </div>
              </div>
      </div>
      <!--button class="btn btn-lg btn-primary btn-block" type="submit" onclick="form_validate(this.form,'precharge.php')">Continue</button-->
    </form>
    <footer class="pt-4 my-md-3 pt-md-5 ">
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
    <script src="https://js.stripe.com/v3/"></script>
  </body>
</html>
