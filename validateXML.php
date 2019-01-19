<html>
	<head>
		<title>Validate</title>
		<link rel="stylesheet" type="text/css" href="StyleFile/myStyle.css">
	</head>
	<body>
		<header>
			Shop-Okay<br>
			<span style="font-size:20px;font-family: Gotham, 'Helvetica Neue', Helvetica">Supermarket</span>
		</header>
		<center>
		<div id="content">
			<?php
				$conn = @mysqli_connect("localhost","root","","products");
				
				if(mysqli_connect_errno()){
					echo "Unable to connect to database". mysqli_connect_error();
					exit;
				}
				
				$xmlFile = $_POST['File'];
				$schema = "productsSchema.xsd";
				
				$doc = new DOMDocument();
				$doc->load($xmlFile);
				
				if ($doc->schemaValidate($schema)){	
					$products = $doc->getElementsByTagName("product");
					foreach ($products as $value) {
						$category = $value->getElementsByTagName("category");
						$category_tag = $category->item(0)->nodeName;
						$category_text = $category->item(0)->nodeValue;
						
						$name = $value->getElementsByTagName("name");
						$name_tag = $name->item(0)->nodeName;
						$name_text = $name->item(0)->nodeValue;
						
						$price = $value->getElementsByTagName("price");
						$price_tag = $price->item(0)->nodeName;
						$price_text = $price->item(0)->nodeValue;
						
						$quantity = $value->getElementsByTagName("quantity");
						$quantity_tag = $quantity->item(0)->nodeName;
						$quantity_text = $quantity->item(0)->nodeValue;
						
						$photo = $value->getElementsByTagName("photo");
						$photo_tag = $photo->item(0)->nodeName;
						$photo_text = $photo->item(0)->nodeValue;
						
						$sql = "INSERT INTO product_details (category,name,price,quantity,photo)
						VALUES ('$category_text','$name_text','$price_text','$quantity_text','$photo_text')";
						if ($conn->query($sql) != True) {
							echo "Error: " . $sql . "<br>" . $conn->error;
						}
					}
					echo "The products' details have been successfully added<br/><br/>";?>
					<button type="button" class="button" onclick="window.location.href='createXML.html'" name="Products">Enter more products</button><?php
				}else{
					echo $xmlFile + " is invalid";
				}
			?>
		</div>
		</center>
	</body>
</html>