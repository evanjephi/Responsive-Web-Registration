<?php

include 'includes/library.php';
$pdo = connectDB();

$query = "SELECT ID, item FROM `cois3420_naughtynice_options`";
$stmt = $pdo->query($query);

$errors = array(); //declare empty array to add errors too

//get values from post or set to NULL if doesn't exist
$name = $_POST['name'] ?? null;
$gps = $_POST['gps'] ?? null;
$behavior = $_POST['status'] ?? null;
$primary = $_POST['primary'] ?? "";
$list = $_POST['list'] ?? null;

/*****************************************
 * Include library, make database connection,
 * and query for dropdown list information here
 ***********************************************/

if (isset($_POST['submit'])) { //only do this code if the form has been submitted

//     //validate user has entered a name
    //     if (!isset($name) || strlen($name) === 0) {
    //         $errors['name'] = true;
    //     }

//     //checking for lack of comma (indication of not two co-ordinates)
    //     if (strpos($gps, ",") === false) {
    //         $errors['gps'] = true;
    //     }

// //make sure the chose a character
    //     if (empty($behavior)) {
    //         $errors['behavior'] = true;
    //     }
    // //make sure they agreed to the terms
    //     if ($primary == "") {
    //         $errors['primary'] = true;
    //     }
    //validate email address
    $list = filter_var($list, FILTER_SANITIZE_STRING);

    //only do this if there weren't any errors
    if (count($errors) === 0) {

        /********************************************
         * Put the code to write to the database here
         ********************************************/

        $query = "insert into cois3420_naughtynice_alldata values (NULL, ?,?,?,?,?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $gps, $primary, $behavior, $list]);

        $query = "UPDATE `cois3420_naughtynice_options` SET request_count = request_count + 1 WHERE ID = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$primary]);

        //send the user to the thankyou page.
        header("Location:thanks.php");
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
   <?php
$page_title = "Logging Letters";
include 'includes/metadata.php';?>
  </head>
  <body>
   <?php include 'includes/header.php';?>

      <section>
        <h2>Logging 'Xmas Letters</h2>
       <form id="requestform" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
          <div>
            <label for="name">Child's Name:</label>
            <input
              id="name"
              name="name"
              type="text"
              placeholder="John Smith"
              value="<?=$name?>" />
            <span class="error <?=!isset($errors['name']) ? 'hidden' : "";?>">Please enter a Child's Name</span>
          </div>
          <div>
            <label for="gps">GPS Co-ordinates:</label>
            <input
              id="gps"
              name="gps"
              type="text"
              placeholder="(43.651890, -79.381706)"
              required
              value = "<?=$gps?>"
            />
            <span class="error <?=!isset($errors['gps']) ? 'hidden' : "";?>">Please enter a GPS location</span>
          </div>
          <fieldset>
            <legend>Behavior</legend>

            <div>
              <input id="naughty" name="status" type="radio" value="B" <?=$behavior == "B" ? 'checked' : ''?> />
              <label for="naughty">Naughty</label>
            </div>

            <div>
              <input id="nice" name="status" type="radio" value="G"  <?=$behavior == "G" ? 'checked' : ''?> />
              <label for="nice">Nice</label>
            </div>
            <span class="error <?=!isset($errors['behavior']) ? 'hidden' : "";?>">Please choose a behavior</span>
          </fieldset>

          <div>
            <label for="primary">Primary Request</label>
            <select name="primary" id="primary" class="select-css">
              <option value="">Choose One</option> <!--leave this as default-->

               <!-- Put for loop for database results here. Use the one option left below as the template.  Replace the option value, and the comparisons value in the ternary operator (the "1"s) with a php echo of the database ID, and the contents of the option (lightsaber) with a echo of the database name  -->
              <?php foreach ($stmt as $row): ?>
              <option value="<?=$row['ID']?>" <?=$primary == $row['ID'] ? 'selected' : ''?>><?=$row['item']?></option>
              <?php endforeach?>
              <!-- loop should stop here -->

            </select>
            <span class="error <?=!isset($errors['primary']) ? 'hidden' : "";?>">Please choose a primary toy request</span>
          </div>
          <div>
            <label for="list">Remaining List</label>
            <textarea name="list" id="list" cols="30" rows="10"></textarea>
          </div>
          <div>
            <button id="submit" name="submit">Save Request</button>
          </div>
        </form>
      </section>
   <?php include 'includes/footer.php';?>
  </body>
</html>
