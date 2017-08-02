<?php

    // Here is my hypothetical bug fix I created for bonus points :) hopefully there isn't an actual bug to fix
    // Here is another bug fix, the last one did not appear on Github. Probably because I pushed after merging the branches?

    //check for invalid parameters

    if( !isset($_GET['offset'])){
      invalidParameters("missing offset parameter");
    }

    if( !isset($_GET['limit'])){
      invalidParameters("missing limit parameter");
    }

    if( !is_numeric($_GET['offset'])){
      invalidParameters("offset parameter must be numeric");
    }

    if( !is_numeric($_GET['limit'])){
      invalidParameters("limit parameter must be numeric");
    }

    if( $_GET['offset'] < 0){
      invalidParameters("offset must be a positive value");
    }

    if($_GET['limit'] <= 0){
      invalidParameters("limit parameter must be greater than 0");
    }

    //handle request

    if(isset($_GET['request'])){

      switch ($_GET['request']) {
          case "person":
              createJSON("person.csv");
              break;
          case "item":
              createJSON("items.csv");
              break;
          default:
              invalidParameters("invalid request parameter - request must equal 'person' or 'item'");
      }
    }else{
      invalidParameters("missing request parameter");
    }

    function createJSON($whichFile){
      $finalJSON = array();
      $row = 0;
      if (($handle = fopen($whichFile, "r")) !== FALSE) {
          while (($data = fgetcsv($handle, 30, ",")) !== FALSE) {
              if($row == 0){
                $keys = $data;
              }

              if($row > $_GET['offset'] && $row <= ($_GET['offset']+$_GET['limit'])){
                $jsonArray = array();

                for($i=0; $i < count($data); $i++){
                  $jsonArray[$keys[$i]] = is_numeric($data[$i]) ? intval($data[$i]) : $data[$i];
                }
                array_push($finalJSON, $jsonArray);
              }
              $row++;
          }

          if($_GET['offset'] > $row){
            invalidParameters("offset is out of range");
          }

          fclose($handle);

      }

      echo json_encode($finalJSON);
    }






    function invalidParameters($errorType){
        exit("Invalid parameters: " . $errorType);
    }
?>
