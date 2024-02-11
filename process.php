<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $dbName = "todo";
    
    $conn = new mysqli($server, $user, $password, $dbName);

    // add a todo
    if(isset($_POST['task'])){
        
        echo"00000";
        $task = $_POST['task'];
        $date = $_POST['date'];

        $stmt = $conn->prepare("INSERT INTO todo_list (Task, Date) VALUES (?, ?)");
        $stmt->bind_param("ss", $task, $date);

        $res = $stmt->execute();

        if($res){
            $currentOrder = isset($_POST['order']) ? $_POST['order'] : 'asc';
            header("Location: http://localhost/my_todo/index.php?order=$currentOrder");
            exit();
        } else {
            echo "Error";
        }

        $conn = null;

    // delete a todo
    }elseif (isset($_POST['delete_todo'])) {
        $todo_id = $_POST['todo_id'];
        $stmt = $conn->prepare("DELETE FROM todo_list WHERE ID = ?");
        $res = $stmt->execute([$todo_id]);

        if ($res) {
            $currentOrder = isset($_POST['order']) ? $_POST['order'] : 'asc';
            header("Location: http://localhost/my_todo/index.php?order=$currentOrder");
        } else {
            echo "Error deleting todo.";
        }

        $conn = null;
        echo "5555";
        exit();

    // edit a todo
    } elseif (isset($_POST['edit_todo'])) {
        $todo_id = $_POST['edit_id'];
        $stmt = $conn->prepare("SELECT * FROM todo_list WHERE ID = ?");
        $stmt->bind_param("i", $todo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingTodo = $result->fetch_assoc();

    } elseif (isset($_POST['save_edited_todo'])) {
        $edited_id = $_POST['edited_id'];
        $edited_task = $_POST['edited_task'];
        $edited_date = $_POST['edited_date'];

        $stmt = $conn->prepare("UPDATE todo_list SET Task = ? , Date = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $edited_task, $edited_date, $edited_id);

        $res = $stmt->execute();

        if ($res) {
            $currentOrder = isset($_POST['order']) ? $_POST['order'] : 'asc';
            header("Location: http://localhost/my_todo/index.php?order=$currentOrder");
            exit();
        } else {
            echo "Error updating todo.";
        }

    // mark a todo as completed or pending
    }elseif (isset($_POST['checkbox_id'])) {
        $todo_id = $_POST['checkbox_id'];
        // get current order
        $order = isset($_POST['order']) ? $_POST['order'] : 'asc';
        $stmt = $conn->prepare("UPDATE todo_list SET Completed = NOT Completed WHERE ID = ?");
        $res = $stmt->execute([$todo_id]);
    
        if ($res) {
            $_SESSION['scrollposition'] = $_SERVER['HTTP_REFERER'] . '#' . $_POST['checkbox_id'];
            // set the current order
            $currentOrder = isset($_POST['order']) ? $_POST['order'] : 'asc';
            header("Location: http://localhost/my_todo/index.php?order=$currentOrder");
            exit();
        } else {
            echo "Error";
        }
    
        $conn = null;
        exit();

    // delete all todos
    } elseif (isset($_POST['delete_all'])) {
        $stmt = $conn->prepare("DELETE FROM todo_list");
        $res = $stmt->execute();

        if ($res) {
            header("Location: http://localhost/my_todo/index.php");
        } else {
            echo "Error deleting all todos.";
        }

        $conn = null;
        exit();
    }else {
        header("Location: http://localhost/my_todo/index.php");
        echo "2222";
        exit();
    }
?>
