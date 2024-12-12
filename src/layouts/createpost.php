<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../CSS/createarticle.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  <title>New Post | VietTechBlog</title>
  <link rel="icon" type="img/png" href="../Assets/favicon-32x32.png" sizes="favicon-32x32" />
</head>

<body>
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <div class="editor-container">
    <form action="../PHP/createpost.php" method="POST" onsubmit="return submitPost()">
      <div class="editor-toolbar1">
        <input type="text" class="editor-input" id="title" name="title" placeholder="Tiêu đề" />
        <button type="submit" class="toolbar-button">
          Xuất bản bài viết
        </button>
      </div>
    <div class="editor-toolbar2">
      <div class="toolbar-buttons">
        <button type="button" class="fas fa-bold" onclick="toggleStyle(this, 'bold')"></button>
        <button type="button" class="fas fa-italic" onclick="toggleStyle(this, 'italic')"></button>
        <button type="button" class="fas fa-strikethrough" onclick="toggleStyle(this,'strikethrough')"></button>
        <button type="button" class="fas fa-heading" onclick="toggleStyle(this, 'heading')"></button>
        <button type="button" class="fas fa-list-ul" onclick="toggleStyle(this, 'insertUnorderedList')"></button>
        <button type="button" class="fas fa-list-ol" onclick="toggleStyle(this, 'insertOrderedList')"></button>
        <button type="button" class="fas fa-superscript" onclick="toggleStyle(this, 'superscript')"></button>
        <button type="button" class="fas fa-align-left" onclick="toggleStyle(this, 'justifyLeft')"></button>
        <button type="button" class="fas fa-align-center" onclick="toggleStyle(this, 'justifyCenter')"></button>
        <button type="button" class="fas fa-align-right" onclick="toggleStyle(this, 'justifyRight')"></button>
        <button type="button" class="fas fa-align-justify" onclick="toggleStyle(this, 'justifyFull')"></button>
        <button type="button" class="fas fa-eye" onclick="toggleStyle(this, 'preview')"></button>
        <button type="button" class="fas fa-expand" onclick="toggleStyle(this, 'fullscreen')"></button>
        <button type="button" class="fas fa-undo" onclick="toggleStyle(this, 'undo')"></button>
        <button type="button" class="fas fa-redo" onclick="toggleStyle(this, 'redo')"></button>
        <button type="button" class="fas fa-question-circle" onclick="toggleStyle(this, 'help')"></button>
        <button type="button" class="fas fa-info-circle" onclick="toggleStyle(this, 'info')"></button>
        <!-- Add more formatting buttons as needed -->
    </div>
