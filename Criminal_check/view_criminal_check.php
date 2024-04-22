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

<?php include "connection.php"; ?>
<?php
session_start();
$actionscr="";
if($_SESSION['user']=="")
{
	$headerval='Location:endSession.php';
	header($headerval);
}


$country_id="";
if(isset($_POST["country_id"]))
    $country_id=$_POST["country_id"];

if(isset($_POST['actionscr']))
    $actionscr=$_POST['actionscr'];

$conn = new mysqli($servername, $username, $password,$db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$select_country_sql="SELECT country_id ,country_name FROM country_master";
$country_result = $conn->query($select_country_sql);
?>


<HTML">
<HEAD>
    <TITLE>Criminal Checks</TITLE>
    <SCRIPT>
       
    </SCRIPT>
</HEAD>



<BODY>
<form method="POST" action="criminal_checks.php">
<?php include("header.php"); ?>
<div class="sidebar">
    <br/><br/><br/><?php include("sidebar.php"); ?>
</div>
<input type="hidden" name="country_id" value="<?php echo $_POST["country_id"]; ?>"/>
<input type="hidden" name="actionscr" value="<?PHP echo $actionscr; ?>"/>
<div class="content">
    <br/><br/><br/><br/>
    <h4>View Criminal Check</h4>
    <hr/>
    <div class="row rowintro">
        <label class="col-sm-1 control-label text-primary">Country</label>
        <div class="col-sm-6">
            <select class="form-control" name="country_id" required>
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
            <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span>&nbsp;Search</button>
        </div>
    </div>
    <br/>

    <div class="table-responsive table-wrapper-scroll-y my-custom-scrollbar">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Country</th>
                    <th>Criminal URL</th>
                    <th>Last Update</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "connection.php";
                $conn = new mysqli($servername, $username, $password, $db);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT c.country_id, cm.country_name, c.url1, c.url2, c.update_date
                        FROM criminal_checks c,country_master cm
                        WHERE c.country_id = cm.country_id";
                        if($country_id!=""){
                            $sql = $sql." AND c.country_id = ".$country_id;
                        }
                $sql = $sql." ORDER BY c.country_id";
                

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
            
                        $url_length1 = strlen($row["url1"]);
                        $url1 = substr($row["url1"], 0, 100);

                        $url_length2 = strlen($row["url2"]);
                        $url2 = substr($row["url2"], 0, 100);
                        ?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <?PHP if($region_prev!=$row["country_id"]){ ?>
                            <td><b><?PHP echo $row["country_name"]; ?></b></td>
                            <?PHP }else{ ?>
                            <td></td>
                            <?PHP } ?> 
                            <?PHP if($url_length1 >= 100){?>
                            <td><a href="#" onclick="" title="Click Here For More Information" id="btn"><?PHP echo$url1."..."; ?></a></td>
                            <?PHP }else {?>
                            <td><a href="#" onclick="" title="Click Here For More Information" id="btn"><?PHP echo $url1; ?></a></td>
                            <?PHP } ?>
                            <td><?PHP echo $row["update_date"]; ?></td>
                            
                        </tr>
                        <?PHP if($url2!=""){ ?>
                        <tr>
                            <td></td><td></td>
                            <?PHP if($url_length2 >= 100){?>
                            <td><a href="#" onclick="" title="Click Here For More Information" id="btn"><?PHP echo$url2."..."; ?></a></td>
                            <?PHP }else {?>
                            <td><a href="#" onclick="" title="Click Here For More Information" id="btn"><?PHP echo $url2; ?></a></td>
                            <?PHP } ?>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php } ?>
                                                    
                <?php
                    } $region_prev=$row["country_id"];
                } else {
                ?>
                    <tr>
                        <td colspan="4">No Records</td>
                    </tr>
                <?php
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <?php include("footer.php"); ?>
</div>
</form>
</body>
</html>

