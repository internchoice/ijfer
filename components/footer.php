<footer class="custom-footer">
    <div class="container footer-content d-md-flex justify-content-between align-items-center">

        <!-- Copyright -->
        <div>
            Copyright © <span id="year"></span> IJFER all rights reserved
        </div>

        <!-- Inline Links (NOT list) -->
        <div class="footer-links">
            <a href="#">Term And Conditions</a>
            <span class="separator">|</span>
            <a href="#">Privacy Policy</a>
            <span class="separator">|</span>
            <a href="#">Publication Ethics</a>
            <span class="separator">|</span>
            <a href="#">Peer Review Process</a>
            <span class="separator">|</span>
            <a href="#">Contact Us</a>
        </div>

    </div>
</footer>

<script>
    document.getElementById("year").textContent = new Date().getFullYear();
</script>