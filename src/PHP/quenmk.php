<?php
    session_start();
    require_once '../PHP/config.php';
    
    function redirect($url, $params = []) {
        $queryString = $params ? '?' . http_build_query($params) : '';
        header("Location: $url$queryString");
        exit();
    }
   // Hàm gửi email 
    function sendEmail($email, $verifycation_code) {
            require '../../PHPMailer-master/src/PHPMailer.php';
            require '../../PHPMailer-master/src/SMTP.php';
            require '../../PHPMailer-master/src/Exception.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->CharSet = "UTF-8";
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = '22022011@st.vlute.edu.vn';
                $mail->Password = 'thanhtu192512';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                $mail->setFrom("22022011@st.vlute.edu.vn","VietTechBlog");
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "Thư gửi mã xác nhận quên mật khẩu";
                $noidungthu =
                        "<p>Chào bạn!</p>
                        <p>Mã xác thực của bạn là: <br> <b> {$verifycation_code} </b> <br>
                        Mã này có hiệu lực trong 5 phút. <br>
                        Trân trọng! <br> <h1>VietTechBlog</h1>";
                        
                $mail->Body = $noidungthu;
                $mail->smtpConnect( array(
                    "ssl" => array(
                        "verify_peer"=> false,
                        "verify_peer_name"=> false,
                        "allow_self_signed" => true
                    )
                ));
                $mail->send();
                return true;
            } catch (Exception $e) {
                echo 'Error: ', $mail->ErrorInfo;
                return false;
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $_SESSION['email'] = $email;
        // Kiểm tra email có tồn tại
        $stmt = $conn->prepare("SELECT * FROM taikhoan WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {

            $stmt = $conn->prepare("SELECT * FROM maxacnhan WHERE user_id = (select id from taikhoan where email = ?)");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            $verifycation_code = substr(md5( rand(0,999999)) , 0 , 6);

            if($stmt->num_rows > 0){
                echo" đã tồn tại chỉ cập nhật";
                $stmt = $conn->prepare("UPDATE maxacnhan SET macode = ? WHERE user_id = (select id from taikhoan where email = ?)");
                $stmt->bind_param("ss", $verifycation_code, $email);
            }else{
                echo"alert 'ko tồn tại thêm vào'";
                $stmt = $conn->prepare("INSERT INTO maxacnhan (macode, user_id) VALUES (?, (select id from taikhoan where email = ?))");
                $stmt->bind_param("ss", $verifycation_code  , $email);
            }
            $stmt->execute();
            $kt = sendEmail($email, $verifycation_code);
            if($kt == true){
                header("location: ../layouts/code.html");
            }
        } else {
           redirect('../layouts/quenmk.html', ['email-error' => 'Đã xảy ra lỗi, vui lòng thử lại']);
        }
    
        $stmt->close();
        $conn->close();
    }
?>