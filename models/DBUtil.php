<?php
##
##  *** Utility Functions
##
  // Show all matching records in a  <table>
  function LUhdbsDB($conn,$query)
  {
      $allrec = [];

	 // Run the query on the server
      if (!($result = mysqli_query($conn, $query)))
      {
        echo("Error description: " . mysqli_error($conn));
      } else {
        //
		 // Find out how many rows are available
		 //$recCount = @ mysqli_num_rows($result);
		   while ($row = @ mysqli_fetch_array($result))
   			{
        // Print one row of results
        $onerec = [];
        $onerec["mid"] = $row["Member_id"];
        $onerec["cat"] = $row["Category"];
        $onerec["fn"] = $row["F_Name"];
        $onerec["ln"] = $row["L_Name"];
        $onerec["spn"] = $row["Sp_FName"];
        $onerec["city"] = $row["City"];
        $onerec["zip"] = $row["Zip"];
        $allrec[] = $onerec;
        }
	  }
  	return ($allrec);

  } // end of function
  //
  // create search criteria
  function create_crit($fn,$ln,$zip)
  {
    $s_fn = trim($fn);
    $s_ln = trim($ln);
    $s_zi = trim($zip);
  
    
    // Initialize
	  $a_cri = "";
	  $b_cri = "";
	  $c_cri = "";
	  $comb_cri = "";
	  //
	  if(!empty($s_fn)) $a_cri = "(F_Name like '%".$s_fn."%' OR Sp_FName like '%".$s_fn."%')";
	  if(!empty($s_ln)) $b_cri = "L_Name like '%".$s_ln."%'";
	  if(!empty($s_zi)) $c_cri = "Zip like '%".$s_zi."%'";
	  //
	  if(!empty($a_cri)) $comb_cri = $a_cri;
	  if(!empty($b_cri) && !empty($comb_cri)) $comb_cri = $comb_cri." AND ".$b_cri;
	  if(!empty($b_cri) &&  empty($comb_cri)) $comb_cri = $b_cri;
	  if(!empty($c_cri) && !empty($comb_cri)) $comb_cri = $comb_cri." AND ".$c_cri;
	  if(!empty($c_cri) &&  empty($comb_cri)) $comb_cri = $c_cri;
	  //
	  return ($comb_cri);

  }// end of function

  // Query to check if MID in DB
  function isIDinDB($conn,$mid)
  {

      $r_flag = false;
      //
      $query = "SELECT Member_id FROM details WHERE Member_id = '{$mid}'" ;
      if (!($result = mysqli_query($conn, $query)))
      {
        echo("Error description: " . mysqli_error($conn));
      } else {
        // Find out how many rows are available
        $rowsCnt = mysqli_num_rows($result);
        if ($rowsCnt > 0) $r_flag = true;
      }
      //
      return($r_flag);
  }
  // Query to get member details for a mid
  function getMemberDetails($conn,$mid)
  {

      $rmrec = [];
      $rmrec["cat"]="GD";
      $rmrec["fn"]="";
      $rmrec["ln"]="";
      $rmrec["addr1"]="";
      $rmrec["addr2"]="";
      $rmrec["city"]="";
      $rmrec["state"]="";
      $rmrec["zip"]="";
      //
      $query = "SELECT * FROM details WHERE Member_id = '{$mid}'" ;
      if (!($result = mysqli_query($conn, $query)))
      {
        echo("Error description: " . mysqli_error($conn));
      } else {
        // Fetch fields from member record
        $row = mysqli_fetch_array($result);
        //
        $rmrec["cat"]=$row["Category"];
        $rmrec["fn"]=$row["F_Name"];
        $rmrec["ln"]=$row["L_Name"];
        $rmrec["addr1"]=$row["Address1"];
        $rmrec["addr2"]=$row["Address2"];
        $rmrec["city"]=$row["City"];
        $rmrec["state"]=$row["State"];
        $rmrec["zip"]=$row["Zip"];
        //
      }
       //
      return($rmrec);
  }

  // Query to get and update Order ID
  function getOID($conn,$incOID)
  {

      $r_oid = 0;
      $query = "LOCK TABLES idnumbers WRITE";
      if (!mysqli_query($conn, $query))
      {
      echo("Error description: " . mysqli_error($conn));
      }
      //
      $query = "SELECT * FROM idnumbers" ;
      if (!($result = mysqli_query($conn, $query)))
      {
        echo("Error description: " . mysqli_error($conn));
      } else {
        $row = mysqli_fetch_array($result);
        $r_oid=$row["oid"] + $incOID;
      }
      //
      $query = "UPDATE idnumbers SET oid = oid + 1";
      if (!($result = mysqli_query($conn, $query)))
      {
        echo("Error description: " . mysqli_error($conn));
      }  //
      $query = "UNLOCK TABLES";
      if (!mysqli_query($conn, $query))
      {
        echo("Error description: " . mysqli_error($conn));
      }
      return($r_oid);
  }


  // Query to get and update Member ID
  function getMID($conn)
  {

      $r_mid = 0;
      $query = "LOCK TABLES idnumbers WRITE";
      if (!mysqli_query($conn, $query))
      {
      echo("Error description: " . mysqli_error($conn));
      }
      //
      $query = "SELECT * FROM idnumbers" ;
      if (!($result = mysqli_query($conn, $query)))
      {
        echo("Error description: " . mysqli_error($conn));
      } else {
        $row = mysqli_fetch_array($result);
        $r_mid=$row["reg_uid"];
      }
      //
      $query = "UPDATE idnumbers SET reg_uid = reg_uid + 1";
      if (!($result = mysqli_query($conn, $query)))
      {
        echo("Error description: " . mysqli_error($conn));
      }  //
      $query = "UNLOCK TABLES";
      if (!mysqli_query($conn, $query))
      {
        echo("Error description: " . mysqli_error($conn));
      }
      return($r_mid);
  }

  // Query to Insert in tickets table all items
  function insertDonation($conn,$mid,$oid,$customer,$amount,$txn_id,$trn_dt,$status)
  {

    $fname = $customer["fname"];
    $lname = $customer["lname"];
    $name = $fname." ".$lname;
    $email = $customer["email"];
    $ins = false;

     if($mid == 0) {
      $mid = getMID($conn);
      $addr1 = $customer["addr1"];
      $addr2 = $customer["addr2"];
      $city = $customer["city"];
      $state = $customer["state"];
      $zip = $customer["zip"];
      $ins = true;
     }
     $pay_date = date("Y-m-d",$trn_dt);
     $txn_date = date("Y-m-d H:i:s",$trn_dt);
     //$numitems = count($items);

     $rcode = array_fill(0, 2, 0);
     $rcode[0] = $mid;

     //insert member details data into the database (for new donor)
     if($ins){
      $sqlQuery = "INSERT INTO details(Member_id,Category,F_Name,L_Name,Address1,Address2,City,State,Zip,Email,CreatedOn) VALUES ";
      $sqlQuery = $sqlQuery."('{$mid}','GD','{$fname}','{$lname}','{$addr1}','{$addr2}','{$city}','{$state}','{$zip}','{$email}', '{$txn_date}');";
      // Run the query on the server
       if (!mysqli_query($conn, $sqlQuery)) {
            $rcode[0] = 0;
            echo("Error description: " . mysqli_error($conn));
          } 

     }
     // insert into payment details table
     $query = "INSERT INTO puja_payment_detail (reg_uid, oid, pay_mode, pay_type, pay_for, pay_date, cc_name, amount, status, cc_ref_no, created_on) VALUES ('{$mid}', '{$oid}', 'CC', 'DONATION', 'DONATE / Parking Lot', '{$pay_date}', '{$name}', '{$amount}', '{$status}', '{$txn_id}', '{$txn_date}')" ;
     // Run the query on the server
      if (!mysqli_query($conn, $query)) {
        echo("Error description: " . mysqli_error($conn));
      } else {
        $rcode[1] = 1;
      }

      return ($rcode);
  }

  // Query to Insert in tickets table all items
  function insertItems($conn,$oid,$customer,$items,$amount,$txn_id,$trn_dt,$status)
  {

     $name = $customer["name"];
     $email = $customer["email"];
     $city = $customer["city"];
     $state = $customer["state"];
     $zip = $customer["zip"];
     $pay_date = date("Y-m-d",$trn_dt);
     $txn_date = date("Y-m-d H:i:s",$trn_dt);
     $numitems = count($items);

     $rcode = array_fill(0, 2, 0);

     //insert tansaction data into the database
      $sqlQuery = "INSERT INTO tickets(oid,name,email,city,state,zip,amount,pay_date,txn_id,status,created_on,item_name,item_number,item_cost) VALUES ";
      if (isset($items[0])){    //at least one item

        for($i=0; $i<$numitems; $i++) {

                if ($i == $numitems-1){
                    $sqlQuery = $sqlQuery."('{$oid}','{$name}','{$email}','{$city}','{$state}','{$zip}','{$amount}','{$pay_date}','{$txn_id}','{$status}','{$txn_date}','{$items[$i][0]}', '{$items[$i][1]}', '{$items[$i][2]}');";
                }else{
                    $sqlQuery = $sqlQuery."('{$oid}','{$name}','{$email}','{$city}','{$state}','{$zip}','{$amount}','{$pay_date}','{$txn_id}','{$status}','{$txn_date}','{$items[$i][0]}', '{$items[$i][1]}', '{$items[$i][2]}'),";
                }

        }
        //
        // Run the query on the server
        if (!mysqli_query($conn, $sqlQuery)) {
            echo("Error description: " . mysqli_error($conn));
        } else {
          $rcode[0] = 1;
        }


      }
      // insert into payment details table
      $query = "INSERT INTO puja_payment_detail (reg_uid, oid, pay_mode, pay_type, pay_for, pay_date, cc_name, amount, status, cc_ref_no, created_on) VALUES (99999, '{$oid}', 'CC', 'OTHER', 'TICKET / Drama', '{$pay_date}', '{$name}', '{$amount}', '{$status}', '{$txn_id}', '{$txn_date}')" ;
      // Run the query on the server
      if (!mysqli_query($conn, $query)) {
        echo("Error description: " . mysqli_error($conn));
      } else {
        $rcode[1] = 1;
      }

      return ($rcode);
  }

?>