</div>
<div contenteditable="true" class="editor-textarea" id="textEditor" placeholder="Nội dung bài viết..."></div>
<textarea id="hiddenContent" name="content" style="display:none;"></textarea>
</form>
  </div>
  <script>
    function toggleStyle(button, command) {
      const editor = document.getElementById("textEditor");
      const container = document.querySelector(".editor-container");
      const toolbar1 = document.querySelector(".editor-toolbar1");
      const toolbar2 = document.querySelector(".editor-toolbar2");
      const inputTitle = document.querySelector(".editor-input");
      const inputDesc = document.querySelector(".editor-input1");
      editor.focus();
      if (command === "preview") {
          // Toggle chế độ xem trước
          const isReadOnly = editor.getAttribute("contenteditable") === "false";
          if (isReadOnly) {
              editor.setAttribute("contenteditable", "true");
              button.classList.remove("active");
          } else {
              editor.setAttribute("contenteditable", "false");
              button.classList.add("active");
          }
          return;
      }

      if (command === "fullscreen") {
          // Toggle chế độ toàn màn hình
          const isFullscreen = container.classList.toggle("fullscreen");

          // Hiển thị/Ẩn các phần tiêu đề và mô tả
          if (isFullscreen) {
              toolbar1.style.display = "none";
              inputTitle.style.display = "none";
              inputDesc.style.display = "none";
          } else {
              toolbar1.style.display = "";
              inputTitle.style.display = "";
              inputDesc.style.display = "";
          }

          button.classList.toggle("active");
          return;
      }

      if (command === "undo") {
          // Hoàn tác
          document.execCommand("undo", false, null);
          return;
      }

      if (command === "redo") {
          // Làm lại
          document.execCommand("redo", false, null);
          return;
      }

      if (command === "help") {
          // Hiển thị hộp thoại trợ giúp
          alert("Hướng dẫn sử dụng trình soạn thảo:\n- Sử dụng các nút để áp dụng định dạng.\n- Nhấn nút Preview để xem trước nội dung.\n- Nhấn nút Fullscreen để chuyển đổi chế độ toàn màn hình.");
          return;
      }

      if (command === "info") {
          // Hiển thị thông tin về trình soạn thảo
          alert("Trình soạn thảo văn bản phiên bản 1.0\nHỗ trợ các tính năng định dạng, danh sách, căn chỉnh, và xem trước.");
          return;
      }

      if (["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"].includes(command)) {
          // Loại bỏ trạng thái active từ tất cả các nút căn chỉnh
          const alignmentButtons = document.querySelectorAll(".fa-align-left, .fa-align-center, .fa-align-right, .fa-align-justify");
          alignmentButtons.forEach(btn => btn.classList.remove("active"));

          // Áp dụng căn chỉnh và kích hoạt trạng thái active cho nút được nhấn
          document.execCommand(command, false, null);
          button.classList.add("active");
          return;
      }

      button.classList.toggle("active");

      const selectedText = window.getSelection();
      const parentNode = selectedText.anchorNode.parentNode;

      if (command === "superscript" || command === "bold" || command === "italic" || command === "strikethrough") {
          document.execCommand(command, false, null);
      } else if (command === "heading") {
          const selectedText = window.getSelection();
          const parentNode = selectedText.anchorNode.parentNode;

          // Toggle heading
          if (parentNode && parentNode.tagName === 'H1') {
              document.execCommand('formatBlock', false, 'P'); // Remove heading
          } else {
              document.execCommand('formatBlock', false, 'H1'); // Apply heading
          }
      } else if (command === "insertUnorderedList" || command === "insertOrderedList") {
          const isUnorderedList = command === "insertUnorderedList";
          const otherButton = document.querySelector(isUnorderedList ? ".fa-list-ol" : ".fa-list-ul");

          if (otherButton && otherButton.classList.contains("active")) {
              otherButton.classList.remove("active");
              document.execCommand(isUnorderedList ? "insertOrderedList" : "insertUnorderedList", false, null);
          }

          document.execCommand(command, false, null);
      }

      // Adjust for heading inside a list
      if (command === "heading" && parentNode.tagName === "LI") {
          document.execCommand("outdent", false, null); // Remove list formatting
          document.execCommand('formatBlock', false, 'H1'); // Apply heading0+- fz21`
      } else if (parentNode.tagName === "H1" && (command === "insertUnorderedList" || command === "insertOrderedList")) {
          document.execCommand("formatBlock", false, "P"); // Convert back to paragraph if a list is applied
      }

      // Ensure list removal resets to paragraph
      if ((command === "insertUnorderedList" || command === "insertOrderedList") && parentNode.tagName === "H1") {
          document.execCommand("formatBlock", false, "P");
      }
      editor.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            const selection = window.getSelection();
            const parentNode = selection.anchorNode.parentNode;

            // Kiểm tra nếu dòng hiện tại là H1
            if (parentNode && parentNode.tagName === "H1") {
                // Tìm nút H1 trong thanh công cụ và tắt trạng thái active
                const h1Button = document.querySelector(".editor-toolbar button[data-command='heading']");
                if (h1Button) {
                    h1Button.classList.remove("active");
                }

                // Đặt dòng mới về định dạng đoạn văn (<p>)
                setTimeout(() => {
                    document.execCommand("formatBlock", false, "P");
                }, 0); // Sử dụng setTimeout để xử lý sau khi Enter
            }
        }
    });
  }
    function toggleAlignment(button, command) {
      const editor = document.getElementById("contentEditor");
      editor.focus();

      // Remove active state from all alignment buttons
      const alignmentButtons = document.querySelectorAll(
        ".fa-align-left, .fa-align-center, .fa-align-right, .fa-align-justify"
      );
      alignmentButtons.forEach((btn) => btn.classList.remove("active"));

      // Apply the alignment and set active state for the clicked button
      document.execCommand(command, false, null);
      button.classList.add("active");
    }
    function submitPost() {
      const titleInput = document.querySelector(".editor-input");
      const contentInput = document.getElementById("textEditor").innerHTML;

      const title = titleInput.value.trim(); // Lấy nội dung tiêu đề, loại bỏ khoảng trắng đầu/cuối
      document.getElementById('hiddenContent').value = contentInput;

      if (!title) {
        Swal.fire({
          icon: "error",
          title: "Lỗi",
          text: "Vui lòng nhập Tiêu đề cho bài viết!",
        });
        titleInput.focus(); // Đưa con trỏ vào ô tiêu đề
        return false;
      }

      if (!content) {
        // Hiển thị SweetAlert khi không có Nội dung bài viết
        Swal.fire({
          icon: "error",
          title: "Lỗi!",
          text: "Vui lòng nhập Nội dung bài viết.",
        });
        contentInput.focus(); // Đưa con trỏ vào ô nội dung
        return false;
      }

      // Nếu cả hai ô đã có nội dung, tiến hành xử lý gửi bài viết
      Swal.fire({
        icon: "success",
        title: "Bài viết đã sẵn sàng!",
        text: "Bài viết đã lưu thành công!",
      });
    }


  </script>
</body>

</html>