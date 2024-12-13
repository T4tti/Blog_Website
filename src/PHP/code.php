<?php
    session_start();
    require_once '../PHP/config.php';

    function redirect($url, $params = []) {
    $queryString = $params ? '&' . http_build_query($params) : '';
    header("Location: $url$queryString");
    exit();
}


    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $macode = isset($_POST['code']) ? $_POST['code'] : '';

        $stmt = $conn->prepare("SELECT * FROM maxacnhan WHERE macode = ? ");
        $stmt->bind_param("s", $macode);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            redirect('../layouts/personal.php?tab=password', ['forget' => 'true']);
            exit();
        }

    }
?>