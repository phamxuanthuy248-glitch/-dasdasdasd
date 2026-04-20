<?php
// Footer layout file
?>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-dark text-white mt-5 pt-5 pb-3">
        <div class="container-fluid">
            <div class="row mb-4">
                <!-- Column 1: About -->
                <div class="col-md-4 col-sm-6 mb-4">
                    <h5 class="mb-3 fw-bold">
                        <i class="fas fa-globe text-info"></i> Tour Du Lịch
                    </h5>
                    <p class="text-muted">
                        Khám phá những điểm đến tuyệt vời trên toàn thế giới cùng chúng tôi. 
                        Dịch vụ chuyên nghiệp, giá cạnh tranh, hỗ trợ 24/7.
                    </p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-muted text-decoration-none me-3" title="Facebook">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
                        <a href="#" class="text-muted text-decoration-none me-3" title="Twitter">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-muted text-decoration-none me-3" title="Instagram">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                        <a href="#" class="text-muted text-decoration-none" title="YouTube">
                            <i class="fab fa-youtube fa-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-md-4 col-sm-6 mb-4">
                    <h5 class="mb-3 fw-bold">
                        <i class="fas fa-link"></i> Liên Kết Nhanh
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>?action=home" class="text-muted text-decoration-none">
                                <i class="fas fa-chevron-right"></i> Trang Chủ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo BASE_URL; ?>?action=tours" class="text-muted text-decoration-none">
                                <i class="fas fa-chevron-right"></i> Tours Du Lịch
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-muted text-decoration-none">
                                <i class="fas fa-chevron-right"></i> Về Chúng Tôi
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-muted text-decoration-none">
                                <i class="fas fa-chevron-right"></i> Liên Hệ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-muted text-decoration-none">
                                <i class="fas fa-chevron-right"></i> FAQ
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="col-md-4 col-sm-6 mb-4">
                    <h5 class="mb-3 fw-bold">
                        <i class="fas fa-phone"></i> Thông Tin Liên Hệ
                    </h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-3">
                            <i class="fas fa-map-marker-alt text-info"></i> 
                            123 Đường ABC, Quận 1, TP. Hồ Chí Minh
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-phone text-info"></i>
                            <a href="tel:+84123456789" class="text-muted text-decoration-none">
                                (+84) 123-456-789
                            </a>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope text-info"></i>
                            <a href="mailto:info@tourdlich.com" class="text-muted text-decoration-none">
                                info@tourdlich.com
                            </a>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-clock text-info"></i>
                            <span>8:00 - 22:00 (Hàng ngày)</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <hr class="bg-secondary">

            <!-- Bottom Row: Copyright & Links -->
            <div class="row py-3">
                <div class="col-md-6 mb-2">
                    <p class="text-muted mb-0">
                        &copy; 2026 Tour Du Lịch. Tất cả quyền được bảo lưu.
                    </p>
                </div>
                <div class="col-md-6 text-md-end text-start">
                    <p class="text-muted mb-0">
                        <a href="#" class="text-muted text-decoration-none">
                            Chính Sách Bảo Mật
                        </a> 
                        <span class="mx-2">|</span>
                        <a href="#" class="text-muted text-decoration-none">
                            Điều Khoản Dịch Vụ
                        </a>
                        <span class="mx-2">|</span>
                        <a href="#" class="text-muted text-decoration-none">
                            Giải Quyết Tranh Chấp
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>

    <style>
        footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            margin-top: 60px;
            border-top: 3px solid #0066cc;
        }

        footer a {
            transition: all 0.3s ease;
        }

        footer a:hover {
            color: #00d4ff !important;
            padding-left: 5px;
        }

        footer .social-links a {
            padding-left: 0;
        }

        footer h5 {
            color: #00d4ff;
        }

        footer .text-muted {
            color: #adb5bd !important;
        }

        @media (max-width: 768px) {
            footer .col-md-4 {
                text-align: center;
                margin-bottom: 20px;
            }
            
            footer .social-links {
                justify-content: center;
                display: flex;
                gap: 15px;
            }
        }
    </style>

</body>
</html>
