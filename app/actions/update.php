<?php 
require "../../dbpath.php";
require "validate.php";

if (isset($_POST['id']) && isset($_POST['Product_Name']) && isset($_POST['Cost'])) {	
	try {
		$connection = new PDO ($dsn);
		$connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$id = $_POST['id'];
		$Product_Name = $_POST['Product_Name'];
		$Cost = $_POST['Cost'];

		$sql = "UPDATE Product SET Product_Name = :Product_Name, Cost = :Cost WHERE id = :id";
		$sqlCheck = "SELECT EXISTS (SELECT * FROM Product WHERE id = :id)";
		$sqlSelect = "SELECT * FROM Product WHERE id = :id";
		$sqlCheckProductName = "SELECT * FROM Product";
		
		$statement = $connection->prepare($sqlCheck);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetch();

		if($result[0] == "0"){
			header('Content-Type: application/json');
			http_response_code(400);
			echo (json_encode(['errorMessage' => 'Invalid id']));
			return;
		} else if(!isValidProductName($Product_Name)) { 
			header('Content-Type: application/json');
			http_response_code(400);
			echo json_encode(['errorMessage' =>"The Product_Name is invalid"]);
			return;
		} else if (!isValidCost($Cost)) {
			header('Content-Type: application/json');
			http_response_code(400);
			echo json_encode(['errorMessage' =>"The Cost is invalid"]);
			return;
		} else {
			$statement = $connection->prepare ( $sqlCheckProductName );
			$statement->execute();
			$result = $statement->fetchAll();
			foreach ($result as $product) {
				if ($Product_Name == $product["Product_Name"] && $id != $product["id"]) {
					header('Content-Type: application/json');
					http_response_code(400);
					echo json_encode(['errorMessage' =>"Product already exists"]);
					return;
				}
			} 

			$statement = $connection->prepare ($sql);
			$statement->bindParam(':id', $id);
			$statement->bindParam(':Product_Name', $Product_Name);
			$statement->bindParam(':Cost', $Cost);
			$statement->execute();

			$statement = $connection->prepare($sqlSelect);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch();

			$to      = 'products@company.com​';
		    $subject = 'Update product';
		    $message = 'Product (id = ' . $id . ') was updated (new product_name = ' . $Product_Name . ', new cost = ' . $Cost . ') in Database';
		    $headers = 'From: application@company.com' . "\r\n" .
		    	'Reply-To: Mykyta Shvets@company.com' . "\r\n" .
		    	'X-Mailer: PHP/' . phpversion();
		   	mail($to, $subject, $message, $headers);

			http_response_code(200);
			echo (json_encode($result));
			return;
		}
	} catch ( PDOException $error ) {
		header('Content-Type: application/json');
		http_response_code(400);
		echo (json_encode(['errorMessage' => $error->getMessage()]));
		exit ();
	}
}
?>