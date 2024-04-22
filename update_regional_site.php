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
$insertupdateflag="";
$id="";
$url="";
$update_date="";
$description="";
$region="";
$organisation="";
$action="";
$actionscr="";

if(isset($_POST['action']))
    $action=$_POST['action'];

if(isset($_POST['actionscr']))
    $actionscr=$_POST['actionscr'];

if(isset($_POST['list_id']))
    $list_id=$_POST['list_id'];

if(isset($_POST['url']))
    $url=$_POST['url'];

if(isset($_POST['sitename']))    
    $sitename=$_POST['sitename'];

if(isset($_POST['update_date']))
    $update_date=$_POST['update_date'];

if(isset($_POST['description']))
    $description=$_POST['description'];

if(isset($_POST['region']))
    $region=$_POST['region'];

if(isset($_POST['organisation']))
    $organisation=$_POST['organisation'];

if(isset($_POST['visibility']))
    $visibility=$_POST['visibility'];

$conn = new mysqli($servername, $username, $password,$db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$select_region_sql="SELECT region_id,region FROM region_master";
$region_result = $conn->query($select_region_sql);

if (isset($_POST['create'])) 
{ 
$check="SELECT site_url From regional_siteof_intrest WHERE site_url='".$url."'";
$result_chk = $conn->query($check);
$checkrows=mysqli_num_rows($result_chk);

    if ($action == "update") 
    {
        if($list_id=="" && $checkrows==0){
            $sql = "INSERT INTO regional_siteof_intrest (organisation, sitename, site_url, region_id, description, user_id, update_date, visibility)
            VALUES ('".$organisation."', '".$sitename."', '".$url."', ".$region.", '".$description."', ".$_SESSION['userid'].", '".$update_date."','".$visibility."')";

            if ($conn->query($sql) === TRUE) {
                $insertupdateflag="INS";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            } 
        } 
        if($list_id!=""){
            $sql = "UPDATE regional_siteof_intrest 
                SET organisation = '$organisation', 
                    sitename = '$sitename', 
                    site_url = '$url', 
                    region_id = '$region', 
                    visibility = '$visibility',
                    description = '$description', 
                    update_date = '$update_date' 
                WHERE id = '$list_id'";

            if ($conn->query($sql) === TRUE) {
                $insertupdateflag = "INS";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } 
}
//$conn->close();
?>
<HTML ng-app="sactionList">
<HEAD>
<?php include("header.php"); ?>
<div class="sidebar">
    <br/><br/><br/><?php include("sidebar.php"); ?>
</div>
	<TITLE>Sanctions</TITLE>
	<SCRIPT language="javascript">
    var delId=0;
    function assignvar(id)
    {
        delId=id;
    }
	function update_url(id)
	{
        document.forms[0].action="create_regional_site.php";
		document.forms[0].list_id.value=id;
    	document.forms[0].method="post";
		document.forms[0].submit();
	}
	</SCRIPT>
  </HEAD>

<BODY>
<form method="post"action="update_regional_site.php">

<input type="hidden" name="list_id"/>
<input type="hidden" name="action" value="<?PHP echo $action ?>"/>
<input type="hidden" name="actionscr" value="<?PHP echo $actionscr ?>"/>
<div class="content">
  <br/><br/><br/><br/>
    <h4>Regional Sites Of Interest</h4>
    <hr/>
    <div class="row rowintro">
        <label class="col-sm-1 control-label text-primary text-right">Region</label>
        <div class="col-md-4">
            <select class="form-control" name="region" id="region">
                <option value="">Select</option>
                <option value="">All</option>
                    <?PHP	while($row = $region_result->fetch_assoc()) {
                        if($region==$row['region_id']){  ?>
                            <option value="<?PHP echo $row['region_id'];?>" class="form-control" name="region" id="region" SELECTED><?PHP echo $row['region'];?></option>
                    <?php  }else{ ?>
                            <option value="<?PHP echo $row['region_id'];?>" class="form-control" name="region" id="region"><?PHP echo $row['region'];?></option>
                    <?php }
                        }?>
            </select>
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span>&nbsp;View
        </div>
	
        <div class="col-md-6" style="text-align:right;">
            &nbsp;&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="create_regional_site.php" role="button" Title="Creates New URL"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add New</a>
            <a class="btn btn-success btn-sm" href="home.php" role="button" Title="Home Page"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a>
        </div>  
    </div>

    <?php if ($checkrows>0 && $insertupdateflag == "") { ?>
        <h5>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Data for This URL already Exist!
            </div>
        </h5>
    <?php } ?>
    <h5>
    <?PHP if($insertupdateflag == "INS"){?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong><?PHP if($list_id=="") { ?> New URL Data Inserted <?PHP }else if($_POST['action']=="delete"){ ?> URL Data Deleted <?PHP }else{ ?> URL Data Updated <?PHP } ?>
    </div>
    <?PHP } ?>
    </h5>
    
    
    <!--<div class="panel panel-default">-->
    <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-striped table-bordered ">
            <thead>
            <tr>
                <th>#</th>
                <th>Region</th>
                <th>Site Name</th>
                <th>URL</th>
                <th>Last Update</th>
                <th>Visibility</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?PHP
                    //$list_id = isset($_POST['list_id']) ? $_POST['list_id'] : '';
                    $sql = "SELECT r.id, r.site_url, r.sitename, r.update_date, r.description, r.visibility, rm.region, r.organisation
                    FROM regional_siteof_intrest r, user_master u, region_master rm
                    WHERE r.region_id = rm.region_id";
                    if ($_SESSION['role'] == "admin") {
                        $sql = $sql." AND u.role='" . $_SESSION['role'] . "'";
                    }
                    if ($_SESSION['role'] == "user") {
                        $sql = $sql." AND u.user_id='" . $_SESSION['userid'] . "'";
                    }
                    if($region!=""){
                        $sql = $sql." AND r.region_id = ".$region;
                    }
                    $sql .= " GROUP BY r.id ORDER BY rm.region";    
                    $result = $conn->query($sql);
                
                    if ($result->num_rows > 0) {
                        // output data of each row
                        $count = 1;
                        $region_prev = "";
                        while ($row = $result->fetch_assoc()) {
                            $url_length = strlen($row["site_url"]);
                            $url = substr($row["site_url"], 0, 100);
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <?php if ($region_prev != $row["region"]) { ?>
                                    <td><b><?php echo $row["region"]; ?></b></td>
                                <?php } else { ?>
                                    <td></td>
                                <?php } ?> 
                                <td><?php echo $row["sitename"]; ?></td>

                                <?php if ($url_length >= 100) { ?>
                                    <td><?php echo $url . "..."; ?></td>
                                <?php } else { ?>
                                    <td><?php echo $url; ?></td>
                                <?php } ?>
                                <td><?PHP echo $row["update_date"]; ?></td>
                                <td><?php echo $row["visibility"]; ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" title="Modify a Sanction" onclick="update_url(<?php echo $row['id']; ?>)">
                                        <span class="glyphicon glyphicon-pencil"></span>&nbsp;Update
                                    </button>
                                </td>
                                

                            </tr>
                        <?php
                            $region_prev = $row["region"];
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7">No Records</td>
                        </tr>
                    <?php
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
  </div>
</div>
</BODY>

