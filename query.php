<?php
include("conn.php");

// Function to get the department name from the kskdepartment table based on depcode
function getDepartmentName($depcode) {
    global $conn;
    
    $sql = "SELECT department FROM kskdepartment WHERE depcode = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $depcode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['department'];
    } else {
        return $depcode; // Return the depcode itself if no department is found
    }

    $stmt->close();
}

// Function to get the request result and transform Line_name to name
function getR_Name($LIname) {
    global $conn;
    
    $sql = "SELECT R_name FROM stretcher_request_staff WHERE Line_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $LIname);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['R_name'];
    } else {
        return $LIname; // Return the depcode itself if no department is found
    }

    $stmt->close();
}
function getRequestResult() {
    global $conn;
    $requestResult = array(
        "ID"=> "",
        "Caller"=> "",
        "status"=> "",
        "location"=> "",
        "Type"=> "",
        "locations"=> "",
        "Sender"=> "",
        "Patient"=> "",
        "reciver"=> "",
        "PatientID"=> "",
        "stc_send_time"=> "",
        "stc_return_time"=> "",
    );

    // Mapping for status types
    $typeMapping = array(
        1 => 'ไม่ด่วน',   
        2 => 'น้อย',     
        3 => 'ปานกลาง',   
        4 => 'ด่วน',      
        5 => 'ด่วนมาก'    
    );
    
    // Mapping for stretcher types
    $stcType = array(
        1 => 'นอน',
        3 => 'นั่ง',
        4 => 'นั่ง(มีล้อแล้ว)',
        5 => 'ล้อเข็นนอนออกซิเจน'
    );
    
    // SQL query to get the highest priority stretcher request
    $sql = "SELECT * FROM stretcher_register 
            WHERE stretcher_work_status_id  = 1 
            ORDER BY stretcher_register_id ASC 
            LIMIT 1";
    
    error_log("Executing query: $sql");

    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Fill request result array with data
            $requestResult['ID'] = $row['stretcher_register_id'] ?? '';
            $requestResult['Caller'] = $row['doctor_request'] ?? '';
            $requestResult['status'] = $typeMapping[$row['stretcher_work_status_id']] ?? '';
            
            // Transform depcode to department name
            $requestResult['location'] = getDepartmentName($row['from_depcode'] ?? '');
            $requestResult['locations'] = getDepartmentName($row['send_depcode'] ?? '');

            $requestResult['Type'] = $stcType[$row['stretcher_type_id']] ?? '';
            $requestResult['Patient'] = $row['hn'] ?? '';
            
            // Transform ผู้รับ to R_name
            $requestResult['reciver'] = getR_Name($row['ผู้รับ'] ?? '');

            $requestResult['stc_send_time'] = $row['เวลารับ'] ?? '';
            $requestResult['stc_return_time'] = $row['เวลาส่ง'] ?? '';

        } else {
            error_log("No rows found with stretcher_work_status_id  = 1");
        }

        $stmt->close();
    } else {
        error_log("Error preparing the query: " . $conn->error);
    }

    return $requestResult;
}
?>
