<?php
//http://localhost/FinalGroupProject/index.php
include_once "gcpinclude.php";
WriteHeaders("COMP220 - Team Coding Project", "gcpstyle", "gcpscript");
//main

WriteFooters();
openFile(); 
saveFile();

?>
<header>
  <h1>COMP220 - Team Coding Project</h1>
  <p>Part A - Ben E & Tara</p>
  <p>Part B - Colin & Robert</p>
  <p>Part C - Dhruv & Ayush</p>
</header>
<main>
  <section>
    <div class="left-column">
      <!-- Meant to be empty -->
    </div>
    <div class="content-container">
      <div class="menu"> <ul>
        <?php drawMenu(); ?>        
      </ul>
    </div>
    <div class="centerbox">
        <textarea id="text" spellcheck="true" wrap="hard" autofocus placeholder="Start typing..."></textarea>
    </div>
      <div class="bottom">Bottom content goes here.</div>
    </div>
    <div class="right-column">
      <!-- Meant to be empty -->
    </div>
  </section>
</main>

<?php
function drawMenu()
{
    drawFileDropDown();
    drawFindDropDown();
    echo '<li><a href="#">Find Next</a></li>';
    drawFontDropDown();
}
function drawFileDropDown()
{
    echo '<li><a href="#">File</a>
      <ul>
        <li><label for="fileNew" class="menu-label">New File</label>
</li>
        <li>
          <label for="fileInput" class="menu-label">Open File</label>
          <form action=? method="post" enctype="multipart/form-data">
            <input type="file" id="fileInput" name="fileInput" style="display:
            none;" onchange="this.form.submit();">
          </form>
        </li>
        <li><label for="fileSave" class="menu-label">Save File</label></li>
      </ul>
    </li>';
}
function drawFindDropDown()
{
    echo '<li><a href="#">Find</a>
      <ul>
        <li><input type="checkbox" id="caseSensitive"> Case Sensitive</li>
        <li><input type="text" class="menu-textbox" id="searchText" placeholder="Search text"></li>
        <li><button class="menu-button" onclick="#">Find</button></li>
      </ul>
    </li>';
}
function drawFontDropDown()
{
    echo '<li><a href="#">Font</a>
      <ul>
        <li>
          <label for="selectFont">Select Typeface:</label>
          <select id="selectFont" class="menu-select">';
    $mysqlObj = CreateConnectionObject();
    $sql = "SELECT * FROM fontnames";
    $stmt = $mysqlObj->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    foreach ($result as $row) {
        echo '<option value="' .
            $row["fontName"] .
            '">' .
            $row["fontName"] .
            "</option>";
    }
    CloseConnection($mysqlObj);
    echo '</select>
        </li>
        <li>
          <label for="selectFontSize">Select Font Size:</label>
          <select id="selectFontSize" class="menu-select">
            <option value="small">Small</option>
            <option value="medium">Medium</option>
            <option value="large">Large</option>
          </select>
        </li>
      </ul>
    </li>
    </ul>';
    echo "<script>
    document.getElementById(\"selectFontSize\").addEventListener(\"change\", function () {
        var selectedFont = this.value;
        document.getElementById(\"editor\").style.fontFamily = selectedFont;
    });

    document.getElementById(\"selectFontSize\").addEventListener(\"change\", function () {
        var selectedSize = this.value;
        document.getElementById(\"editor\").style.fontSize = selectedSize;
    });
</script>";
}
// TODO: Fix or rewrite the below if we don't want loaded data to persist on F5
function openFile()
{
    $filename = 'editor.dat';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileInput"])) {
        $file = $_FILES["fileInput"];

        // Check for errors during upload
        if ($file["error"] > 0) {
            echo "Error: " . $file["error"];
        } else {
            $contents = file_get_contents($file["tmp_name"]);

            // Display file contents in textarea
            echo '<script>document.getElementById("text").value = ' . json_encode($contents) . ';</script>';
        }
    } elseif (file_exists($filename)) {
        $contents = file_get_contents($filename);
        echo '<script>document.getElementById("text").value = ' . json_encode($contents) . ';</script>';
    } else {
        echo 'Editor.dat does not exist. Please save the file first.';
    }
}
function saveFile($text)
{
    $filename = 'editor.dat';
    $result = file_put_contents($filename, $text);

    if ($result !== false) {
        echo 'File saved.';
    } else {
        echo 'Error Saving File: ' . error_get_last()['message'];
    }
}
function findTextInFile($FileName, $SearchedPhrase)
{
    $content = file_get_contents($FileName);
    $position = strpos($content, $SearchedPhrase);

    if ($position !== false) {
        echo "String $SearchedPhrase not found";
    }
    else{
        echo "$SearchedPhrase was found at position " . ($position + 1) . ".";
    }
}
function findNextInFile($filename, $searchPhrase) {
  if (isset($_COOKIE['previous_position'])) {
      $content = file_get_contents($filename);
      $position = strpos($content, $searchPhrase, $_COOKIE['previous_position']);

      if ($position === false) {
          echo "String $searchPhrase not found";
      } 
      else {
          echo "$searchPhrase was found at position " . ($position + 1) . ".";
          setcookie('previous_position', $position + 1);
      }
  } 
  else {
      findTextInFile($filename, $searchPhrase);
  }
}
?>
