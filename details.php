<?php include("header.php"); ?>


<div class="container">
<table class="table table-striped table-dark">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Balance</th>
      <th scope="col">Profile</th>
    </tr>
  </thead>
  <tbody>

<?php
    if(array_key_exists("custId",$_GET)){
        //echo $_GET["custId"];
        include("connection.php");
        $query="SELECT * from customers where id=".$_GET["custId"];
        $result=mysqli_query($link,$query);
		
	    
	   
	    while($row = mysqli_fetch_array($result))
	    {
	    echo "<tr>";
	    echo "<td>" . $row['ID'] . "</td>";
	    echo "<td>" . $row['NAME'] . "</td>";
	    echo "<td>" . $row['EMAIL'] . "</td>";
	    echo "<td>" . $row['BALANCE'] . "</td>";
	    
	    echo '<td><a href="transfer.php?custId='.$row["ID"].'"><button type="submit" id="btn" name="custId" class="btn btn-outline-success">Transfer Money</button></a></td>';
	    
	    echo "</tr>";
	    
	    }
	    echo "</table>"; 
    }else{
        echo "f";
    }
    
    
    
     
    
?>

</tbody>
</table>
</div>



<?php include("footer.php"); ?>