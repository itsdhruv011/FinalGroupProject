<?php  
// http://localhost/FinalGroupProject/testing.php
require_once "gcpinclude.php";
date_default_timezone_set("America/Toronto");
$mysqlObj = CreateConnectionObject();
?>

<?php
// Initialize variables
$textarea = "";
$message = "";
$find = "";
$case = false;
$position = 0;

// Handle form submission
if (isset($_POST['submit'])) {
  // Get the textarea content
  $textarea = $_POST['textarea'];

  // Handle new button
  if ($_POST['submit'] == 'New') {
    // Clear the textarea
    $textarea = "";
    $message = "New file created.";
  }

  // Handle open button
  if ($_POST['submit'] == 'Open') {
    // Check if a file was selected
    if (isset($_FILES['fileInput'])) {
      // Get the file
      $file = $_FILES['fileInput'];
      // Check for errors during upload
      if ($file['error'] > 0) {
        // Display an error message
        $message = "Error: " . $file['error'];
      } else {
        // Read the file content into the textarea
        $textarea = file_get_contents($file['tmp_name']);
        $message = "File opened successfully.";
        // Display the file content in the textarea
        echo '<script>document.getElementById("textarea").value = ' .
          json_encode($textarea) . ';</script>';
      }
    } else {
      // Display an error message
      $message = "Please select a file to open.";
    }
  }

  // Handle save button
  if ($_POST['submit'] == 'Save') {
    // Check if the textarea is not empty
    if (!empty($textarea)) {
        // Write the textarea content to the file
        $filename = 'editor.dat';
        $result = file_put_contents($filename, $textarea);
        
        if ($result !== false) {
            $message = "File saved successfully.";
            // Download the file to the user's computer
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
        } else {
            $message = "Error Saving File.";
        }
    } else {
        // Display an error message
        $message = "Textarea is empty. Please enter some text to save.";
    }
}
  }

  // Handle find button
  if ($_POST['submit'] == 'Find') {
    // Get the find string
    $find = $_POST['find'];
    // Check if the find string is not empty
    if ($find != "") {
      // Get the case sensitivity option
      $case = isset($_POST['case']);
      // Find the first occurrence of the find string in the textarea
      if ($case) {
        // Case-sensitive search
        $position = strpos($textarea, $find);
      } else {
        // Case-insensitive search
        $position = stripos($textarea, $find);
      }
      // Check if the find string was found
      if ($position !== false) {
        // Display a success message
        $message = "Found '$find' at position $position.";
      } else {
        // Display a failure message
        $message = "Not found '$find'.";
      }
    } else {
      // Display an error message
      $message = "Please enter a string to find.";
    }
  }

  // Handle find next button
  if ($_POST['submit'] == 'Find Next') {
    // Get the find string
    $find = $_POST['find'];
    // Check if the find string is not empty
    if ($find != "") {
      // Get the case sensitivity option
      $case = isset($_POST['case']);
      // Get the current position
      $position = $_POST['position'];
      // Find the next occurrence of the find string in the textarea
      if ($case) {
        // Case-sensitive search
        $position = strpos($textarea, $find, $position + 1);
      } else {
        // Case-insensitive search
        $position = stripos($textarea, $find, $position + 1);
      }
      // Check if the find string was found
      if ($position !== false) {
        // Display a success message
        $message = "Found '$find' at position $position.";
      } else {
        // Display a failure message
        $message = "Not found '$find'.";
      }
    } else {
      // Display an error message
      $message = "Please enter a string to find.";
    }
  }


?>

<html>
<head>
  <title>Tara's Struggle</title>
</head>
<body>
  <h1>Tara's Struggle</h1>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
    <p>
      <label for="fileInput">File:</label>
      <input type="file" id="fileInput" name="fileInput">
    </p>
    <p>
      <input type="submit" name="submit" value="New">
      <input type="submit" name="submit" value="Open">
      <input type="submit" name="submit" value="Save">
    </p>
    <p>
      <label for="find">Find:</label>
      <input type="text" id="find" name="find" value="<?php echo $find; ?>">
      <input type="hidden" name="position" value="<?php echo $position; ?>">
      <input type="checkbox" id="case" name="case" <?php echo $case ? "checked" : ""; ?>>
      <label for="case">Case-sensitive</label>
    </p>
    <p>
      <input type="submit" name="submit" value="Find">
      <input type="submit" name="submit" value="Find Next">
    </p>
    <p>
      <?php echo $message; ?>
    </p>
    <p>
      <label for="textarea">Text Area:</label><br>
      <textarea id="textarea" name="textarea" rows="10" cols="50"><?php echo $textarea; ?></textarea>
    </p>
  </form>
</body>
</html>

