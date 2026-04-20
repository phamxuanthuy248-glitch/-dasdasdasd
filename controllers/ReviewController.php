<?php

class ReviewController {

    public function addReview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                $_SESSION['error'] = "Vui lòng đăng nhập để đánh giá!";
                header("Location: index.php?action=login");
                exit;
            }

            $tourId = $_POST['tour_id'] ?? null;
            $rating = intval($_POST['rating'] ?? 5);
            $comment = trim($_POST['comment'] ?? '');
            $bookingId = $_POST['booking_id'] ?? null;
            $image = null;

            // Validation
            if (!$tourId) {
                $_SESSION['error'] = "Tour không hợp lệ!";
                header("back");
                exit;
            }

            if ($rating < 1 || $rating > 5) {
                $rating = 5;
            }

            if (empty($comment)) {
                $_SESSION['error'] = "Vui lòng nhập nhận xét!";
                header("back");
                exit;
            }

            // Upload hình ảnh nếu có
            if (!empty($_FILES['image']['name'])) {
                $image = $this->uploadImage($_FILES['image']);
            }

            $review = new Review();
            $reviewId = $review->createReview($tourId, $_SESSION['user']['id'], $bookingId, $rating, $comment, $image);

            if ($reviewId) {
                $_SESSION['success'] = "Đánh giá thành công! Chờ admin phê duyệt.";
            } else {
                $_SESSION['error'] = "Lỗi khi thêm đánh giá!";
            }

            header("back");
            exit;
        }
    }

    public function editReview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            if (!isset($_SESSION['user'])) {
                header("Location: index.php?action=login");
                exit;
            }

            $reviewId = $_POST['review_id'] ?? null;
            $rating = intval($_POST['rating'] ?? 5);
            $comment = trim($_POST['comment'] ?? '');
            $image = null;

            $review = new Review();
            $reviewData = $review->getReviewById($reviewId);

            if (!$reviewData || $reviewData[0]['user_id'] != $_SESSION['user']['id']) {
                $_SESSION['error'] = "Không có quyền sửa đánh giá này!";
                header("back");
                exit;
            }

            // Upload hình ảnh nếu có
            if (!empty($_FILES['image']['name'])) {
                $image = $this->uploadImage($_FILES['image']);
            }

            if ($review->updateReview($reviewId, $rating, $comment, $image)) {
                $_SESSION['success'] = "Cập nhật đánh giá thành công!";
            } else {
                $_SESSION['error'] = "Lỗi khi cập nhật đánh giá!";
            }

            header("back");
            exit;
        }
    }

    public function deleteReview() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $reviewId = $_GET['id'] ?? null;
        $review = new Review();
        $reviewData = $review->getReviewById($reviewId);

        if (!$reviewData || $reviewData[0]['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = "Không có quyền xóa đánh giá này!";
            header("back");
            exit;
        }

        if ($review->deleteReview($reviewId)) {
            $_SESSION['success'] = "Xóa đánh giá thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi xóa đánh giá!";
        }

        header("back");
        exit;
    }

    public function getReviewsByTour($tourId) {
        $review = new Review();
        return $review->getReviewsByTour($tourId);
    }

    public function markHelpful() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reviewId = $_POST['review_id'] ?? null;
            $review = new Review();
            
            if ($review->addHelpful($reviewId)) {
                echo json_encode(['success' => true, 'message' => 'Cảm ơn bạn!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi!']);
            }
            exit;
        }
    }

    private function uploadImage($file) {
        $uploadDir = 'assets/uploads/reviews/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = time() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = "Chỉ hỗ trợ file ảnh (JPG, PNG, GIF)!";
            return null;
        }

        // Check file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $_SESSION['error'] = "File quá lớn (tối đa 5MB)!";
            return null;
        }

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $fileName;
        }
        return null;
    }
}
