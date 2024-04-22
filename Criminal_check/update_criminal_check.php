<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/sanction.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/angular.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php 
include "connection.php";
session_start();

if ($_SESSION['user'] == "") {
    $headerval = 'Location:endSession.php';
    header($headerval);
}
$id= "";
$url1 = "";
$url2 = "";
$note1 = "";
$note2 = "";
$api_url = "";
$update_date="";
$insertupdateflag = "";
$action="";
$actionscr="";


if (isset($_POST['action']))
    $action = $_POST['action'];

if (isset($_POST['list_id']))
    $list_id = $_POST['list_id'];

if (isset($_POST["country"]))
    $country_id = $_POST["country"];

    if (isset($_POST["update_date"]))
    $update_date = $_POST["update_date"];

    if(isset($_SESSION['userid']))
echo $user_id = $_SESSION["userid"];


if(isset($_POST['id']))
    $id = $_POST['id'];
if(isset($_POST['url1']))
    $url1 = $_POST['url1'];
if(isset($_POST['url2']))
    $url2 = $_POST['url2'];
if(isset($_POST['note1']))
    $note1 = $_POST['note1'];
if(isset($_POST['note2']))
    $note2 = $_POST['note2'];
if(isset($_POST['api_url']))
    $api_url = $_POST['api_url'];

date_default_timezone_set("Asia/Kolkata");

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$select_country_sql = "SELECT country_id, country_name FROM country_master";
$country_result = $conn->query($select_country_sql);

