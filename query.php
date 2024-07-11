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
        "patientID"=> "",
    );
    $sql = "SELECT * FROM stretcher_register WHERE สถานะ = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $urgentRowFound = false;
        while ($row = $result->fetch_assoc()) {
            if ($row['ความเร่งด่วน'] == 'ด่วนมาก') {
                $requestResult['ID'] = $row['stretcher_register_id'];
                $requestResult['Caller'] = $row['doctor_request'];
                $requestResult['status'] = $row['ความเร่งด่วน'];
                $requestResult['location'] = $row['from_note'];
                $requestResult['Type'] = $row['ประเภทเปล'];
                $requestResult['locations'] = $row['send_note'];
                //$requestResult['Sender'] = $row['ผู้ส่ง'];
                $requestResult['Patient'] = $row['ชื่อผู้ป่วย'];
                $requestResult['PatientID'] = $row['hn'];
                $requestResult['reciver'] = $row['ผู้รับ'];
                $urgentRowFound = true;
                break;
            }
        }

        if (!$urgentRowFound) {
            mysqli_data_seek($result, 0);
            while ($row = $result->fetch_assoc()) {
                if ($row['ความเร่งด่วน'] == 'ด่วน') {
                    $requestResult['ID'] = $row['stretcher_register_id'];
                    $requestResult['Caller'] = $row['doctor_request'];
                    $requestResult['status'] = $row['ความเร่งด่วน'];
                    $requestResult['location'] = $row['from_note'];
                    $requestResult['Type'] = $row['ประเภทเปล'];
                    $requestResult['locations'] = $row['send_note'];
                    //$requestResult['Sender'] = $row['ผู้ส่ง'];
                    $requestResult['Patient'] = $row['ชื่อผู้ป่วย'];
                    $requestResult['PatientID'] = $row['hn'];
                    $requestResult['reciver'] = $row['ผู้รับ'];
                }
            }
        }
    }

    $stmt->close();
    return $requestResult;

    $sql = "SELECT * FROM stretcher_register";
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