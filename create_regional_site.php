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
    $insertupdateflag="";
    $action="";
    $listid="";
    $url=""; 
    $sitename="";
    $description="";
    $region_id="";
    $visibility = "";
    $organisation=""; 
    $actionscr="";

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

	$conn = new mysqli($servername, $username, $password,$db);

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
    
    $select_region_sql="SELECT region_id,region FROM region_master";
    $region_result = $conn->query($select_region_sql);

    $select_userid_sql = "SELECT user_id FROM user_master u";
    $userid_result = $conn->query($select_userid_sql);

    if($listid!="")
    {
        $sql ="SELECT r.id, r.organisation, r.site_url,r.sitename, r.region_id, r.description, r.user_id, r.visibility, r.update_date, rm.region
        FROM regional_siteof_intrest r, region_master rm, user_master u
        WHERE r.region_id = rm.region_id
        AND r.id = $listid";

             if($_SESSION['role'] == "admin")
             {
                 $sql=$sql." AND u.role='".$_SESSION['role']."' ";
             }
             if ($_SESSION['role'] == "user") {
                $sql= $sql." AND u.user_id = '" . $_SESSION['userid'] . "'";
            }
        $sql=$sql." AND id=".$listid;
        $result = $conn->query($sql);

     if ($result->num_rows > 0)
     {
        while($row = $result->fetch_assoc())
        { 
            $organisation = $row["organisation"];
            $sitename = $row["sitename"];
            $url = $row["site_url"];
            $region_id = $row["region_id"];
            $description = $row["description"];
            $visibility = $row["visibility"];
            $region=$row["region_id"];
            $description = $row["description"];
            $update_date = $row["update_date"];
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
<?php include("header.php"); ?>
<div class="sidebar">
    <br/><br/><br/><?php include("sidebar.php"); ?>
</div>
<BODY>
 <form action="update_regional_site.php" method="post" class="form-horizontal">
    
        <input type="hidden" name="action" value="update"/>
        <input type="hidden" name="actionscr" value="modify"/>
        <input type="hidden" name="list_id" value="<?php echo $listid; ?>"/>
        <div class="content">
            <br/><br/><br/><br/>
            <h4>Regional Sites Of Interest</h4>
            <hr/>
            <div class="form-group">
                <label class="col-sm-2 control-label text-primary">Region</label>
                <div class="col-sm-6">
                    <select class="form-control" name="region" id="region" required>   
                        <option value="">Select</option>
                        <?php 
                        while($row_region = $region_result->fetch_assoc()) {
                            $selected = ($region_id == $row_region['region_id']) ? "selected" : "";
                        ?>
                        <option value="<?php echo $row_region['region_id'];?>" <?php echo $selected; ?>><?php echo $row_region['region'];?></option>
                        <?php } ?>
                    </select>
                </div>   
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label text-primary">Organisation</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $organisation; ?>" class="form-control"  name="organisation" id="organisation" placeholder="Organisation">
                </div>   
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label text-primary">Site Name</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $sitename; ?>" class="form-control"  name="sitename" id="sitename" placeholder="site Name">
                </div>   
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label text-primary">URL</label>
                <div class="col-sm-6">
                    <input type="text" value="<?php echo $url; ?>" class="form-control" name="url" id="url" placeholder="URL" required>
                </div>   
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label text-primary">Description</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="description" id="description" placeholder="Description"><?php echo $description; ?></textarea>
                </div>   
            </div>
            <div class="form-group rowintro">
            <label class="col-sm-2 control-label text-primary">Visibility</label>
            <div class="col-sm-6">
                <select class="form-control" name="visibility" required>
                    <option value="">Select</option>
                    <option value="Y" <?php if ($visibility == "Y") echo 'selected="selected"'; ?>>Yes</option>
                    <option value="N" <?php if ($visibility == "N") echo 'selected="selected"'; ?>>No</option>
                </select>
            </div>
            <div class="col-sm-4"></div>
            </div>
            <br/>
            <div class="form-group">
                <label class="col-sm-2 control-label text-primary">Last Updated</label>
                <div class="col-sm-6">
                    <input type="date" value="<?php echo $update_date; ?>" class="form-control" name="update_date" id="update_date" placeholder="Last Updated" required>
                </div>   
            </div>
            <div class="col-sm-2"></div>
            <div class="col-sm-6" align="right">
                <button type="submit"  name="create" class="btn btn-success btn-sm" " Title="Save Changes"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Submit</button>
                <a href="home.php" class="btn btn-primary btn-sm" role="button" Title="Home Page"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a>
            </div>
        </div>
        <br/><br/>    
        <?php include("footer.php"); ?>
  </form>
</BODY>