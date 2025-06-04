<?php
session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");

if (isset($_SESSION['user']['email']) && $_SESSION['user']['role_id'] == 2) {
  header("location:../index.php");
  die();
}

admin_header();
admin_navbar();


$type = isset($_GET['type']) ? $_GET['type'] : 'all';
?>
<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    
    <h2 class="heading-2"><?= ucfirst($type) ?> Categories</h2>
    <a href="add_category.php" class="btn btn-primary btn-sm">+ New Category</a>
  </div>
  <div class="table-responsive" id="table-responsive"></div>
</main>

<script>
  function show_categories() {
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
      if (ajax.readyState === 4 && ajax.status === 200) {
        document.getElementById("table-responsive").innerHTML = ajax.responseText;
        $('#categoriesTable').DataTable({
          destroy: true,
          responsive: true,
          autoWidth: false,
          lengthChange: true,
          pageLength: 5,
          scrollX: true
        });
      }
    };
    ajax.open("POST", "../processes/category_process.php");
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    ajax.send("action=show_categories&type=<?= $type ?>");
  }

  function toggle_category(cat_id) {
    var status = document.getElementById("status" + cat_id).innerText;
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
      if (ajax.readyState === 4 && ajax.status === 200) {
        show_categories();
      }
    };
    ajax.open("POST", "../processes/category_process.php");
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // also re-send type
    ajax.send("action=toggle_status&category_id=" + cat_id + "&current_status=" + status + "&type=<?= $type ?>");
  }

  function delete_category(cat_id) {
    if (confirm("Are you sure you want to delete this category?")) {
      var ajax = new XMLHttpRequest();
      ajax.onreadystatechange = function() {
        if (ajax.readyState === 4 && ajax.status === 200) {
          if (ajax.responseText.trim() === "success") {
            show_categories();
          } else {
            alert("Failed to delete category.");
          }
        }
      };
      ajax.open("POST", "../processes/category_process.php");
      ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      ajax.send("action=delete&category_id=" + cat_id);
    }
  }


  show_categories();
</script>

<?php admin_footer(); ?>