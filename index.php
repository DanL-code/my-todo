<?php 
$server = "localhost";
$user = "root";
$password = "";
$dbName = "todo";

$conn = new mysqli($server, $user, $password, $dbName);
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <meta name ="viewport" content="width=device-width, initial-scale=1.0">
  <title>To-Do-List-Project</title>
  <link rel="stylesheet" href="style.css">
  <!-- Import icon style url -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

  <!-- Theme Colour -->
  <div class="theme_colour">
      <li >
        <i class="material-icons" style="font-size: 35px;" title="Theme Colour" >palette</i>
  	  	  <menu>
  	  	    <li onclick="changeThemeColour('spring')">Spring</li>
  	  	    <li onclick="changeThemeColour('summer')">Summer</li>
            <li onclick="changeThemeColour('autumn')">Autumn</li>
            <li onclick="changeThemeColour('xmas')">Christmas</li>
            <li onclick="changeThemeColour('default')">Default</li>
  	  	  </menu>	
  	  </li>
  </div>

  <!-- Main Container -->
  <div class="main_container">
    <div class="heading">
      <h1>My To-Do List</h1>
    </div>

    <!-- sorting order in different status-->
    <?php 
          // Pending tasks
        if (isset($_GET['showPending'])) {
          $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';
          $todos = $conn->query("SELECT * FROM todo_list WHERE Completed = 0 ORDER BY Date $order");
          if ($todos && $todos->num_rows <= 0) {
            echo '⚠️No Pending Task⚠️';
            $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';
            $todos = $conn->query("SELECT * FROM todo_list ORDER BY Date $order");
          }
          // Completed tasks
        } elseif (isset($_GET['showCompleted'])) {
          $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';
          $todos = $conn->query("SELECT * FROM todo_list WHERE Completed = 1 ORDER BY Date $order");
          if ($todos && $todos->num_rows <= 0) {
            echo '⚠️No Completed Task⚠️';
            $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';
            $todos = $conn->query("SELECT * FROM todo_list ORDER BY Date $order");
          }
        }else {
          // All todos
          $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';
          $todos = $conn->query("SELECT * FROM todo_list ORDER BY Date $order");
        }
    ?>

    <!-- Add a todo -->
    <div class="add_todo" id="addTodoSection">
      <form action="process.php" method="POST" autocomplete="off">
        <input class="form_input" type="text" placeholder="Add a todo . . ." id="todo" name="task"
        required>
        <input class="date" type="date" name="date" required>
        <input type="hidden" name="order" value="<?php echo $order; ?>">
        <button id="submitBtn" type="submit"><span>Add+</span></button>
      </form>
    </div>

    <!-- hidden form only display when edit a todo and save changes -->
    <div id="edit_form" style="display: none;">
      <form action="process.php" method="POST">
        <input type="hidden" name="edited_id" id="edit_form_id">
        <input type="text" name="edited_task" id="edit_form_task">
        <input type="date" name="edited_date" id="edit_form_date" required>
        <input type="hidden" name="order" value="<?php echo $order; ?>">
        <button type="submit" name="save_edited_todo" id="edit_form_btn"><span>Save Changes</span></button>
      </form>
    </div>

    <!-- Show delete all btn and sorting when number of rows more than 0 -->
    <?php if(mysqli_num_rows($todos) >0 ){ ?>

     <!-- Delete all todos -->
      <form action="process.php" method="POST">
        <button type="submit" name="delete_all" id="delete_all_btn" style="background: none; border: none;" title="Delete All"> <i class="material-icons" style="font-size: 35px;">delete_forever</i></button>
      </form>

     <!-- Status filter -->
      <form action="index.php" method="GET">
      <div class="statusFilter">
        <li >
          <i class="material-icons" style="font-size: 35px;" title="Filter">sort</i>
  	  	    <menu>
  	  	      <li><button type="submit" name="showAll" id="showAll_btn" style="background: none; border: none;"><span>All</span></button></li>
  	  	      <li><button type="submit" name="showPending" id="showPending_btn" style="background: none; border: none;"><span>Pending</span></button></li>
              <li><button type="submit" name="showCompleted" id="showCompleted_btn" style="background: none; border: none;"><span>Completed</span></button></li>
  	  	    </menu>	
  	    </li>
      </div>
      </form>

     <!-- Toggle sorting order -->
      <a href="?order=<?php echo $order === 'asc' ? 'desc' : 'asc'; ?>">
      <button type="submit" name="order_todo" id="order_icon_btn" style="background: none; border: none;" title="Toggle Sorting Order"> <i class="material-icons" style="font-size: 35px;">swap_vert</i></button></a>
    <?php }?>

    <!-- Display an image if there is no task -->
    <?php if(mysqli_num_rows($todos) <= 0){ ?>
      <div class="display_todos">
        <img src="images/to-do-icon.jpeg" width="350px">
      </div>
    <?php }?>

    <!-- Main section to display todos -->
    <?php while ($todo = mysqli_fetch_assoc($todos)){?>
      <div class="display_todos">

        <!-- Delete a todo -->
        <form action="process.php" method="POST">
          <input type="hidden" name="todo_id" value="<?php echo $todo['ID']; ?>">
          <input type="hidden" name="order" value="<?php echo $order; ?>">
          <button type="submit" name="delete_todo" id="delete_icon_btn" style="background: none; border: none;" title="Delete"> <i class="material-icons" >backspace</i></button>
        </form>

        <!-- Edit a todo -->
        <form action="process.php" method="POST">
          <input type="hidden" name="edit_id" value="<?php echo $todo['ID']; ?>">
          <input type="hidden" name="order" value="<?php echo $order; ?>">
          <button type="button" name="edit_todo" id="edit_icon_btn" style="background: none; border: none;" title="Edit" onclick="showEditForm(<?php echo $todo['ID']; ?>, '<?php echo addslashes($todo['Task']); ?>')">
          <i class="material-icons">edit</i></button>
        </form>
        
         <!-- Display todos in different status -->
        <form action="process.php" method="POST">
          <input type="hidden" name="checkbox_id" value="<?php echo $todo['ID']; ?>"> 
          <input type="hidden" name="order" value="<?php echo $order; ?>">
          <?php if($todo['Completed']){ ?>
            <input type="checkbox" class="check_box" <?php echo $todo['Completed'] ? 'checked' : ''; ?> onchange="this.form.submit()">
            <h2 class="completed"> <span class="completed_text">Completed: </span><?php echo $todo['Task']?></h2>
          <?php }else{ ?>
            <input type="checkbox" class="check_box"/>
            <h2 class="pending"> <span class="pending_text">Pending: </span><?php echo $todo['Task']?></h2>
          <?php } ?>
            <small class="date_time"> Due Date: <?php echo $todo['Date']?></small>
        </form>
      </div>
    <?php } ?>
  </div>

  <!-- JavaScript doc -->
  <script src="script.js"></script>

  <!-- Add a footer -->
  <footer>
    <p>(C) 2023 Made with ❤️ by Dan Luo</p>
  </footer>
</body>
</html>





