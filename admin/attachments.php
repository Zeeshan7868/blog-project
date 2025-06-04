<?php

session_start();
require("../require/dashboard_layout.php");
require("../require/layout.php");
require("../require/db_connection/connection.php");
// require("../functions/user_functions.php");

if (isset($_SESSION['user']['email']) and $_SESSION['user']['role_id'] == 2) {
  header("location:../index.php");
  die();
}


$type = isset($_GET['type']) ? $_GET['type'] : 'all';

admin_header();
admin_navbar();

?>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="heading-2">Post Attachments</h2>
  </div>

  <div class="table-responsive" id="table-responsive" style="overflow-x:auto;">
  </div>
</main>

<script>

  function show_all(type){
    var ajax_request = null;

    if(window.XMLHttpRequest){
      ajax_request = new XMLHttpRequest();
    }else{
      ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
    }

    ajax_request.onreadystatechange = function(){
      if(ajax_request.readyState === 4 && ajax_request.status === 200) {
        document.getElementById("table-responsive").innerHTML = ajax_request.responseText;

        $('#attachmentsTable').DataTable({
          destroy: true,
          responsive: true,
          autoWidth: false,
          lengthChange: true,
          pageLength: 20,
          scrollX: true
        });
      }
    }

    ajax_request.open("POST", "../processes/attachment_process.php");
    ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax_request.send("action=show_all&type=" + type);
  }

  
  show_all("<?= $type ?>");

  function toggleAttachmentStatus(attachment_id, currentStatus) {
    const activeVal = currentStatus === 'Active' ? 'InActive' : 'Active';

    var ajax_request = null;
    if (window.XMLHttpRequest) {
      ajax_request = new XMLHttpRequest();
    } else {
      ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
    }

    ajax_request.onreadystatechange = function () {
      if (ajax_request.readyState === 4 && ajax_request.status === 200) {
        document.getElementById("is_active" + attachment_id).innerHTML = ajax_request.responseText;
        
        show_all("<?= $type ?>");
      }
    }

    ajax_request.open("POST", "../processes/attachment_process.php");
    ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax_request.send("action=is_active&attachment_id=" + attachment_id + "&active_value=" + activeVal);
  }

</script>

<?php
admin_footer();
?>
