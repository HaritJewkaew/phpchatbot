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
        "locations"=> "",
        "Sender"=> "",
        "Patient"=> "",
        "reciver"=> "",
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
                $requestResult['location'] = $row['สถานที่รับ'];
                $requestResult['Type'] = $row['ประเภทเปล'];
                $requestResult['locations'] = $row['สถานที่ส่ง'];
                //$requestResult['Sender'] = $row['ผู้ส่ง'];
                $requestResult['Patient'] = $row['ชื่อผู้ป่วย'];
                $requestResult['reciver'] = $row['ผู้รับ'];
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
                    $requestResult['location'] = $row['สถานที่รับ'];
                    $requestResult['Type'] = $row['ประเภทเปล'];
                    $requestResult['locations'] = $row['สถานที่ส่ง'];
                    //$requestResult['Sender'] = $row['ผู้ส่ง'];
                    $requestResult['Patient'] = $row['ชื่อผู้ป่วย'];
                    $requestResult['reciver'] = $row['ผู้รับ'];
                }
            }
        }
    }

    $stmt->close();
    return $requestResult;

    $sql = "SELECT * FROM project";
    $result = $conn->query($sql);
    
    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $requestResult['ID']. " - Name: " . $requestResult['Caller']. "<br>";
        }
    } else {
        echo "0 results";
    }
    
    // Close connection
    $conn->close();

}
?>