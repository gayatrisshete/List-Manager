<!DOCTYPE html>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/sanction.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/sanction.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?PHP include "connection.php"; ?>
<?PHP
session_start();
$actionscr="";
if($_SESSION['user']=="")
{
	$headerval='Location:endSession.php';
	header($headerval);
}

$region="";
if(isset($_POST["region"]))
    $region=$_POST["region"];

if(isset($_POST['actionscr']))
    $actionscr=$_POST['actionscr'];

// Create connection
$conn = new mysqli($servername, $username, $password,$db);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$select_region_sql="SELECT region_id,region FROM region_master";
$region_result = $conn->query($select_region_sql);

?>
<HTML ng-app="sactionList">
<HEAD>
    <TITLE>Sanctions</TITLE>
    <SCRIPT>
        function listDetails(divid){
           // alert(list_id)
            var x = document.getElementById("trDiv"+divid);
            if (x.style.display === "none") {
                x.style.display = "table-row";
            } else {
                x.style.display = "none";
            }
        }
    </SCRIPT>
</HEAD>

<BODY>
<form method="POST" action="view_regional_site.php">
<?php include("header.php"); ?>
<div class="sidebar">
    <br/><br/><br/><?php include("sidebar.php"); ?>
</div>
<input type="hidden" name="region" value="<?php echo $_POST["region"]; ?>"/>
<input type="hidden" name="actionscr" value="<?PHP echo $actionscr; ?>"/>
<div class="content">
    <br/><br/><br/><br/>
    <h4>View Regional Site of Intrest</h4>
    <hr/>
    <div class="row">
        <label class="col-sm-1 control-label text-primary text-right">Region</label>
        <div class="col-sm-4">
        <select class="form-control" name="region" id="region">
            <option value="">Select</option>
                <?PHP	while($row = $region_result->fetch_assoc()) {
                    if($region==$row['region_id']){  ?>
                        <option value="<?PHP echo $row['region_id'];?>" class="form-control" name="region" id="region" SELECTED><?PHP echo $row['region'];?></option>
                <?php  }else{ ?>
                        <option value="<?PHP echo $row['region_id'];?>" class="form-control" name="region" id="region"><?PHP echo $row['region'];?></option>
                <?php }
                    }?>
            </select>
        </div>
        <div class="col-sm-6">
        <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span>&nbsp;Search
        </div>
	</div>
    <br/>
    <!--<div class="panel panel-default ">-->
    <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>     
                <th>#</th>
                <th>Region</th>
                <th>Site Name</th>
                <th>URL</th>
                <th>Last Update</th>
                <th>Active</th>    
            </tr>
            </thead>
            <tbody>
            <?PHP
                    $conn = new mysqli($servername, $username, $password, $db);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                     $sql =  "SELECT R.id, R.URL, R.sitename, R.update_date, R.description, R.visibility, rm.region, R.organisation
                    FROM Regional_siteof_intrest R, region_master rm
                    WHERE R.region_id = rm.region_id";

                    if($region!=""){
                        $sql = $sql." AND R.region_id = ".$region;
                    }
                    $sql .= " ORDER BY rm.region";    
                    $result = $conn->query($sql);
                
                    if ($result->num_rows > 0) {
                        // output data of each row
                        $count = 1;
                        $region_prev = "";
                        while ($row = $result->fetch_assoc()) {
                            $url_length = strlen($row["URL"]);
                            $url = substr($row["URL"], 0, 100);
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

                            </tr>
                        <?php
                            $region_prev = $row["region"];
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5">No Records</td>
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

