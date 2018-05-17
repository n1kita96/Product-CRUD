<?php 
require "../../dbpath.php";
if (isset ( $_POST ['id'] )) {	
	try {
		$connection = new PDO ($dsn);
		$connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
		$id = $_POST['id'];

		$sqlCheck = "SELECT EXISTS (SELECT * FROM Product WHERE id = :id)";
		$sql = "DELETE FROM Product WHERE id = :id";

		$statement = $connection->prepare ($sqlCheck);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetch();
		
		if($result[0] == "0"){
			header('Content-Type: application/json');
			http_response_code(400);
			echo (json_encode(['errorMessage' => 'Invalid id']));
			return;
		} else {
			$statement = $connection->prepare ($sql);
			$statement->bindParam(':id', $id);
			$statement->execute();
			
			$to      = 'products@company.com​';
		    $subject = 'Delete product';
		    $message = 'Product (id = ' . $id . ') was deleted from Database';
		    $headers = 'From: application@company.com' . "\r\n" .
		    	'Reply-To: Mykyta Shvets@company.com' . "\r\n" .
		    	'X-Mailer: PHP/' . phpversion();
		   	mail($to, $subject, $message, $headers);

			http_response_code(200);
			echo (json_encode(['successMessage' => 'Row deleted successfully']));
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