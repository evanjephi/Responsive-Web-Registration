<?php
/****************************************
// ENSURES THE USER HAS ACTUALLY LOGGED IN
// IF NOT REDIRECT TO THE LOGIN PAGE HERE
 ******************************************/

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

require "includes/library.php";

// CONNECT TO DATABASE
$pdo = connectDB();

// get the contents of naughty/nice list, behavior first so we can use PDO Fetch_group to get two different arrays
$query = "SELECT behavior, name, item FROM cois3420_naughtynice_options,cois3420_naughtynice_alldata where cois3420_naughtynice_alldata.primary_choice = cois3420_naughtynice_options.ID  ORDER BY name ASC";
$stmt = $pdo->query($query);
if (!$stmt) {
    die("Something went horribly wrong");
}
//fetch_groups returns multidimensional array based on first column of query (all G  and all B)
$lists = $stmt->fetchAll(PDO::FETCH_GROUP);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
$page_title = "Naughty/Nice List";
include "includes/metadata.php";
?>
</head>

<body>
    <?php include "includes/header.php"?>
     <?php if (array_key_exists("G", $lists)): // if there are nice kids?>
			    <section>
			      <h2>Nice</h2>
			      <ol>
			      <?php foreach ($lists['G'] as $row): ?>
			        <li><?=$row['name'] . "(" . $row['item'] . ")"?></li>
			      <?php endforeach;?>
    </ol>
    </section>
    <?php
endif;

if (array_key_exists("B", $lists)): // if there are naughty kids
    ?>
			     <section>
			        <h2>Naughty</h2>
			        <ol>
			        <?php foreach ($lists['B'] as $row): ?>
			          <li><?=$row['name'] . "(" . $row['item'] . ")"?></li>
			        <?php endforeach;?>
        </ol>
    </section>
    <?php endif?>
    <?php include "includes/footer.php"?>
</body>

</html>