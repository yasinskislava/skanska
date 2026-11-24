document.addEventListener("DOMContentLoaded", () => {
    const navLinks = document.querySelectorAll(".navbar-nav a");
    const currentFile = window.location.pathname.split("/").pop() || "index.php";

    navLinks.forEach(link => {
        const hrefFile = link.getAttribute("href")?.split("?")[0];
        if (hrefFile === currentFile) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }

        link.addEventListener("click", () => {
            navLinks.forEach(el => el.classList.remove("active"));
            link.classList.add("active");
        });
    });
});