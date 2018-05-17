<?php 
require "../../dbpath.php";
require "validate.php";

if (isset($_POST['Product_Name']) && isset($_POST['Cost'])) {	
	try {
		$connection = new PDO ($dsn);
		$connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
		$Product_Name = $_POST['Product_Name'];
		$Cost = $_POST['Cost'];

		$sql = "INSERT INTO Product (Product_Name,Cost) VALUES (:Product_Name, :Cost)";
		$sqlCheck = "SELECT * FROM Product";

		if(!isValidProductName($Product_Name)) { 
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
			$statement = $connection->prepare ( $sqlCheck );
			$statement->execute();
			$result = $statement->fetchAll();
			foreach ($result as $product) {
				if ($Product_Name == $product["Product_Name"]) {
					header('Content-Type: application/json');
					http_response_code(400);
					echo json_encode(['errorMessage' =>"Product already exists"]);
					return;
				}
			} 
			$statement = $connection->prepare ( $sql );
			$statement->bindParam(':Product_Name', $Product_Name);
			$statement->bindParam(':Cost', $Cost);
			$statement->execute();

			$to      = 'products@company.com​';
		    $subject = 'Create product';
		    $message = 'New product (Product_Name = ' . $Product_Name . ', cost = ' . $Cost . ' was created in Database';
		    $headers = 'From: application@company.com' . "\r\n" .
		    	'Reply-To: Mykyta Shvets@company.com' . "\r\n" .
		    	'X-Mailer: PHP/' . phpversion();
		   	mail($to, $subject, $message, $headers);
		}

	} catch ( PDOException $error ) {
		header('Content-Type: application/json');
		http_response_code(400);
		echo (json_encode(['errorMessage' => $error->getMessage()]));
		exit();
	}

	try {
		$connection = new PDO ($dsn);
		$sql = "SELECT * FROM Product ORDER BY id DESC LIMIT 1";
		$statement = $connection->prepare( $sql );
		$statement->execute();
		$result = $statement->fetch();
	
		http_response_code(201);
		echo (json_encode($result));
		return;
	} catch ( PDOException $error ) {
		header('Content-Type: application/json');
		http_response_code(400);
		echo (json_encode(['errorMessage' => $error->getMessage()]));
		exit();
	}
}
?>