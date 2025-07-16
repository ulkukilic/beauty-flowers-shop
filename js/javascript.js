
    document.addEventListener("DOMContentLoaded", () => {
        const trBtn = document.getElementById("tr-btn");
        const enBtn = document.getElementById("en-btn");

        const showLang = (lang) => {
            const allTR = document.querySelectorAll(".lang-tr");
            const allEN = document.querySelectorAll(".lang-en");

            allTR.forEach(el => el.style.display = lang === "tr" ? "inline-block" : "none");
            allEN.forEach(el => el.style.display = lang === "en" ? "inline-block" : "none");

            localStorage.setItem("lang", lang);
        };

        const savedLang = localStorage.getItem("lang") || "en";
        showLang(savedLang);

        trBtn.addEventListener("click", () => showLang("tr"));
        enBtn.addEventListener("click", () => showLang("en"));
    });
