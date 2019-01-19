<html>
	<head>
		<title>Write Data</title>
		<link rel="stylesheet" type="text/css" href="StyleFile/myStyle.css">
		<?php
			$xmlDoc = null;
			if (isset($_POST['hiddenFile'])){
				$xmlDoc = $_FILES['ChooseFile']['name'];
			}
		?>
		<script>
			function load(xmlDoc){
				//var content = document.getElementById("content");
				var connect = new XMLHttpRequest();
				connect.open("GET",xmlDoc,false);
				connect.send();
				
				var document = connect.responseXML;
				var products = document.childNodes[0];
				
				var newContent ="<h1>Product Information</h1>" + 
								"<table cellpadding='8' border='1' style='text-align:center'>" + 
								"<tr>" +
								"<th>Products&nbsp;</th><th>Category</th><th>Name</th><th>Price</th><th>Quantity</th><th>Image</th>" +
								"</tr>";
				
				for (var i = 0; i < products.children.length; i++) {
					var product = products.children[i];
					var category = product.getElementsByTagName("category");
					var name = product.getElementsByTagName("name");
					var price = product.getElementsByTagName("price");
					var quantity = product.getElementsByTagName("quantity");
					var photo = product.getElementsByTagName("photo");
					
					
					var productNum = i + 1;

					newContent = newContent + "<tr><td>Product " + productNum + "</td><td>" + category[0].textContent.toString() + "</td><td>" + 
								name[0].textContent.toString() + "</td><td>" + price[0].textContent.toString() + "</td><td>" + 
								quantity[0].textContent.toString() + "</td><td><img src='" + photo[0].textContent.toString() + "' width='100' height='100'/></td></tr>";
				}
				newContent = newContent = newContent + "</table><form enctype='multipart/form-data' id='uploadFile' style='padding-top:20px' method='post'>" +
							"<input type='file' name='ChooseFile' id='ChooseFile' required/>" +
							"<input type='hidden' value='true' name='hiddenFile'/>" +
							"<input type='submit' name='ChangeFile' id='ChangeFile' value='Submit'/></form>" + 
							"<form action='validateXML.php' enctype='multipart/form-data' style='padding-top:20px' method='post'>" +
							"<input type='hidden' name='File' id='File' value='<?php echo $xmlDoc ?>'>" +
							"<input type='submit' class='button' value='Validate' name='ValidateFile' id='ValidateFile'>" +
							"</form>";
				content.innerHTML = newContent;
			}
		</script>
	</head>
	<body>
		<header>
			Shop-Okay<br>
			<span style="font-size:20px;font-family: Gotham, 'Helvetica Neue', Helvetica">Supermarket</span>
		</header>
		<center>
			<div id="content" style="padding-top:20px">
			<?php
				if (isset($_POST['writeXML'])){
					$writeXML = $_POST['writeXML'];
					if ($writeXML = true){
						$products = array();
						$products[] = array("category"=>$_POST['CategoryOne'],"name"=>$_POST['NameOne'],"price"=>$_POST['PriceOne'],
									"quantity"=>$_POST['QuantityOne'],"size"=>$_POST['SizeOne'],"photo"=>$_FILES['PhotoOne']['name'],
									"photoTemp"=>$_FILES['PhotoOne']['tmp_name']);
						$products[] = array("category"=>$_POST['CategoryTwo'],"name"=>$_POST['NameTwo'],"price"=>$_POST['PriceTwo'],
									"quantity"=>$_POST['QuantityTwo'],"size"=>$_POST['SizeTwo'],"photo"=>$_FILES['PhotoTwo']['name'],
									"photoTemp"=>$_FILES['PhotoTwo']['tmp_name']);
						$products[] = array("category"=>$_POST['CategoryThree'],"name"=>$_POST['NameThree'],"price"=>$_POST['PriceThree'],
									"quantity"=>$_POST['QuantityThree'],"size"=>$_POST['SizeThree'],"photo"=>$_FILES['PhotoThree']['name'],
									"photoTemp"=>$_FILES['PhotoThree']['tmp_name']);
						$products[] = array("category"=>$_POST['CategoryFour'],"name"=>$_POST['NameFour'],"price"=>$_POST['PriceFour'],
									"quantity"=>$_POST['QuantityFour'],"size"=>$_POST['SizeFour'],"photo"=>$_FILES['PhotoFour']['name'],
									"photoTemp"=>$_FILES['PhotoFour']['tmp_name']);
						$products[] = array("category"=>$_POST['CategoryFive'],"name"=>$_POST['NameFive'],"price"=>$_POST['PriceFive'],
									"quantity"=>$_POST['QuantityFive'],"size"=>$_POST['SizeFive'],"photo"=>$_FILES['PhotoFive']['name'],
									"photoTemp"=>$_FILES['PhotoFive']['tmp_name']);
						
						$dom = new DOMDocument('1.0', 'UTF-8');
						
						$root = $dom->createElement("products");
						$root->setAttribute("xmlns:xsi","http://www.w3.org/2001/XMLSchema-instance");
						$root->setAttribute("xsi:noNamespaceSchemaLocation","productsSchema.xsd");
						$dom->appendChild($root);
						
						foreach ($products as $data) {
							$target_path = "Uploads/";
							static $id_value = "id1";
							
							$product = $dom->createElement("product");
							$root->appendChild($product);
							
							$id_attr = $dom->createAttribute("id");
							$product->appendChild($id_attr);
							$attr_value = $dom->createTextNode($id_value++);
							$id_attr->appendChild($attr_value);
							
							$category = $dom->createElement("category");
							$product->appendChild($category);
							$cat_value = $dom->createTextNode($data["category"]);
							$category->appendChild($cat_value);
							
							$name =$dom->createElement("name");
							$product->appendChild($name);
							$name_value = $dom->createTextNode($data["name"]);
							$name->appendChild($name_value);
							
							$price = $dom->createElement("price");
							$product->appendChild($price);
							$price_value = $dom->createTextNode($data["price"]);
							$price->appendChild($price_value);
							
							$quantity = $dom->createElement("quantity");
							$product->appendChild($quantity);
							$quan_value = $dom->createTextNode($data["quantity"]);
							$quantity->appendChild($quan_value);
							
							$size = $dom->createElement("size");
							$product->appendChild($size);
							$size_value = $dom->createTextNode($data["size"]);
							$size->appendChild($size_value);
							
							$target_path = $target_path.basename($data["photo"]);
							move_uploaded_file($data["photoTemp"],$target_path);
							
							$photo = $dom->createElement("photo");
							$product->appendChild($photo);
							$photo_value = $dom->createTextNode($target_path);
							$photo->appendChild($photo_value);
						}
						
						
						echo "Product details have been successfully written to the xml file<br/><br/>";
						
						$dom->save("products.xml");
						$xmlDoc = "products.xml";
						$writeXML = false;
					}
				}
					?>
					<button type="button" class="button" name="Return" onclick="load('<?php echo $xmlDoc ?>')">View product details</button>
			</div>
		</center>
	</body>
</html>