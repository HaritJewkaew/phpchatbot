<?php
include("conn.php");

function getRequestResult() {
    global $conn;
    $requestResult = array(
        "ID"=> "1",
        "Caller"=> "",
        "status"=> "",
        "location"=> "",
        "Type"=> "",
    );
    $sql = "SELECT * FROM request WHERE สถานะ = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $urgentRowFound = false;
        while ($row = $result->fetch_assoc()) {
            if ($row['ความเร่งด่วน'] == 'ด่วนมาก') {
                $requestResult['ID'] = $row['Info_id'];
                $requestResult['Caller'] = $row['ผู้เรียก'];
                $requestResult['status'] = $row['ความเร่งด่วน'];
                $requestResult['location'] = $row['สถานที่'];
                $requestResult['Type'] = $row['ประเภทเปล'];
                $urgentRowFound = true;
                break;
            }
        }

        if (!$urgentRowFound) {
            mysqli_data_seek($result, 0);
            while ($row = $result->fetch_assoc()) {
                if ($row['ความเร่งด่วน'] == 'ด่วน') {
                    $requestResult['ID'] = $row['Info_id'];
                    $requestResult['Caller'] = $row['ผู้เรียก'];
                    $requestResult['status'] = $row['ความเร่งด่วน'];
                    $requestResult['location'] = $row['สถานที่'];
                    $requestResult['Type'] = $row['ประเภทเปล'];
                    break;
                }
            }
        }
    }

    $stmt->close();
    return $requestResult;
}
?>