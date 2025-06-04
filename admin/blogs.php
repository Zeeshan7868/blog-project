<?php

session_start();
require("../require/dashboard_layout.php");
require("../require/db_connection/connection.php");

// require("../require/layout.php");
// require("../functions/user_functions.php");


if (isset($_SESSION['user']['email']) and $_SESSION['user']['role_id'] == 2) {
  header("location:../index.php");
  die();
}



// user_navbar();
admin_header();
admin_navbar();

?>


<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="heading-2">All Blogs</h2>
    <a href="add_blog.php" class="btn btn-primary btn-sm">+ New Blog</a>
  </div>


  <div>

    <div class="table-responsive" id="table-responsive">
      <table id="blogsTable" class="table table-hover align-middle display nowrap" style="width:100%">

      </table>
    </div>
  </div>
</main>


<script>
  function show_blogs() {
    // document.getElementById('loadingModal').style.display = 'none';

    var ajax = null;
    if (window.XMLHttpRequest) {
      ajax = new XMLHttpRequest;
    } else {
      ajax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    ajax.onreadystatechange = function() {
      if (ajax.readyState === 4 && ajax.status === 200) {

        document.getElementById("blogsTable").innerHTML = ajax.responseText;

        $('#blogsTable').DataTable({
          destroy: true,
          responsive: true,
          autoWidth: false,
          lengthChange: true,
          pageLength: 5,
          scrollX: true
        });



      }
    };

    ajax.open("POST", "../processes/blog_process.php");
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("action=show_blogs");
  }

  show_blogs();

  function active_blog(blog_id) {

    // var activeValue = document

    var activeVal = document.getElementById("is_active" + blog_id).innerHTML;
    // console.log(is_active);


    // console.log(blog_id);

    var ajax_request = null
    if (window.XMLHttpRequest) {
      ajax_request = new XMLHttpRequest;
    } else {
      ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
    }

    ajax_request.onreadystatechange = function() {
      if (ajax_request.readyState === 4 && ajax_request.status === 200) {

        document.getElementById("is_active" + blog_id).innerHTML = ajax_request.responseText;
        show_blogs();
        // document.getElementById('loadingModal').style.display = 'none';
      }
    }

    ajax_request.open("POST", "../processes/blog_process.php");
    ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax_request.send("action=is_active&blog_id= " + blog_id + "&active_value=" + activeVal);

  }

  function delete_blog(blog_id) {
    if (!confirm("Are you sure you want to delete this blog?")) return;

    var ajax = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

    ajax.onreadystatechange = function() {
      if (ajax.readyState === 4 && ajax.status === 200) {
        alert(ajax.responseText.trim());
        show_blogs();
      }
    };

    ajax.open("POST", "../processes/blog_process.php", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("action=delete&blog_id=" + blog_id);
  }
</script>


<?php
admin_footer();
?>