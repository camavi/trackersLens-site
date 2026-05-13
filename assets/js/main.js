(function () {
  const storageKey = "trackersLensLanguage";
  const switcher = document.querySelector("[data-language-switcher]");
  const menu = document.querySelector("[data-menu]");
  const menuToggle = document.querySelector("[data-menu-toggle]");

  function closeMenu() {
    if (!menu || !menuToggle) return;
    menu.classList.remove("is-open");
    menuToggle.setAttribute("aria-expanded", "false");
  }

  function setupMenu() {
    if (!menu || !menuToggle) return;

    menuToggle.addEventListener("click", () => {
      const isOpen = menu.classList.toggle("is-open");
      menuToggle.setAttribute("aria-expanded", String(isOpen));
    });

    menu.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", closeMenu);
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") closeMenu();
    });
  }

  function setupLanguageSwitcher() {
    if (!switcher) return;

    switcher.addEventListener("change", (event) => {
      const url = event.target.value;
      const language = url.replace(/\//g, "");

      if (language) {
        localStorage.setItem(storageKey, language);
      }

      window.location.href = url;
    });
  }

  function setupSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach((link) => {
      link.addEventListener("click", (event) => {
        const id = link.getAttribute("href");
        if (!id || id === "#") return;
        const target = document.querySelector(id);
        if (!target) return;
        event.preventDefault();
        target.scrollIntoView({ behavior: "smooth", block: "start" });
        history.pushState(null, "", id);
      });
    });
  }

  function setupReveal() {
    const items = document.querySelectorAll(".reveal");
    if (!items.length) return;

    if (!("IntersectionObserver" in window)) {
      items.forEach((item) => item.classList.add("is-visible"));
      return;
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.16 });

    items.forEach((item) => observer.observe(item));
  }

  function setupNewsletter() {
    const form = document.querySelector(".newsletter");
    if (!form) return;

    form.addEventListener("submit", (event) => {
      event.preventDefault();
      form.reset();
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    setupMenu();
    setupLanguageSwitcher();
    setupSmoothScroll();
    setupReveal();
    setupNewsletter();
  });
})();