$select_userid_sql = "SELECT user_id FROM user_master u";
$userid_result = $conn->query($select_userid_sql);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handling CRUD operations
    if (isset($_POST['create'])) {
        $country_id = $_POST['country_id'];
        $update_date = $_POST['update_date'];
        $url1 = $_POST['url1'];
        $url2 = $_POST['url2'];
        $api_url = $_POST['api_url'];
        $note1 = $_POST['note1'];
        $note2 = $_POST['note2'];
        $update_date=$_POST['update_date'];
        $grd_entry = $_POST['grd_entry'];

        if ($action == "update") {
            if ($list_id!=="") {
                //update records
                $sql = "UPDATE criminal_checks 
                        SET url1 = '$url1', 
                            url2 = '$url2', 
                            note1 = '$note1', 
                            note2 = '$note2', 
                            api_url = '$api_url', 
                            update_date = '$update_date', 
                            user_id = {$_SESSION['userid']} 
                        WHERE id = $list_id";
            } else {
                // Insert new record
                $sql = "INSERT INTO criminal_checks (url1, url2, api_url, note1, note2, grd_entry, country_id, update_date, user_id) 
                        VALUES ('$url1', '$url2', '$api_url', '$note1', '$note2', '$grd_entry', $country_id, '$update_date', {$_SESSION['userid']})";
            }

            if ($conn->query($sql) === TRUE) {
                $insertupdateflag = "INS";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
 // Delete record
    if ($action == "delete" && !empty($list_id)) {
       
        $delete_sql = "DELETE FROM criminal_checks WHERE id = $list_id";

        if ($conn->query($delete_sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html ng-app="dairyApp">
<head>
    <title>Criminal Checks</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/sanction.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/angular.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   

    <script language="javascript">
        function update_url(id) 
        {
        alert(id);
        document.forms[0].action = "create_criminal_check.php"; // Redirects to create page
        document.forms[0].list_id.value = id;
        document.forms[0].action.value = "update";
        document.forms[0].method = "post";
        document.forms[0].submit();
       }


        function delete_url(id) {
            if(confirm("Are you sure about Suspending the record?")) {
                document.forms[0].action = "update_criminal_check.php";
                document.forms[0].list_id.value = id;
                document.forms[0].action.value = "delete";
                document.forms[0].method = "post";
                document.forms[0].submit();
            }
        }
    </script>
</head>
        <body>
    <form method="post" action="update_criminal_check.php">
    <?php include("header.php"); ?>
    <div class="sidebar">
        <br/><br/><br/><?php include("sidebar.php"); ?>
    </div>
    
</div>
        </div>
        <input type="hidden" name="list_id" />
        <input type="hidden" name="action" value="<?php echo $action ?>" />
        <input type="hidden" name="actionscr" value="<?php echo $actionscr ?>" />
        <input type="hidden" name="region" value="<?php echo $_POST["country"]; ?>" />
        <div class="content">
            <br/><br/><br/><br/>
            <h4>Criminal Check</h4>
            <hr/>
            <h5>
                <?php if ($insertupdateflag == "INS") { ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Success!</strong><?php if ($list_id == "") { ?> New URL Data Inserted <?php } else if ($_POST['action'] == "delete") { ?> URL Data Deleted <?php } else { ?> URL Data Updated <?php } ?>
                    </div>
                <?php } ?>
                <div class="row rowintro">

                    <label class="col-sm-1 control-label text-primary">Authority</label>
                    <div class="col-md-4">
                        <select class="form-control" name="country" id="country" required>
                            <option value="">Select Country</option>
                            <?php
                            while ($row_country = $country_result->fetch_assoc()) {
                                $selected = ($country_id == $row_country['country_id']) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $row_country['country_id']; ?>" <?php echo $selected; ?>>
                                    <?php echo $row_country['country_name']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span>&nbsp;View
                    </div>
                    <div class="col-md-6" style="text-align:right;">
                        &nbsp;&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="create_criminal_check.php" role="button" Title="Creates New URL"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add New</a>
                        <a class="btn btn-success btn-sm" href="home.php" role="button" Title="Home Page"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a>
                    </div>
                </div>
                <?php if ($country_id != "") { ?>
                    <div class="">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">SrNo</th>
                                        <th width="15%">URL1</th>
                                        <th width="25%">URL2</th>
                                        <th width="20%"> API URL</th>
                                        <th width="10%">Update_date</th>
                                        <th width="10%">grd entry</th>
                                        <th width="15%">Action</th>
                                        <th width="15%">delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                 if ($country_id != "") {
                                                $sql = "SELECT id, url1, url2, api_url, note1, note2, grd_entry, country_id, update_date, user_id FROM criminal_checks WHERE country_id = $country_id";

                                                $result = $conn->query($sql);

                                                if ($result->num_rows > 0) {
                                                    $count = 1;
                                                    while ($row = $result->fetch_assoc()) {
                                                        
                                                        $url_length1 = strlen($row["url1"]);
                                                        $url1 = substr($row["url1"], 0, 100);

                                                        $url_length1 = strlen($row["url2"]);
                                                        $url2 = substr($row["url2"], 0, 100);
                                                        if (!empty($row["url1"]) || !empty($row["url2"])) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $count++; ?></td>
                                                                <td>
                                                                    <?php if ($url_length1 >= 100) { ?>
                                                                        <a href="#" onclick="" title="Click Here For More Information" id="btn"><?php echo $url1 . "..."; ?></a>
                                                                    <?php } else { ?>
                                                                        <a href="#" onclick="" title="Click Here For More Information" id="btn"><?php echo $url1; ?></a>
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($url_length1 >= 100) { ?>
                                                                        <a href="#" onclick="" title="Click Here For More Information" id="btn"><?php echo $url2 . "..."; ?></a>
                                                                    <?php } else { ?>
                                                                        <a href="#" onclick="" title="Click Here For More Information" id="btn"><?php echo $url2; ?></a>
                                                                    <?php } ?>
                                                                </td>
                                                                 <td>
                                                                 <?php echo $row["api_url"]; ?>
                                                                 </td>
                                                                <td><?php echo $row["update_date"]; ?></td>
                                                                <td><?php echo $row["grd_entry"]; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-primary btn-sm" title="Modify a Sanction" onclick="update_url(<?php echo $row['id']; ?>)">
                                                                        <span class="glyphicon glyphicon-pencil"></span>&nbsp;Update
                                                                    </button>
                                                                </td>
                                                                <td>
                                                                    <a href="#" onclick="delete_url(<?php echo $row['id']; ?>)">delete</a>
                                                                </td>


                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan='7'>No records found</td>
                                                    </tr>
                                                    <?php
                                                }
                                                $conn->close();
                                            }
                                            ?>
                                 </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <br/><br/>
            <?php include("footer.php"); ?>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Are you sure about Suspending the record?
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn-sm btn-danger" onclick="javascript:delete_url()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
