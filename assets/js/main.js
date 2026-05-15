(function () {
  const storageKey = "trackersLensLanguage";
  const launchStorageKey = "trackersLensLaunchEmails";
  const config = window.TrackersLensConfig || {};
  const apiBaseUrl = (config.apiBaseUrl || "https://api.trackerslens.com").replace(/\/$/, "");
  const appBaseUrl = (config.appBaseUrl || "https://app.trackerslens.com").replace(/\/$/, "");
  const labels = Object.assign({
    loginSubmit: "Accedi",
    registerSubmit: "Crea account",
    connecting: "Connessione all'API...",
    failed: "Accesso non riuscito. Controlla i dati e riprova.",
    success: "Accesso riuscito. Apertura dashboard...",
    saving: "Salvataggio...",
    newsletterSaved: "Email salvata. Ti avviseremo al lancio.",
    launchInvalid: "Inserisci una email valida.",
    contactSuccess: "Messaggio inviato. Ti risponderemo presto.",
    contactInvalid: "Compila tutti i campi richiesti."
  }, config.labels || {});
  const switcher = document.querySelector("[data-language-switcher]");
  const menu = document.querySelector("[data-menu]");
  const menuToggle = document.querySelector("[data-menu-toggle]");
  const modal = document.querySelector("[data-login-modal]");
  const authForm = document.querySelector("[data-auth-form]");
  const authMessage = document.querySelector("[data-auth-message]");
  const authSubmit = document.querySelector("[data-auth-submit]");
  const newsletterForm = document.querySelector("[data-newsletter-form]");
  const newsletterMessage = document.querySelector("[data-newsletter-message]");
  const launchModal = document.querySelector("[data-launch-modal]");
  const launchForm = document.querySelector("[data-launch-form]");
  const launchMessage = document.querySelector("[data-launch-message]");
  const contactModal = document.querySelector("[data-contact-modal]");
  const contactForm = document.querySelector("[data-contact-form]");
  const contactMessage = document.querySelector("[data-contact-message]");
  let authMode = "login";
  let lastFocus = null;

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

    document.querySelectorAll("[data-focus-newsletter]").forEach((trigger) => {
      trigger.addEventListener("click", openLaunchModal);
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

  async function ensureCsrfCookie() {
    await fetch(`${apiBaseUrl}/sanctum/csrf-cookie`, {
      method: "GET",
      credentials: "include",
      headers: { Accept: "application/json" }
    });
  }

  async function postApi(endpoint, payload) {
    await ensureCsrfCookie();
    const csrfToken = decodeURIComponent(getCookie("XSRF-TOKEN") || "");
    const response = await fetch(`${apiBaseUrl}${endpoint}`, {
      method: "POST",
      credentials: "include",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-XSRF-TOKEN": csrfToken
      },
      body: JSON.stringify(payload)
    });

    if (!response.ok) {
      let message = labels.failed;
      try {
        const data = await response.json();
        message = data.message || Object.values(data.errors || {})[0]?.[0] || message;
      } catch (_error) {
        // Keep the fallback message when the API returns an empty body.
      }
      throw new Error(message);
    }

    return response.status === 204 ? null : response.json();
  }

  function persistLaunchEmailLocally(email) {
    const saved = JSON.parse(localStorage.getItem(launchStorageKey) || "[]");
    if (!saved.includes(email)) {
      saved.push(email);
      localStorage.setItem(launchStorageKey, JSON.stringify(saved));
    }
  }

  async function submitLaunchEmail(email, source) {
    await postApi("/api/launch-subscriptions", {
      email,
      source,
      locale: config.currentLanguage || document.documentElement.lang || null
    });
    persistLaunchEmailLocally(email);
  }

  function setupNewsletter() {
    if (!newsletterForm) return;

    newsletterForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      const input = newsletterForm.querySelector('input[type="email"]');
      const submit = newsletterForm.querySelector('button[type="submit"]');
      const email = input && input.value.trim();
      if (!email || !input.checkValidity()) return;

      if (submit) submit.disabled = true;
      if (newsletterMessage) {
        newsletterMessage.textContent = labels.saving;
        newsletterMessage.classList.remove("is-error");
        newsletterMessage.classList.remove("is-success");
      }

      try {
        await submitLaunchEmail(email, "footer_newsletter");
        if (newsletterMessage) {
          newsletterMessage.textContent = labels.newsletterSaved;
          newsletterMessage.classList.add("is-success");
        }
        newsletterForm.reset();
      } catch (error) {
        if (newsletterMessage) {
          newsletterMessage.textContent = error.message || labels.failed;
          newsletterMessage.classList.add("is-error");
        }
      } finally {
        if (submit) submit.disabled = false;
      }
    });
  }

  function saveLaunchEmail(email) {
    return submitLaunchEmail(email, "launch_modal");
  }

  function setLaunchMessage(message, type) {
    if (!launchMessage) return;
    launchMessage.textContent = message || "";
    launchMessage.classList.toggle("is-success", type === "success");
    launchMessage.classList.toggle("is-error", type === "error");
  }

  function openLaunchModal(event) {
    if (event) event.preventDefault();
    if (!launchModal) return;
    lastFocus = document.activeElement;
    launchModal.hidden = false;
    launchModal.classList.add("is-open");
    launchModal.setAttribute("aria-hidden", "false");
    document.body.classList.add("has-modal");
    setLaunchMessage("", "");
    window.setTimeout(() => {
      const email = launchModal.querySelector('input[name="email"]');
      if (email) email.focus();
    }, 50);
  }

  function closeLaunchModal() {
    if (!launchModal) return;
    launchModal.classList.remove("is-open");
    launchModal.setAttribute("aria-hidden", "true");
    launchModal.hidden = true;
    document.body.classList.remove("has-modal");
    if (lastFocus && typeof lastFocus.focus === "function") lastFocus.focus();
  }

  function setupLaunchModal() {
    if (!launchModal || !launchForm) return;

    document.querySelectorAll("[data-launch-open]").forEach((button) => {
      button.addEventListener("click", openLaunchModal);
    });

    launchModal.querySelectorAll("[data-launch-close]").forEach((button) => {
      button.addEventListener("click", closeLaunchModal);
    });

    launchForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      const input = launchForm.querySelector('input[name="email"]');
      const submit = launchForm.querySelector('button[type="submit"]');
      const email = input && input.value.trim();

      if (!email || !input.checkValidity()) {
        setLaunchMessage(labels.launchInvalid, "error");
        if (input) input.focus();
        return;
      }

      if (submit) submit.disabled = true;
      setLaunchMessage(labels.saving, "");

      try {
        await saveLaunchEmail(email);
        setLaunchMessage(labels.newsletterSaved, "success");
        launchForm.reset();
      } catch (error) {
        setLaunchMessage(error.message || labels.failed, "error");
      } finally {
        if (submit) submit.disabled = false;
      }
    });
  }

  function setContactMessage(message, type) {
    if (!contactMessage) return;
    contactMessage.textContent = message || "";
    contactMessage.classList.toggle("is-success", type === "success");
    contactMessage.classList.toggle("is-error", type === "error");
  }

  function openContactModal(event) {
    if (event) event.preventDefault();
    if (!contactModal) return;
    lastFocus = document.activeElement;
    contactModal.hidden = false;
    contactModal.classList.add("is-open");
    contactModal.setAttribute("aria-hidden", "false");
    document.body.classList.add("has-modal");
    setContactMessage("", "");
    window.setTimeout(() => {
      const name = contactModal.querySelector('input[name="name"]');
      if (name) name.focus();
    }, 50);
  }

  function closeContactModal() {
    if (!contactModal) return;
    contactModal.classList.remove("is-open");
    contactModal.setAttribute("aria-hidden", "true");
    contactModal.hidden = true;
    document.body.classList.remove("has-modal");
    if (lastFocus && typeof lastFocus.focus === "function") lastFocus.focus();
  }

  function setupContactModal() {
    if (!contactModal || !contactForm) return;

    document.querySelectorAll("[data-contact-open]").forEach((button) => {
      button.addEventListener("click", openContactModal);
    });

    contactModal.querySelectorAll("[data-contact-close]").forEach((button) => {
      button.addEventListener("click", closeContactModal);
    });

    contactForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!contactForm.checkValidity()) {
        setContactMessage(labels.contactInvalid, "error");
        return;
      }

      const formData = new FormData(contactForm);
      const submit = contactForm.querySelector('button[type="submit"]');
      if (submit) submit.disabled = true;
      setContactMessage(labels.saving, "");

      try {
        await postApi("/api/contact-messages", {
          name: formData.get("name") || "",
          email: formData.get("email") || "",
          message: formData.get("message") || "",
          source: "contact_modal",
          locale: config.currentLanguage || document.documentElement.lang || null
        });
        setContactMessage(labels.contactSuccess, "success");
        contactForm.reset();
      } catch (error) {
        setContactMessage(error.message || labels.failed, "error");
      } finally {
        if (submit) submit.disabled = false;
      }
    });
  }

  function getCookie(name) {
    return document.cookie
      .split("; ")
      .find((row) => row.startsWith(`${name}=`))
      ?.split("=")[1];
  }

  function setAuthMessage(message, type) {
    if (!authMessage) return;
    authMessage.textContent = message || "";
    authMessage.classList.toggle("is-success", type === "success");
    authMessage.classList.toggle("is-error", type === "error");
  }

  function setAuthMode(mode) {
    authMode = mode;
    const isRegister = mode === "register";
    if (!modal || !authForm) return;

    modal.querySelectorAll("[data-auth-mode]").forEach((button) => {
      button.classList.toggle("is-active", button.dataset.authMode === mode);
    });

    modal.querySelectorAll("[data-register-only]").forEach((item) => {
      item.hidden = !isRegister;
      item.querySelectorAll("input").forEach((input) => {
        input.required = isRegister;
        input.autocomplete = isRegister && input.name === "password" ? "new-password" : input.autocomplete;
      });
    });

    modal.querySelectorAll("[data-login-only]").forEach((item) => {
      item.hidden = isRegister;
    });

    const submitLabel = authSubmit && authSubmit.querySelector("[data-auth-submit-label]");
    if (submitLabel) {
      submitLabel.textContent = isRegister ? labels.registerSubmit : labels.loginSubmit;
    }

    setAuthMessage("", "");
  }

  function openAuthModal() {
    if (!modal) return;
    lastFocus = document.activeElement;
    modal.hidden = false;
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("has-modal");
    setAuthMode("login");
    window.setTimeout(() => {
      const email = modal.querySelector('input[name="email"]');
      if (email) email.focus();
    }, 50);
  }

  function closeAuthModal() {
    if (!modal) return;
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    modal.hidden = true;
    document.body.classList.remove("has-modal");
    setAuthMessage("", "");
    if (lastFocus && typeof lastFocus.focus === "function") lastFocus.focus();
  }

  async function submitAuth(event) {
    event.preventDefault();
    if (!authForm || !authSubmit) return;

    const formData = new FormData(authForm);
    const endpoint = authMode === "register" ? "/api/register" : "/api/login";
    const payload = Object.fromEntries(formData.entries());
    payload.remember = formData.get("remember") === "1";

    if (authMode === "login") {
      delete payload.name;
      delete payload.password_confirmation;
    }

    authSubmit.disabled = true;
    setAuthMessage(labels.connecting, "");

    try {
      await ensureCsrfCookie();

      const csrfToken = decodeURIComponent(getCookie("XSRF-TOKEN") || "");
      const response = await fetch(`${apiBaseUrl}${endpoint}`, {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-XSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(payload)
      });

      if (!response.ok) {
        let message = labels.failed;
        try {
          const data = await response.json();
          message = data.message || Object.values(data.errors || {})[0]?.[0] || message;
        } catch (_error) {
          // Keep the fallback message when the API returns an empty body.
        }
        throw new Error(message);
      }

      setAuthMessage(labels.success, "success");
      window.setTimeout(() => {
        window.location.href = appBaseUrl;
      }, 600);
    } catch (error) {
      setAuthMessage(error.message, "error");
    } finally {
      authSubmit.disabled = false;
    }
  }

  function setupAuthModal() {
    if (!modal || !authForm) return;

    document.querySelectorAll("[data-login-open]").forEach((button) => {
      button.addEventListener("click", openAuthModal);
    });

    modal.querySelectorAll("[data-login-close]").forEach((button) => {
      button.addEventListener("click", closeAuthModal);
    });

    modal.querySelectorAll("[data-auth-mode]").forEach((button) => {
      button.addEventListener("click", () => setAuthMode(button.dataset.authMode || "login"));
    });

    authForm.addEventListener("submit", submitAuth);

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && modal.classList.contains("is-open")) closeAuthModal();
      if (event.key === "Escape" && launchModal && launchModal.classList.contains("is-open")) closeLaunchModal();
      if (event.key === "Escape" && contactModal && contactModal.classList.contains("is-open")) closeContactModal();
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    setupMenu();
    setupLanguageSwitcher();
    setupSmoothScroll();
    setupReveal();
    setupNewsletter();
    setupLaunchModal();
    setupContactModal();
    setupAuthModal();
  });
})();
