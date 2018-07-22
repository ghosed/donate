<?php
  session_save_path("../../cgi-bin");
  session_start();
  ob_start();
  require_once('../vendor/autoload.php');
  require_once('config/db.php');
  require_once('models/DBUtil.php');

//set api key (Capital Account)
  \Stripe\Stripe::setApiKey('sk_test_SikxjqLtKArFPQ4JUuZ3h4Jg');

// Connect to database
$connection = @ mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

 // Sanitize POST Array
 $POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
 $SESSION = filter_var_array($_SESSION, FILTER_SANITIZE_STRING);
 if (isset($SESSION['mid'])) $mid = $SESSION['mid'];
 if ($mid == 0){
  isset($POST['first_name']) ? $first_name = $POST['first_name'] : $first_name = "";
  isset($POST['last_name']) ? $last_name = $POST['last_name'] : $last_name = "";
  isset($POST['addr1']) ? $addr1 = $POST['addr1'] : $addr1 = "";
  isset($POST['addr2']) ? $addr2 = $POST['addr2'] : $addr2 = "";
  isset($POST['city']) ? $city = $POST['city'] : $city = "";
  isset($POST['state']) ? $state = $POST['state'] : $state = "";
  isset($POST['zip']) ? $zip = $POST['zip'] : $zip = "";
 } else {
  // get the member details from DB
  $memrec = getMemberDetails($connection,$mid);
  $cat=$memrec["cat"];
  $first_name=$memrec["fn"];
  $last_name=$memrec["ln"];
  $addr1=$memrec["addr1"];
  $addr2=$memrec["addr2"];
  $city=$memrec["city"];
  $state=$memrec["state"];
  $zip=$memrec["zip"];
 }
 //
 isset($POST['email']) ? $email = $POST['email'] : $email = "";
 isset($POST['amount']) ? $amount = $POST['amount'] : $amount = "";

// minimum amount to charge ($20)
$minamt = 20*100;
$goback = "<a href='javascript:history.back()'>Go Back</a>";

 // create customer array
 $cust = array();
 $cust = array(
    "fname" => $first_name,
    "lname" => $last_name,
    "email" => $email,
    "addr1" => $addr1,
    "addr2" => $addr2,
    "city" => $city,
    "state" => $state,
    "zip" => $zip
    );

 $desc = "Donation to Houston Durgabari ... ";
 $full_name = $first_name. ' '.$last_name;
 $email  = filter_var($email, FILTER_SANITIZE_EMAIL);
 if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
  exit("This email adress isn't valid! ".$goback);
 }
 //$amount = floatval($POST['amount'])*100;
 $amount = floatval($amount)*100;
 if ($amount < $minamt) {
  $min = $minamt/100;
  exit("Charge amount should be greater than ".$min." !!! ".$goback);
 }

 //get token, card and user info from the form
$token  = $_POST['stripeToken'];

