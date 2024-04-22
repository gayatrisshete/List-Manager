<!DOCTYPE html>
<meta charset="utf-8">
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
    $listid="";
    $update_date="";
    $description="";
    $url1 = "";
    $url2 = "";
    $api_url ="";
    $note1 = "";
    $note2 ="";
    $grd_entry = "";


    date_default_timezone_set("Asia/Kolkata");

    if(isset($_POST['actionscr']))
        $actionscr=$_POST['actionscr'];

    if(isset($_POST["update_date"]))
        $lastPinged=$_POST["update_date"];
    else
        $lastPinged=date("Y-m-d");

    if(isset($_POST['list_id']))  
        $listid=$_POST['list_id'];

    if(isset($_POST["region"]))
        $region=$_POST["region"]; 

    // Create connection
	$conn = new mysqli($servername, $username, $password,$db);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

    //$select_region_sql="SELECT region_id ,region FROM region_master";
    //$region_result = $conn->query($select_region_sql);

    $select_country_sql="SELECT country_id ,country_name FROM country_master";
    $country_result = $conn->query($select_country_sql);


    $select_userid_sql = "SELECT user_id FROM user_master u";
    $userid_result = $conn->query($select_userid_sql);
    if ($listid != "") 
    {
    $sql= "SELECT c.id, c.url1, c.url2, c.api_url, c.note1, c.note2, c.grd_entry, c.country_id, c.update_date, c.user_id, u.role
            FROM sanctionlist.criminal_checks c, user_master u, country_master cm
            WHERE c.user_id = u.user_id AND c.country_id = cm.country_id";
            

            if ($_SESSION['role'] == "admin") {
                $sql = $sql." AND u.role = '" . $_SESSION['role'] . "'";
            }
            if ($_SESSION['role'] == "user") {
                $sql= $sql." AND u.user_id = '" . $_SESSION['userid'] . "'";
            }
        
           $sql = $sql." AND c.id = ". $listid ;
            $result = $conn->query($sql);
        
        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            { 
                $url1=  $row['url1'] ;
                $url2=$row['url2'] ;
                $note1 = $row["note1"];
                $note2 =  $row["note2"];
            $api_url=$row["api_url"];
            $grd_entry=$row["grd_entry"];
                $country_id=$row["country_id"];
            }
        }
    }
$conn->close();
?>

<HTML ng-app="sactionList">
<HEAD>
	<TITLE>Sacntions</TITLE>
	<LINK type=text/css rel=Stylesheet href="css/kapiraj.css">
	</HEAD>

<BODY>
<form action="update_criminal_check.php" method="post" class="form-horizontal">
<form method="post" action="update_global_directory.php">
    <?php include("header.php"); ?>
    <div class="sidebar">
        <br/><br/><br/><?php include("sidebar.php"); ?>
    </div>
<input type="hidden" name="action" value="update"/>
<input type="hidden" name="actionscr" value="modify"/>
<input type="hidden" name="list_id" value="<?PHP echo $listid; ?>"/>
<div class="content">
    <br/><br/><br/><br/>
    <h4>Criminal Checks</h4>
        <hr/>

        <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">Authority/Domain</label>
            <div class="col-sm-4">
                <select  class="form-control" name="country_id" required>
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
        </div>

        <br></br>
        <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">URL 1:</label>
            <div class="col-sm-4">
                <input type="text" value="<?PHP echo $url1; ?>" class="form-control" name="url1" value="<?php echo $url1; ?>" required>
            </div>
        </div>

        <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">Note 1:</label>
            <div class="col-sm-4">
                <textarea value="<?PHP echo $note1; ?>" class="form-control" name="note1" rows="2" style="resize: vertical;" placeholder="Note1"><?php echo $note1; ?></textarea>
            </div>
        </div>

        <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">URL 2:</label>
            <div class="col-sm-4">
                <input type="text" value="<?PHP echo $url2; ?>" class="form-control" name="url2" value="<?php echo $url2; ?>" required>
            </div>
        </div>

        <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">Note 2:</label>
            <div class="col-sm-4">
                <textarea value="<?PHP echo $note2; ?>"  class="form-control" name="note2" rows="2" style="resize: vertical;" placeholder="Note2"><?php echo $note2; ?></textarea>
            </div>
        </div>

        <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">API URL:</label>
            <div class="col-sm-4">
                <input type="text" value="<?PHP echo $api_url; ?>" class="form-control" name="api_url" value="<?php echo $api_url; ?>" required>
            </div>
        </div>

        <br></br>
        <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">GRD Entry</label>
            <div class="col-sm-4">
                <select class="form-control" name="grd_entry">
                    <option value="Yes" <?php if ($grd_entry == "Yes") echo 'selected="selected"'; ?>>Yes</option>
                    <option value="No" <?php if ($grd_entry == "No") echo 'selected="selected"'; ?>>No</option>
                </select>
            </div>
        </div>

        <br></br>
        <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">Update Date</label>
            <div class="col-sm-4">
                <input type="date" value="<?PHP echo $update_date; ?>" class="form-control" name="update_date" id="update_date" required>  
            </div>
        </div>

        <br></br>
        <div class="form-group rowintro" align="right">
            <div class="col-sm-4">
                <button type="submit" name="create" class="btn btn-success btn-sm" title="Save Changes">
                    <span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Submit
                </button>
                <a href="home.php" class="btn btn-primary btn-sm" role="button" title="Home Page">
                    <span class="glyphicon glyphicon-home"></span>&nbsp;Home
                </a>
            </div>
            <div class="col-sm-4"></div>
            <div class="col-sm-4"></div>
        </div>
    </div>

    <br/><br/>
    <?php include("footer.php"); ?>
</form>
</body>
</html>
