<?php
session_start();
$con=mysqli_connect("localhost","root","","shopping_cart");
if(isset($_POST["add_to_cart"]))  
 {  
      if(isset($_SESSION["shopping_cart"]))  
      {  
           $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");  
           if(!in_array($_GET["id"], $item_array_id))  
           {  
                $count = count($_SESSION["shopping_cart"]);  
                $item_array = array(  
                     'item_id' => $_GET["id"],  
                     'item_name' => $_POST["hidden_name"],  
                     'item_price' => $_POST["hidden_price"],  
                     'item_quantity' => $_POST["quantity"]  
                );  
                $_SESSION["shopping_cart"][$count] = $item_array;  
           }  
           else  
           {  
                echo '<script>alert("Item Already Added")</script>';  
                echo '<script>window.location="index.php"</script>';  
           }  
      }  
      else  
      {  
           $item_array = array(  
                'item_id'=> $_GET["id"],  
                'item_name' => $_POST["hidden_name"],  
                'item_price' => $_POST["hidden_price"],  
                'item_quantity' => $_POST["quantity"]  
           );  
           $_SESSION["shopping_cart"][0] = $item_array;  
      }  
 }  
 if(isset($_GET["action"]))  
 {  
      if($_GET["action"] == "delete")  
      {  
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
           {  
                if($values["item_id"] == $_GET["id"])  
                {  
                     unset($_SESSION["shopping_cart"][$keys]);  
                     echo '<script>alert("Item Removed")</script>';  
                     echo '<script>window.location="index.php"</script>';  
                }  
           }  
      }  
 }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Shopping cart</title>
	<style type="text/css">
		#head{
			color: #337AB7;
			text-decoration: underline;
			margin-bottom: 20px;
		}
	</style>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
	<h2 id="head" align="center">Online Shopping cart</h2>
	<?php
		$query="SELECT *FROM products ORDER by id ASC";
		$result=mysqli_query($con,$query);
		if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
				
				?>
				 <div class="col-md-3">
            <form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
            <div style="border: 1px solid #eaeaec; margin: -1px 19px 3px -1px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); padding:10px;" align="center">
            <img src="<?php echo $row["image"]; ?>" class="img-responsive">
            <h5 class="text-info"><?php echo $row["pname"]; ?></h5>
            <h5 class="text-danger">Rs. <?php echo $row["price"]; ?></h5>
            <input type="text" name="quantity" class="form-control" value="1">
            <input type="hidden" name="hidden_name" value="<?php echo $row["pname"]; ?>">
            <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">
            <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-primary" value="Add to Bag">
            </div>
            </form>
            </div>
            <?php
			}
		}
			?>
				 		<div style="clear:both"></div>
   						 <h2>My Shopping Bag</h2>
					    <div class="table-responsive">
    			    <table class="table table-bordered">
    					<tr>
    					<th width="40%">Product Name</th>
    					<th width="10%">Quantity</th>
    					<th width="20%">Price Details</th>
    					<th width="15%">Order Total</th>
    					<th width="5%">Action</th>
    					</tr>
    					 <?php   
                          if(!empty($_SESSION["shopping_cart"]))  
                          {  
                               $total = 0;  
                               foreach($_SESSION["shopping_cart"] as $keys => $values)  
                               {  
                          ?>  
                          <tr>  
                               <td><?php echo $values["item_name"]; ?></td>  
                               <td><?php echo $values["item_quantity"]; ?></td>  
                               <td>Rs. <?php echo $values["item_price"]; ?></td>  
                               <td>Rs. <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>  
                               <td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>  
                          </tr>  
                          <?php  
                                    $total = $total + ($values["item_quantity"] * $values["item_price"]);  
                               }  
                          ?>  
                          <tr>  
                               <td colspan="3" align="right"><b>Total</b></td>  
                               <td align="right">Rs. <?php echo number_format($total, 2); ?></td>  
                               <td></td>  
                          </tr>  
                          <?php  
                          }  
                          ?>  
                     </table> 
     					 
             </div>
	
		</div>
</body>
</html>