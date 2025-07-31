
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




  document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const imgFile = params.get('img');
    if (imgFile) {
      const imgEl = document.getElementById('selected-img');
      imgEl.src = 'images/' + imgFile;
      imgEl.alt = imgFile.split('.')[0];
    }
  });



document.addEventListener('DOMContentLoaded', () => {
  const form  = document.getElementById('order-form');
  const msgEn = document.getElementById('confirmation-message-en');
  const msgTr = document.getElementById('confirmation-message-tr');

  form.addEventListener('submit', e => {
   // e.preventDefault();      
    form.style.display = 'none';
    msgEn.style.display  = 'block';
    msgTr.style.display  = 'block';
  });
});





document.addEventListener('DOMContentLoaded', () => {
      // Form gönderimi ve onay mesajı
      const form = document.getElementById('contact-form');
      const confEn = document.getElementById('confirmation-en');
      const confTr = document.getElementById('confirmation-tr');

      form.addEventListener('submit', e => {
        //e.preventDefault();
        // Doğrulama başarılıysa:
        form.style.display = 'none';
        confEn.style.display = 'block';
        confTr.style.display = 'block';
      });
    });