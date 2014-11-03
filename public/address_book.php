<?php require_once 'resources/includes/address.php' ; ?>
<html>
<head>
    <title>Address Book Application</title>
    <link rel="stylesheet" href="resources/css/bootstrap.min.css">
    <link rel="stylesheet" href="resources/css/address_book.css">
    <script src="resources/js/jquery.min.js"></script>
    <script src="resources/js/bootstrap.min.js"></script>
</head>
<body>s
<div class="container">

<!-- Button trigger modal -->
<div class="row" id="row_with_button">
    <button id="addContactButton" type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Add Contact</button>
</div> <!-- END Button Row div -->

<div class="row" id="main_row">
    <div id="contactList" class="col-md-12">
        <h1>Contacts</h1>
        <table id="contactsTable" class="table table-bordered table-hover">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>City</th>
                <th>State</th>
                <th>Delete</th>
            </tr>
      <!-- Loop through each of the addresses and output -->
                <?php
                    foreach ($contacts as $key => $value) {
                        echo "<tr>";
                        foreach ($value as $key2 => $value2) {
                            echo "<td>$value2</td>";
                        }
                        echo "<td><a href=\"?id=$key\"><span class=\"glyphicon glyphicon-remove\"></span></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
    </table>
    </div> <!-- contact list div -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModal">Add New Contact</h4>
      </div> <!-- modal header -->
        <!-- MODAL BODY WITH FORM -->
      <div class="modal-body">
        <form id="newContactForm" method="POST" action="/address_book.php" class="form-horizontal" role="form">
            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="city" name="city" placeholder="City">
            </div>
            </div>
            <div class="form-group">
            <div class="col-sm-10">
                <input type="text" class="form-control" id="state" name="state" placeholder="State">
            </div>
            </div>
      </div> <!-- modal body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Contact</button>
      </div> <!-- modal-footer -->
        </form>
    </div> <!-- modal-content -->
  </div> <!-- modal-dialogue -->
</div> <!-- master modal-div -->
<!-- end MODAL -->

</div> <!-- contact list row div -->
</div> <!-- site container div -->
</body>
</html>
