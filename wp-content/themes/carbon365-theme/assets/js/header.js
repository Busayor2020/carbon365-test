(function () {
  const toggle = document.querySelector(".nav-toggle");
  const nav = document.getElementById("site-navigation");
  const header = document.querySelector(".site-header"); // Get the main header
  const headerInner = document.querySelector(".header-inner");
  let mobilePanel = null;
  const MOBILE_BREAK = 768;

  function createMobilePanel() {
    if (mobilePanel) return;

    mobilePanel = document.createElement("div");
    mobilePanel.className = "mobile-nav";
    // Accessibility role
    mobilePanel.setAttribute("role", "dialog");
    mobilePanel.setAttribute("aria-modal", "true");

    // 1. Clone Logo (Top inside panel)
    const originalLogo = document.querySelector(".site-logo");
    if (originalLogo) {
      const logoClone = originalLogo.cloneNode(true);
      logoClone.removeAttribute("id");
      mobilePanel.appendChild(logoClone);
    }

    // 2. Clone Menu (Middle)
    const menu =
      nav && nav.querySelector(".menu")
        ? nav.querySelector(".menu").cloneNode(true)
        : null;
    if (menu) {
      mobilePanel.appendChild(menu);
    }

    // 3. Clone CTA (Bottom)
    const originalCta = document.querySelector(".btn-cta");
    if (originalCta) {
      const ctaClone = originalCta.cloneNode(true);
      mobilePanel.appendChild(ctaClone);
    }

    // Insert panel outside header-inner but inside header, or just append to body for easier fixed positioning management
    document.body.appendChild(mobilePanel);

    mobilePanel.addEventListener("click", (e) => {
      if (e.target.closest("a")) closeMenu();
    });
  }

  function openMenu() {
    createMobilePanel();

    // 1. Add open class to panel
    mobilePanel.classList.add("open");

    // 2. Update ARIA state
    toggle.setAttribute("aria-expanded", "true");

    // 3. Swap Font Awesome Icon (bars -> times)
    const icon = toggle.querySelector("i");
    if (icon) {
      icon.classList.remove("fa-bars");
      icon.classList.add("fa-times");
    }

    // 4. Add active state to header (to hide main logo via CSS)
    if (header) header.classList.add("menu-is-active");

    // Lock body scroll
    document.body.style.overflow = "hidden";

    document.addEventListener("keydown", escHandler);
    document.addEventListener("click", outsideClick);

    const firstLink = mobilePanel.querySelector("a");
    if (firstLink) firstLink.focus();
  }

  function closeMenu() {
    if (!mobilePanel) return;

    // 1. Remove open class
    mobilePanel.classList.remove("open");

    // 2. Update ARIA
    toggle.setAttribute("aria-expanded", "false");

    // 3. Swap Font Awesome Icon (times -> bars)
    const icon = toggle.querySelector("i");
    if (icon) {
      icon.classList.remove("fa-times");
      icon.classList.add("fa-bars");
    }

    // 4. Remove active state from header
    if (header) header.classList.remove("menu-is-active");

    // Unlock body scroll
    document.body.style.overflow = "";

    document.removeEventListener("keydown", escHandler);
    document.removeEventListener("click", outsideClick);
    toggle.focus();
  }

  function toggleMenu() {
    const expanded = toggle.getAttribute("aria-expanded") === "true";
    if (expanded) closeMenu();
    else openMenu();
  }

  function escHandler(e) {
    if (e.key === "Escape") closeMenu();
  }

  function outsideClick(e) {
    if (!mobilePanel || !mobilePanel.classList.contains("open")) return;
    // If click is NOT inside the panel AND NOT on the toggle button
    if (!mobilePanel.contains(e.target) && !toggle.contains(e.target)) {
      closeMenu();
    }
  }

  function resizeHandler() {
    if (window.innerWidth >= MOBILE_BREAK) {
      if (mobilePanel && mobilePanel.classList.contains("open")) {
        closeMenu();
      }
    }
  }

  function scrollHandler() {
    if (!header) return;
    if (window.scrollY > 8) header.classList.add("scrolled");
    else header.classList.remove("scrolled");
  }

  if (toggle) toggle.addEventListener("click", toggleMenu);
  window.addEventListener("resize", resizeHandler);
  window.addEventListener("scroll", scrollHandler, { passive: true });

  if (toggle) toggle.setAttribute("aria-expanded", "false");
})();
