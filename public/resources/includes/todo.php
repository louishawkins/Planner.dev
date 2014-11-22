<?
require 'todo_config.php';

class Todo {
    private $new_entry = array();
    private $dbc;
    public  $passed_validation = false;
    
    function __construct($incoming_post, $dbc) {
        $this->dbc = $dbc;
        $this->sanitize($incoming_post);
    }

    private function sanitize($array) {
        foreach ($array as $key => $value) {
            $array[$key] = htmlspecialchars(strip_tags($value));  
        }
        $this->validate($array);
    }

    private function validate($array) {
        $fail_validation = empty($array['content']) ? true : false;
        
        if($fail_validation == false) {
            $this->passed_validation = true;
            $this->new_entry['content']  =  $array['content'];
            if(isset($array['priority'])) {
                $this->new_entry['priority'] =  $array['priority'];
            } else {
                $this->new_entry['priority'] = null;
            }
            if(isset($array['due'])) {
                $this->new_entry['due']      =  $array['due'];
            } else {
                $this->new_entry['due'] = null;
            }
        }
    }

    public function insert() {
        $query = "INSERT INTO items (priority, content, due)
          VALUES (:priority, :content, :due)";

        $stmt = $this->dbc->prepare($query);

        $stmt->bindValue(':priority',  $this->new_entry['priority'],  PDO::PARAM_STR);
        $stmt->bindValue(':content',   $this->new_entry['content'],   PDO::PARAM_STR);
        $stmt->bindValue(':due',       $this->new_entry['due'],       PDO::PARAM_STR);
    
        $stmt->execute();
    }
}

class InvalidInputException extends Exception {
function validate($string) {
    try {
        if(strlen($string) == 0) {
            throw new InvalidInputException("Input cannot be empty!  :(");  
        }
        elseif(strlen($string) > 240) {
            throw new InvalidInputException("Input cannot be longer than 240 characters!  :(");   
        } else {return true;}
    } catch(InvalidInputException $e) {
        $errorMessage = $e->getMessage();
        echo "<div class='alert alert-danger' role='alert'> $errorMessage </div>";
    }
}
} // end class InvalidInputException

// Are we adding any new items?
if(!empty($_POST)) {
    $new_todo = new Todo($_POST, $dbc);
    $passed_validation = $new_todo->passed_validation;
    if($passed_validation) {
        $new_todo->insert();
    }
}

$rows = array();

// pagination variables
$items_per_page = 10;
$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
$offset = ($page - 1) * 10;   

// Are we removing anything? ... then do this.
if(isset($_GET['remove'])) {
    $remove = "UPDATE items SET removed = 1 WHERE id = :id";
    $stmt = $dbc->prepare($remove);
    $stmt->bindValue(':id', $_GET['remove'], PDO::PARAM_INT);
    $stmt->execute();
}

// Are we marking anything as completed? ... then do this.
if(isset($_GET['complete'])){
    $complete = "UPDATE items SET completed = 1 WHERE id = :id";
    $stmt = $dbc->prepare($complete);
    $stmt->bindValue(':id', $_GET['complete'], PDO::PARAM_INT);
    $stmt->execute();
}


// Determine the total number of active records
$rows_active = $dbc->prepare("SELECT * FROM items WHERE removed = 0 AND completed = 0");
$rows_active->execute();
$total_active = $rows_active->rowCount();

// total number of completed records
$rows_completed = $dbc->prepare("SELECT * FROM items WHERE removed = 0 AND completed = 1");
$rows_completed->execute();
$total_completed = $rows_completed->rowCount();

// total number of removed records
$rows_removed = $dbc->prepare("SELECT * FROM items WHERE removed = 1");
$rows_removed->execute();
$total_removed = $rows_removed->rowCount();

// pagination for different categories of lists
if(isset($_GET['list'])) {
    if($_GET['list'] == "completed") {
        $query = "SELECT priority, content, id FROM items WHERE completed = 1 AND removed = 0  LIMIT :items_per_page OFFSET :offset";
        $total_rows_to_display = $total_completed;
    } elseif ($_GET['list'] == "removed") {
        $query = "SELECT priority, content, id FROM items WHERE removed = 1 LIMIT :items_per_page OFFSET :offset";
        $total_rows_to_display = $total_removed;
    } elseif ($_GET['list'] == "active") {
        $query = "SELECT priority, content, id FROM items WHERE completed = 0 AND removed = 0 LIMIT :items_per_page OFFSET :offset";
        $total_rows_to_display = $total_active;
    }
} else {
    $query = "SELECT priority, content, id FROM items WHERE completed = 0 AND removed = 0 LIMIT :items_per_page OFFSET :offset";
    $total_rows_to_display = $total_active;
}
// Select the rows to be displayed per page
$stmt = $dbc->prepare($query);
$stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // high priority <span class=\"glyphicon glyphicon-fire\"></span>
    if(isset($row['priority'])) {
        $row['priority'] = "<span class=\"glyphicon glyphicon-fire\"></span>";
    }
    $rows[] = $row;
}

$lastpage = (ceil($total_rows_to_display/$items_per_page));
$prev = $page == 1 ? 1 : $page - 1;
$next = $page == $lastpage ? $lastpage : $page + 1;  

// Previous/Next buttons for rendering in HTML
$Previous = "<li><a href=\"?page=$prev\">Previous</a></li>";
$Next = "<li><a href=\"?page=$next\">Next</a></li>";

?>
