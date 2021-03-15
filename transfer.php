<?php
	include("connection.php");
	include("header.php");
	$error="";
	$success="";
    /*function to check consistence id and name*/
	function check($link,$id,$name){
        $query="SELECT * FROM customers";
        $result=mysqli_query($link,$query);
        while($row=mysqli_fetch_assoc($result)){
            if($row["ID"] == $id){
                if($row["NAME"] == $name){
                    return true;
                }else{
                    return false;
                }
                
            }
        }
        return false;
    }
	if(array_key_exists("submit",$_POST)){ /*Action to perform after submitting form*/
        /*If form inputs are blank*/
	    if(!$_POST["from_id"] or !$_POST["from_name"] or !$_POST["to_id"] or !$_POST["to_name"] or !$_POST["amount"] ){
            $error .= "Please enter into all input fields<br></p>";
        }else{
            /*Convert the inputs appropriately*/
            $_POST["from_name"]=ucwords(strtolower($_POST["from_name"]));
            $_POST["to_name"]=ucwords(strtolower($_POST["to_name"]));
        }
        /*If sender and receiver details are same*/
        if(($_POST["from_id"]==$_POST["to_id"]) or ($_POST["from_name"]==$_POST["to_name"]))
        {
            $error.="Cannot send to same people<br></p>";
        }
        
        /*If there are some error show this message*/
        if($error!=""){
            $error="<p>There were some error(s):</p>".$error;
        }else{
            /*If there are no errors check if ids and names are same for a particular peson*/
            $check1=check($link,$_POST["from_id"],$_POST["from_name"]);
            $check2=check($link,$_POST["to_id"],$_POST["to_name"]);
            if ($check1 == false or $check2 == false){
                $error.="<p>There were some error(s):<br>";
                $error.="Please enter valid ID or NAME<br>";
            }else{
                $query="SELECT * FROM customers where ID=".$_POST['from_id'];
                $result=mysqli_query($link,$query);
                $row=mysqli_fetch_array($result);
                /*If there is not enough balance*/
                if($row["BALANCE"]<$_POST["amount"]){
                    $error.=$row["NAME"]." doesn't have enough money<br>";
                }else{
                    /*Update data for sender*/
                    $query="UPDATE customers SET BALANCE=".($row["BALANCE"]-$_POST["amount"])." WHERE ID=".$_POST["from_id"];
                    mysqli_query($link,$query);
                    //$success.="Amount Rs.".$_POST["amount"]."/- is transferred from ".$row["NAME"];

                    /*Update data for receiver*/
                    $query="SELECT * FROM customers where ID=".$_POST['to_id'];
                    $result=mysqli_query($link,$query);
                    $row=mysqli_fetch_array($result);
                    $query="UPDATE customers SET BALANCE=".($row["BALANCE"]+$_POST["amount"])." WHERE ID=".$_POST["to_id"];
                    if(mysqli_query($link,$query)){
                       // $success.=" to ".$row["NAME"];
                        //transaction history update
                        $query="INSERT INTO history (from_id,from_name,to_id,to_name,amount,time) VALUES(".$_POST["from_id"].",'".$_POST["from_name"]."',".$_POST["to_id"].",'".$_POST["to_name"]."',".$_POST["amount"].",NOW())";
                        if(mysqli_query($link,$query)){
                            echo '<script type="text/JavaScript">  
                            alert("Transaction Successfull!!");
                            document.location.href = "view+customers.php"; 
                            </script>'; 
                            //echo $success;
                        }
                    }
                   
                    
                    
                     
                }
                
            }
        }
	    
	}
	if(array_key_exists("custId",$_GET)){
        //echo $_GET["custId"];
        
        $query="SELECT * from customers where id=".$_GET["custId"];
        $result=mysqli_query($link,$query);
        $row1 = mysqli_fetch_array($result);
	}

?>

<div class="container">
<?php 
    if($error){
        echo '<div class="alert alert-danger" role="alert"><strong>'.$error.'</strong></div>';
    } 
?>
</div>
<div class="container transfer-form">
    
	<form method="post">
		<h3>From:</h3>
	  <div class="form-group">
	    <label for="from_id">Type Id</label>

	    <input type="number" class="form-control" id="from_id" name="from_id" placeholder="Enter Id" min="1011" max="1020" 
        <?php 
        //If there is a get variable readonly the field
	                        if(array_key_exists('custId',$_GET)){ 
	                        echo "value='".$row1['ID']."'";
	                        echo "readonly";
	                        }else{ 
	                        echo '';
	                       }?>>
	    <small id="emailHelp" class="form-text text-muted">We'll never share your details with anyone else.</small>
	  </div>
	  <div class="form-group">
	    <label for="from_name">Name</label>
	    <input type="text" class="form-control" id="from_name" name="from_name" <?php 
	    //If there is a get variable readonly the field
	    if(array_key_exists('custId',$_GET)){ 
                        echo "value='".$row1['NAME']."'"; 
                        echo "readonly";
                       }else{ 
                        echo '';
                    }?>>
	  </div>
	  <h3>To:</h3>
	  <div class="form-group">
	    <label for="to_id">Enter Id</label>
	    <input type="number" class="form-control" id="to_id" name="to_id"  placeholder="Enter Id" min="1011" max="1020">
	    <small id="emailHelp" class="form-text text-muted">We'll never share your details with anyone else.</small>
	  </div>
	  <div class="form-group">
	    <label for="to_name">Enter Name</label>
	    <input type="text" class="form-control" id="to_name"  name="to_name" placeholder="">
	  </div>
	  <div class="form-group">
	    <label for="amount"><h4>Enter Amount</h4></label>
	    <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount">
	  </div>
	  
	  <button type="submit" class="btn btn-primary" name="submit">Transfer</button>
	</form>
</div>

<?php include("footer.php") ?>
