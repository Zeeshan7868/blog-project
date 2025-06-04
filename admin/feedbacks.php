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
?>

<style>
  
  #feedbackTable td:nth-child(5),
  #feedbackTable th:nth-child(5) {
    width: 500px;           
    white-space: normal;    
    word-wrap: break-word;
  }
</style>

<main class="col-12 col-md-9 dashboard p-4 mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="heading-2">All Feedback</h2>
  </div>

  <div class="table-responsive" style="overflow-x:auto;">
    <table id="feedbackTable" class="table table-hover align-middle display nowrap" style="width:100%">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>User ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Feedback</th>
          <th>Created At</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT * FROM user_feedback ORDER BY created_at DESC";
        $result = mysqli_query($connection, $query);

        $serial = 1;
        while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $serial++ ?></td>
            <td><?= $row['user_id'] ?></td>
            <td><?= $row['user_name'] ?></td>
            <td><?= $row['user_email'] ?></td>
            <td>
              <p class="text-decoration-none" >
                <?= $row['feedback'] ?>
              </p>
            </td>
            <td><?= $row['created_at'] ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>

<script>
  $(document).ready(function() {
    $('#feedbackTable').DataTable({
      responsive: true,
      paging: true,
      searching: true,
      ordering: true,
      order: [[1, 'desc']],
      columnDefs: [
        { orderable: false, targets: 4 }
      ]
    });
  });
</script>

<?php
admin_footer();
?>
