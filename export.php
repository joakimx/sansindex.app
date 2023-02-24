<?php
// Connect to the MySQL database
$conn = mysqli_connect('localhost', 'sans', '', 'SANSINDEX');

// Retrieve the required data from the database
$result = mysqli_query($conn, "SELECT * FROM SEC275 ORDER BY Keywords");

// Output the data in CSV format
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="database_entries.csv"');
$fp = fopen('php://output', 'w');
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($fp, $row);
}
fclose($fp);
exit;
?>

