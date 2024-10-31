(function () {
    document.addEventListener('DOMContentLoaded', function () {
        var acc = document.getElementsByClassName("wppfe-accordion");
        for (var i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("wppfe-active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }
    });
})();