//check whether stripe token is not empty
if(!empty($token)){

  // Create Order ID
  $r_oid=getOID($connection,$incOID);
  //
  //Add Order ID to Description
  try {

    // Create Customer In Stripe
    $customer = \Stripe\Customer::create(array(
      "email" => $email,
      "source" => $token,
      "metadata" => array (
        'NAME'          => $full_name,
        'EMAIL'         => $email,
        'ORDER ID' => $r_oid
        )
    ));
    // Charge Customer
    $charge = \Stripe\Charge::create(array(
        "customer" => $customer->id,
        "amount" => $amount,
        "currency" => "usd",
        "description" => $desc,
        "receipt_email" => $email,
        "metadata" => array ("OID" => $r_oid, "Name"=> $full_name)
    ));

    //retrieve charge details
    $chargeJson = $charge->jsonSerialize();
    //
    //check whether the charge is successful
    if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){
      //order details
      $amount = $chargeJson['amount']/100.0;
      $balance_transaction = $chargeJson['balance_transaction'];
      $status = $chargeJson['status'];
      //$recnum = $chargeJson['receipt_number'];
      $ts = $chargeJson['created'];

      //insert tansaction data into the database
      $rcode = insertDonation($connection,$mid,$r_oid,$cust,$amount,$balance_transaction,$ts,$status);

      // Redirect to success
      //$host  = $_SERVER['HTTP_HOST'];
      //$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
      $success = 'success.php';

      //header("Location: http://$host$uri/$success?tid=".$charge->id."&product=".$charge->description."&status=".$charge->status."&ts=".$charge->created."&oid=".$charge->metadata["OID"]."&name=".$charge->metadata["Name"]."&total=".$charge->amount."&email=".$charge->receipt_email."&mid=".$rcode[0]);
      $_SESSION['tid'] = $charge->id;
      $_SESSION['product'] = $charge->description;
      $_SESSION['status'] = $charge->status;
      $_SESSION['ts'] = $charge->created;
      $_SESSION['oid'] = $charge->metadata["OID"];
      $_SESSION['name'] = $charge->metadata["Name"];
      $_SESSION['total'] = $charge->amount;
      $_SESSION['email'] = $charge->receipt_email;
      $_SESSION['mid'] = $rcode[0];
      header("Location: $success");
      exit();

    }else{
      $statusMsg = "CC Transaction failed";
      print_r($statusMsg);

    }

    // If there is an error from Stripe.
  } catch(\Stripe\Error\Card $e) {
    // Since it's a decline, \Stripe\Error\Card will be caught
    echo "<br> *** Card Declined *** <br>";
        $body = $e->getJsonBody();
        $err  = $body["error"];
        $return_array = [
            "status" =>  $e->getHttpStatus(),
            "type" =>  $err["type"],
            "code" =>  $err["code"],
            "param" =>  $err["param"],
            "message" =>  $err["message"],
        ];
        $return_str = json_encode($return_array);
        print_r ($return_str);

  } catch (\Stripe\Error\RateLimit $e) {
    // Too many requests made to the API too quickly
    echo "<br> *** Too many requests made at a time *** <br>";
        $body = $e->getJsonBody();
        $err  = $body["error"];
        $return_array = [
            "status" =>  $e->getHttpStatus(),
            "type" =>  $err["type"],
            "code" =>  $err["code"],
            "param" =>  $err["param"],
            "message" =>  $err["message"],
        ];
        $return_str = json_encode($return_array);
        print_r ($return_str);

  } catch (\Stripe\Error\InvalidRequest $e) {
    // Invalid parameters were supplied to Stripe's API
    echo "<br> *** Invalid parameters *** <br>";
        $body = $e->getJsonBody();
        $err  = $body["error"];
        $return_array = [
            "status" =>  $e->getHttpStatus(),
            "type" =>  $err["type"],
            "code" =>  $err["code"],
            "param" =>  $err["param"],
            "message" =>  $err["message"],
        ];
        $return_str = json_encode($return_array);
        print_r ($return_str);

  } catch (\Stripe\Error\Authentication $e) {
    // Authentication with Stripe's API failed
    // (maybe you changed API keys recently)
    echo "<br> *** Invalid Authentication Keys *** <br>";
        $body = $e->getJsonBody();
        $err  = $body["error"];
        $return_array = [
            "status" =>  $e->getHttpStatus(),
            "type" =>  $err["type"],
            "code" =>  $err["code"],
            "param" =>  $err["param"],
            "message" =>  $err["message"],
        ];
        $return_str = json_encode($return_array);
        print_r ($return_str);

  } catch (\Stripe\Error\ApiConnection $e) {
    // Network communication with Stripe failed
    echo "<br> *** Network communication failure *** <br>";
        $body = $e->getJsonBody();
        $err  = $body["error"];
        $return_array = [
            "status" =>  $e->getHttpStatus(),
            "type" =>  $err["type"],
            "code" =>  $err["code"],
            "param" =>  $err["param"],
            "message" =>  $err["message"],
        ];
        $return_str = json_encode($return_array);
        print_r ($return_str);

  } catch (\Stripe\Error\Base $e) {
    // Display a very generic error to the user, and maybe send
    // yourself an email
    echo "<br> *** Basic Error *** <br>";
        $body = $e->getJsonBody();
        $err  = $body["error"];
        $return_array = [
            "status" =>  $e->getHttpStatus(),
            "type" =>  $err["type"],
            "code" =>  $err["code"],
            "param" =>  $err["param"],
            "message" =>  $err["message"],
        ];
        $return_str = json_encode($return_array);
        print_r ($return_str);

  } catch (Exception $e) {
    // Something else happened, completely unrelated to Stripe
    echo "<br> *** Non Stripe error *** <br>";
        $body = $e->getJsonBody();
        $err  = $body["error"];
        $return_array = [
            "status" =>  $e->getHttpStatus(),
            "type" =>  $err["type"],
            "code" =>  $err["code"],
            "param" =>  $err["param"],
            "message" =>  $err["message"],
        ];
        $return_str = json_encode($return_array);
        print_r ($return_str);

  }

}else{
  $statusMsg = "Form submission error.......";
  print_r($statusMsg);
}

mysqli_close($connection);

ob_end_flush();

?>