<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
echo getcwd();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'img/'; // set the directory to save uploaded files
    $fileName = basename($_FILES['uploadedFile']['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allow certain file formats
    $allowTypes = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($fileType, $allowTypes)) {

        // Ottieni le dimensioni dell'immagine
        $size = getimagesize($_FILES['uploadedFile']['tmp_name']);
        $width = $size[0]; // Larghezza dell'immagine
        $height = $size[1]; // Altezza dell'immagine

        // Configura la risoluzione massima consentita
        $max_width = 50; // Larghezza massima
        $max_height = 50; // Altezza massima

        if ($width > $max_width || $height > $max_height) {
            echo "Errore: L'immagine supera la risoluzione massima consentita.";
        } else {
            // Upload file to the server
            if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $targetFilePath)) {
                // Call exiftool on the uploaded file to extract metadata
                $exiftoolOutput = shell_exec('exiftool ' . $targetFilePath);

                // Display the metadata to the user
                echo "<pre>$exiftoolOutput</pre>";
            } else{
    		    // There was an error uploading the file
    		    $lastError = error_get_last();
    		    echo "Error: " . $lastError['message'];
	
            }
        }

    } else {
        echo 'Sorry, only JPG, JPEG, PNG, and GIF files are allowed to upload.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Image and Extract Metadata</title>
</head>
<body>
    <h2>Upload Image and Extract Metadata</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="uploadedFile" required>
        <input type="submit" name="submit" value="Upload">
    </form>
</body>
</html>

