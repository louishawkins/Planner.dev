<?php require_once 'resources/includes/todo.php' ; ?>
<html>
<head>
    <title>To-Do List Application</title>
    <link rel="stylesheet" href="resources/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/css/todo_list.css">
    <script src="resources/js/jquery.js"></script>
    <script src="resources/js/bootstrap.min.js"></script>
</head>
<body>

<!-- NAVIGATION BAR -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <ul class="nav navbar-nav">
        <li id="title">TO-DO List</li>
        <li id="additem"><a href="#" data-toggle="modal" data-target="#addRecordModal"><span class="glyphicon glyphicon-plus"></span>New Item</a></li>
    </ul>
  </div>
</nav>

<!-- MAIN BODY -->
<div class="container" id="records_container">
<div class="row" id="table_row">
<!-- SIDEBAR -->
    <div id="sidebar" class="col-md-2">
        <ul>
            <li><a href="?list=active">Active (<? echo $total_active; ?>)</a></li>
            <li><a href="?list=completed">Completed (<? echo $total_completed; ?>)</a></li>
            <li><a href="?list=removed">Removed (<? echo $total_removed; ?>)</a></li>
        </ul>
    </div> <!-- end SIDEBAR div -->
<!-- LIST VIEWER -->
    <div id="items" class="col-md-10">
            <table id="todo_items_table" class="table table-bordered table-hover">
                <tr>
                    <th width="5"><span class="glyphicon glyphicon-ok"></span></th>
                    <th width="5"><span class="glyphicon glyphicon-fire"></span></th>
                    <th width="50">Item</th>
                    <th width="5px"><span class="glyphicon glyphicon-remove-circle"></span></th>
                </tr>
            <?php
                foreach($rows as $row) {
                    echo "<tr>";
                        echo "<td><a href=\"?complete={$row['id']}\"><span class=\"glyphicon glyphicon-ok\"></span></a></td>";
                        echo "<td>{$row['priority']}</td>";
                        echo "<td>{$row['content']}</td>";
                        echo "<td><a href=\"?remove={$row['id']}\"><span class=\"glyphicon glyphicon-remove-circle\"></span></a></td>";
                    echo "</tr>";
                }
            ?>
            </table>
    </div> <!-- contactList -->
</div> <!-- table_row -->
<div class="row" id="pagination">
    <nav id="pager">
        <ul class="pager">
            <?php if($page > 1) {echo $Previous;}?>
            <?php if($page < $lastpage) {echo $Next;}?>
        </ul>
    </nav>
</div>
<!--modal -->
<div class="modal fade" id="addRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModal">Add New Record</h4>
      </div> <!-- modal header -->
        <!-- MODAL BODY WITH FORM -->
      <div class="modal-body">
        <form id="newRecordForm" method="POST" action="/todo_list.php" class="form-horizontal" role="form">

            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="content" name="content" placeholder="Drop all the bass">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <label for="due">Due</label>
                <input type="date" class="form-control" id="due" name="due">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <label for="priority">High Priority</label>
                <input type="checkbox" class="form-control" id="priority" name="priority">
            </div>
            </div>
      </div> <!-- modal body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Record</button>
      </div> <!-- modal-footer -->
        </form>
    </div> <!-- modal-content -->
  </div> <!-- modal-dialogue -->
</div> <!-- master modal-div -->
<!-- end MODAL -->

</div> <!-- container -->

 

</body>
</html>
