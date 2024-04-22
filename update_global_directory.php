<!DOCTYPE html>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/sanction.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/angular.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?PHP include "connection.php"; ?>
<?PHP
session_start();
if($_SESSION['user']=="")
{
	$headerval='Location:endSession.php';
	header($headerval);
}
    $action="";
    $actionscr="";
    $id = "";
    $authority_domain = "";
    $regulatory_authority_central_bank = "";
    $AIact_policy = "";
    $antimoney_laundering = "";
    $bribery = "";
    $credit_cards = "";
    $cloud_policy = "";
    $data_privacy = "";
    $fraud = "";
    $sanctions = "";
    $terrorism = "";
    $other = "";
    $user_id ="";
     $region_id = "";
    $country_id = "";
    $update_date= "";

    date_default_timezone_set("Asia/Kolkata");
    $conn = new mysqli($servername, $username, $password, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if(isset($_SESSION['userid']))
    echo $user_id = $_SESSION["userid"];
   
    if(isset($_POST['action']))
    $action=$_POST['action'];
    
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $listid = $_POST["id"];
        $id=$_POST['id'];
        $authority_domain = $_POST["authority_domain"];
        $regulatory_authority_central_bank = $_POST["regulatory_authority_central_bank"];
        $AIact_policy = $_POST["ai_act_policy"];
        $antimoney_laundering = $_POST["antimoney_laundering"];
        $bribery = $_POST["bribery"];
        $credit_cards = $_POST["credit_cards"];
        $cloud_policy = $_POST["cloud_policy"];
        $data_privacy = $_POST["data_privacy"];
        $fraud = $_POST["fraud"];
        $sanctions = $_POST["sanctions"];
        $terrorism = $_POST["terrorism"];
        $other = $_POST["other"];
        $region_id = $_POST["region"];
        $country_id = $_POST["country_id"];; 
        $update_date = $_POST["update_date"];

        if ($action == "update") {
            if ($id=="") {
                // Insert new record
                $sql = "INSERT INTO global_directory (authority_domain, regulatory_authority_central_bank, AIact_policy,
                    antimoney_laundering, bribery, credit_cards, cloud_policy, data_privacy, fraud, sanctions, terrorism, other,
                    region_id, country_id, user_id, update_date) VALUES (
                    '$authority_domain', '$regulatory_authority_central_bank', '$AIact_policy', '$antimoney_laundering',
                    '$bribery', '$credit_cards', '$cloud_policy', '$data_privacy', '$fraud', '$sanctions', '$terrorism',
                    '$other', $region_id, $country_id, $user_id, '$update_date'
                )";
            } else {
                // Update existing record
                $sql = "UPDATE global_directory SET 
                    authority_domain = '$authority_domain',
                    regulatory_authority_central_bank = '$regulatory_authority_central_bank',
                    AIact_policy = '$AIact_policy',
                    antimoney_laundering = '$antimoney_laundering',
                    bribery = '$bribery',
                    credit_cards = '$credit_cards',
                    cloud_policy = '$cloud_policy',
                    data_privacy = '$data_privacy',
                    fraud = '$fraud',
                    sanctions = '$sanctions',
                    terrorism = '$terrorism',
                    other = '$other',
                    region_id = $region_id,
                    country_id = $country_id,
                    user_id = $user_id,
                    update_date = '$update_date'
                WHERE id = $listid";
                $queryy = "SELECT * FROM global_directory";
            }
            
        if ($conn->query($sql) === TRUE) {
            $insertupdateflag = "INS";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... your existing code ...

    if ($action == "delete" && !empty($id)) {
        // Delete record
        $delete_sql = "DELETE FROM global_directory WHERE id = $id";

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
    <title>Sanctions</title>
    
    <script language="javascript">
        var delId = 0;

        function assignvar(id) {
            delId = id;
        }

        function update_url(id) { 
    alert(listid);
    document.forms[0].action = "global_directory.php";
    document.forms[0].listid.value = id;
    document.forms[0].method = "post";
    document.forms[0].submit();
}

        function delete_url(id)
	{      alert(id);
        document.forms[0].action="update_global_directory.php";
        document.forms[0].action.value="delete";
        document.forms[0].id.value=delId;
		document.forms[0].method="post";
		document.forms[0].submit();
    }

    </script>
</head>
<body>
    <form method="post">
        <?php include("header.php"); ?>
        <div class="sidebar">
            <br/><br/><br/><?php include("sidebar.php"); ?>
        </div>
        <input type="hidden" name="id"/>
        <input type="hidden" name="action" value="<?php echo $action ?>"/>
        <input type="hidden" name="actionscr" value="<?php echo $actionscr ?>"/>
        <div class="content">
            <br/><br/><br/><br/>
            <h4>global directory</h4>
            <hr/>
            <h5>
            <?PHP if($insertupdateflag=="INS"){?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong><?PHP if($id=="") { ?> New URL Data Inserted <?PHP }else if($_POST['action']=="delete"){ ?> URL Data Deleted <?PHP }else{ ?> URL Data Updated <?PHP } ?>
    </div>
    <?PHP } ?>
    <div class="row">
        <div class="col-md-10">
            &nbsp;&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="global_directory.php" role="button" Title="Creates New URL"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add New</a>
            <a class="btn btn-success btn-sm" href="home.php" role="button" Title="Home Page"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a>
        </div>  
            </div>
                <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Authority Domain</th>
                                <th>regulatory_authority_central_bank</th>
                                <th>antimoney_laundering</th>
                                <th>AIact_policy</th>
                                <th>bribery</th>
                                <th>credit_cards</th>
                                <th>cloud_policy</th>
                                <th>data_privacy</th>
                                <th>fraud</th>
                                <th>sanctions</th>
                                <th>terrorism</th>
                                <th>other</th>
                                <th>region_id</th>
                                <th>country_id</th>
                                <th>user_id</th>
                                <th>update_date</th>
                                <th>Action</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Create connection
                            $conn = new mysqli($servername, $username, $password, $db);
                            // Check connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT id, authority_domain, regulatory_authority_central_bank, AIact_policy,
                                    antimoney_laundering, bribery, credit_cards, cloud_policy, data_privacy,
                                    fraud, sanctions, terrorism, other, region_id, country_id, user_id, update_date 
                                    FROM global_directory g, user_master u, region_master rm
                                    WHERE g.user_id = u.userid AND g.region_id = rm.region_id";

                            if ($_SESSION['role'] == "admin") {
                                $sql = $sql . " AND u.role='" . $_SESSION['role'] . "' ";
                            }
                            if ($_SESSION['role'] == "user") {
                                $sql = $sql . " AND u.user_id='" . $_SESSION['userid'] . "' ";
                            }
                            if ($actionscr == "suspend") {
                                $sql = $sql . " AND status<>'I'";
                            }
                            $query = "SELECT * FROM global_directory";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row["id"] . "</td>";
                                    echo "<td>" . $row["authority_domain"] . "</td>";
                                    echo "<td>" . $row["regulatory_authority_central_bank"] . "</td>";
                                    echo "<td>" . $row["antimoney_laundering"] . "</td>";
                                    echo "<td>" . $row["AIact_policy"] . "</td>";
                                    echo "<td>" . $row["bribery"] . "</td>";
                                    echo "<td>" . $row["credit_cards"] . "</td>";
                                    echo "<td>" . $row["cloud_policy"] . "</td>";
                                    echo "<td>" . $row["data_privacy"] . "</td>";
                                    echo "<td>" . $row["fraud"] . "</td>";
                                    echo "<td>" . $row["sanctions"] . "</td>"; // Corrected column name
                                    echo "<td>" . $row["terrorism"] . "</td>";
                                    echo "<td>" . $row["other"] . "</td>";
                                    echo "<td>" . $row["region_id"] . "</td>";
                                    echo "<td>" . $row["country_id"] . "</td>";
                                    echo "<td>" . $row["user_id"] . "</td>";
                                    echo "<td>" . $row["update_date"] . "</td>";
                                    
                                    //echo "<td><a href='#' onclick='edit_url(" . json_encode($row) . ")'>Edit</a></td>";
                                    echo "<td>" . '<button type="button" class="btn btn-primary btn-sm" title="Modify a Sanction" onclick="javascript:update_url(' . $row["id"] . ')"><span class="glyphicon glyphicon-pencil"></span>&nbsp;Update</button></td>';
                                     echo "<td><a href='#' onclick='delete_url(" . $row['id'] . ")'>delete</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No records found</td></tr>";
                            }

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br/><br/>    
            <?php include("footer.php"); ?>      
        </form>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        Are you sure about Suspending the record ?
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
