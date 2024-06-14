<?php
include 'db.php';
 
// init output
$output = '';
 
// lakukan query select order by untuk mengurutkan komentar baru pada tbl_komentar
$query = "SELECT * FROM tbl_komentar WHERE parent_id = 0 ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$res = $stmt->get_result();
 
// looping data
while ($row = $res->fetch_assoc()) {
 
  // lakukan join output
  $output .= '
  <div class="card border border-1 p-3 mb-2 me-1 bg-white text-dark">
      <i class="bi bi-person-circle" style="font-size:30px;"> <span class="fst-normal fw-bold" style="font-size:19px;">' .$row["nama"] . '</span></i>
    <div class="card-body">
      <p>' . $row["komentar"] . '</p>
    </div>
      <div class="col-se-5 mt-5">
      <i class="bi bi-clock"> ' . $row["tanggal_komen"] . '</i> 
      <button type="button" class="btn btn-primary float-end reply" id="' . $row["id"] . '">Reply</button>
      </div>
  </div>
';
 
  $output .= ambil_reply($db, $row["id"]);
}
 
// tampilkan
echo $output;
 
 
// function ambil_reply di gunakan untuk membalas komentar pengguna
function ambil_reply($db, $parent_id = 0)
{
  $output = '';
  $query = "SELECT * FROM tbl_komentar WHERE parent_id=?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $parent_id);
  $stmt->execute();
  $res = $stmt->get_result();
  $count = $res->num_rows;
 
  if ($count > 0) {
    while ($row = $res->fetch_assoc()) {
      $output .= '
        <div class="card border border-1 p-3 mb-2 bg-white text-dark me-2" style="margin-left:30px;">
        <i class="bi bi-person-circle" style="font-size:30px;"> <span class="fst-normal fw-bold" style="font-size:19px;">' .$row["nama"] . '</span></i>
          <div class="card-body">
              <p>' . $row["komentar"] . '</p>
            </div>
            <div class="col-se-5 mt-5">
            <i class="bi bi-clock"> ' . $row["tanggal_komen"] . '</i> 
            <button type="button" class="btn btn-primary float-end reply" id="' . $row["id"] . '">Reply</button>
          </div>   
        </div>   
        ';
 
      $output .= ambil_reply($db, $row["id"]);
    }
  }
 
  return $output;
}
 
 
?>