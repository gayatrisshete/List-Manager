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
    date_default_timezone_set("Asia/Kolkata");

    $action="";
    $actionscr="";
    $listid="";
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
    $region_id = "";
    $country_id = "";
    $update_date="";
    $description="";

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

$select_region_sql="SELECT region_id ,region FROM region_master";
$region_result = $conn->query($select_region_sql);

$select_country_sql="SELECT country_id ,country_name FROM country_master";
$country_result = $conn->query($select_country_sql);

if ($listid!= "") {
    $sql = "SELECT g.id, g.authority_domain, g.regulatory_authority_central_bank, g.AIact_policy,
            g.antimoney_laundering, bribery, g.credit_cards, g.cloud_policy, g.data_privacy,
            g.fraud, g.sanctions, g.terrorism, g.other, g.region_id, g.country_id, g.user_id, g.update_date,g.dir_description 
            FROM global_directory g, user_master u, region_master rm
            WHERE g.user_id = u.user_id 
            AND g.region_id = rm.region_id";
            if ($_SESSION['role'] == "admin") {
                $sql = $sql." AND u.role = '" . $_SESSION['role'] . "'";
            }
            if ($_SESSION['role'] == "user") {
                $sql= $sql." AND u.user_id = '" . $_SESSION['user_id'] . "'";
            }

    $sql = $sql." AND id = ".$listid;
    $result = $conn->query($sql);

    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        { 
            $authority_domain = $row["authority_domain"];
            $regulatory_authority_central_bank = $row["regulatory_authority_central_bank"];
            $AIact_policy = $row["AIact_policy"];
            $antimoney_laundering = $row["antimoney_laundering"];
            $bribery = $row["bribery"];
            $credit_cards = $row["credit_cards"];
            $cloud_policy = $row["cloud_policy"];
            $data_privacy = $row["data_privacy"];
            $fraud = $row["fraud"];
            $sanctions = $row["sanctions"];
            $terrorism =$row["terrorism"];
            $other = $row["other"];
            $region_id = $row["region_id"];
            $country_id = $row["country_id"];
            $user_id = $_SESSION["userid"];
            $update_date = $row["update_date"];
            $description = $row["dir_description"];
        }
    }
}
$conn->close();
?>
<HTML ng-app="sanctionList">
<HEAD>
	<TITLE>Global directory</TITLE>
	<LINK type=text/css rel=Stylesheet href="css/kapiraj.css">
	<SCRIPT language="javascript">
    </SCRIPT>
</HEAD>

<BODY>
<form action="update_global_directory.php" method="post" class="form-horizontal">
<?php include("header.php"); ?>
<div class="sidebar">
    <br/><br/><br/><?php include("sidebar.php"); ?>
</div>
<input type="hidden" name="action" value="update"/>
<input type="hidden" name="actionscr" value="modify"/>
<input type="hidden" name="list_id" value="<?PHP echo $listid; ?>"/>
<div class="content">
    <br/><br/><br/><br/>
    <h4>Global Directory</h4>
    <hr/>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Authority/Domain</label>
            <select class="form-control" name="country_id" id="country_id" required>
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
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Regulatory Authority / Central Bank</label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="RegA_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="RegA_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="RegA_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <textarea  class="form-control" name="RegA_note1" id="RegA_note1" rows="2" cols="50" style="resize: vertical;" placeholder="Note1"><?php echo $value1; ?></textarea>
        </div>
        <div class="col-sm-4">
            <textarea  class="form-control" name="RegA_note2" id="RegA_note2" rows="2" cols="50" style="resize: vertical;" placeholder="Note2"><?php echo $value2; ?></textarea>
        </div>
        <div class="col-sm-4">
            <textarea  class="form-control" name="RegA_note3" id="RegA_note3" rows="2" cols="50" style="resize: vertical;" placeholder="Note2"><?php echo $value3; ?></textarea>
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">AIact policy</label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="AI_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="AI_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="AI_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="AI_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="AI_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="AI_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Antimoney laundering </label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="AL_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="AL_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="AL_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
        <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="AL_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="AL_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="AL_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Bribery</label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="Bribery_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="Bribery_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="Bribery_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="Bribery_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="Bribery_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="Bribery_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Credit cards</label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="CreditC_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="creditC_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="creditC_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="creditC_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="creditC_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="creditC_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Cloud Policy</label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="CloudP_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="CloudP_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="CloudP_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="CloudP_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="CloudP_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="CloudP_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Data privacy</label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="DataP_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="DataP_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="Datap_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="Datap_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="Datap_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="Datap_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Fraud</label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="Fraud_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="Fraud_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="Fraud_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="Fraud_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="Fraud_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="Fraud_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Sanctions</label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="sanctions_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="sanctions_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="sanctions_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="sanctions_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="sanctions_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="sanctions_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Terrorism </label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="terrorism _url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="terrorism _url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="terrorism _url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="terrorism_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="terrorism_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="terrorism_note3" placeholder="Note3">
        </div>
    </div>

    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">other </label>
            <input type="text" value="<?php echo $url1; ?>" class="form-control text-capitalize" name="other_url1" id="first_name" placeholder="URL1">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url2; ?>" class="form-control text-capitalize" name="other_url2" id="second_name" placeholder="URL2">
        </div>
        <div class="col-sm-4">
            <label class="control-label text-primary">&nbsp;</label>
            <input type="text" value="<?php echo $url3; ?>" class="form-control text-capitalize" name="other_url3" id="third_name" placeholder="URL3">
        </div>
    </div>
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value1; ?>" class="form-control" name="note1" id="other_note1" placeholder="Note1">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value2; ?>" class="form-control" name="note2" id="other_note2" placeholder="Note2">
        </div>
        <div class="col-sm-4">
            <input type="text" value="<?php echo $value3; ?>" class="form-control" name="note3" id="other_note3" placeholder="Note3">
        </div>
    </div>
    
    <div class="form-group rowintro">
        <div class="col-sm-4">
            <label class="control-label text-primary">Update Date</label>
            <input type="date" value="<?PHP echo $update_date; ?>" class="form-control" name="update_date" id="update_date" required>
        </div>   
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
    </div>

    <div class="form-group rowintro" align="right">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-success btn-sm" Title="Save Changes"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Submit</button>
            <a href="home.php" class="btn btn-primary btn-sm" role="button" Title="Home Page"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a>
        </div">
    </div>
    
</div>
<br/><br/>  
<?php include("footer.php"); ?>  
</form>
</body>
</html>
