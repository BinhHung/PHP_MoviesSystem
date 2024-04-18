<?php 
include('../connect.php');

if(!isset($_SESSION['uid'])){
  echo "<script> window.location.href='../login.php';  </script>";
}

function filterData(&$str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str). '"';
}

$fileName = "booking-data_" . date('d-m-y') . ".xls";

$fields = array('ID', 'Theater Name', 'Movie', 'Date', 'Person Number', 'User Name', 'Status', 'Total Price');

$excelData = implode("\t", array_values($fields)) . "\n";

$sql = "SELECT b.bookingid, t.theater_name, m.title , b.bookingdate, b.person, u.name, b.status, b.totalprice
          FROM booking b
          JOIN theater t ON b.theaterid = t.theaterid
          JOIN movies m ON t.movieid = m.movieid
          JOIN users u ON b.userid = u.userid
          ORDER BY b.bookingid";

$res = mysqli_query($con, $sql);

if(mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
        $status = ($row['status'] == 1) ? 'Approved' : 'Pending';
        $lineData = array($row['bookingid'], $row['theater_name'], $row['title'], $row['bookingdate'], $row['person'], $row['name'], $status, $row['totalprice']);
        array_walk($lineData, 'filterData');
        $excelData .= implode("\t", array_values($lineData)) . "\n";
    }
} else {
    $excelData .= 'No records found...' . "\n";
}



header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

echo $excelData;
exit();
?>
