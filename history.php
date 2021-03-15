<?php include("header.php") ?>
<div class="container">
<table class="table table-striped table-dark">
  <thead>
    <tr>
      <th scope="col">Serial No</th>
      <th scope="col">Sender</th>
      <th scope="col">Receiver</th>
      <th scope="col">Amount</th>
      <th scope="col">Time</th>
    </tr>
  </thead>
  <tbody>
    <?php 
        include("connection.php");
	    $query="SELECT * FROM history ORDER BY serial_no DESC";
	    $result=mysqli_query($link,$query);
	    $i=1;
	    while($row = mysqli_fetch_array($result))
	    {
	    echo "<tr>";
	    echo "<td>" . $i . "</td>";
	    echo "<td>" . $row['from_id']."-".$row['from_name'] . "</td>";
	    echo "<td>" . $row['to_id']."-".$row['to_name'] . "</td>";
	    echo "<td>" . $row['amount'] . "</td>";
	    echo "<td>" . $row['time'] . "</td>";
	    
	    echo "</tr>";
	    $i=$i+1;
	    }
	    
?>
    
  </tbody>
</table>
</div>


<?php include("footer.php") ?>