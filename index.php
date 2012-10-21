<?php

session_start();
include("header.php");
include("config.php");
?>
  	
	<div id="content-container">
	
		<div id="content">
			<h2>
				Product
			</h2>



			<div class="thumbnail">
				<img src="images/flower2.jpeg" alt="" width="120"><br>
				Flower D <?php echo $PayPalCurrencyCode; ?> $35.00<br>
				<!--form method="post" action="cart.php"-->
					<form action='cart_checkout.php' METHOD='POST'>
					<input type="hidden" name="itemname" value="Flower D" /> 
					<input type="hidden" name="itemdesc" value="Flower D Desc" />
					<input type="hidden" name="itemnumber" value="p1004" /> 
					<input type="hidden" name="itemprice" value="35.00" />
        			Quantity : 
        			<select name="itemQty">
        			<option value="1">1</option>
        			<option value="2">2</option>
        			<option value="3">3</option>
        			</select> 
        			<br><br><!--input class="dw_button" type="submit" name="submit" value="Add to Cart" /-->
					<input type="hidden" name="useraction" value="commit">
					<input type='image' name='submit' src='images/buynow.jpeg' border='0' align='top' alt='Check out with PayPal'/>
					</form>	
    			
			</div>


		</div>
		
		<div id="aside">
			<h3>
				Express Checkout
			</h3>
			
			<br><br>Buy Now Button: 
			<p>Checkout with Paypal and pay at PayPal.
			<br>Note: Recommend for flat or free shipping fee.
			<br>Update shipping fee at paypal_ecfunctions.php
		</div>

<?php
	include("footer.php");
?>