import "@cmswift/ui/css/ui.css";
import "./styles/app.css";
import cmswiftCoreUrl from "@cmswift/core?url";
import cmswiftUiUrl from "@cmswift/ui?url";
import { createI18n } from "./i18n.js";
import { AppLayout } from "./layouts/AppLayout.js";
import { DashboardPage, PlaceholderPage } from "./pages/DashboardPage.js";
import { navigationItems } from "./services/dashboardData.js";
import { installCmswiftIconPathPatch } from "./cmswiftIconPathPatch.js";

createI18n();

function loadScript(src) {
  return new Promise((resolve, reject) => {
    const script = document.createElement("script");
    script.src = src;
    script.onload = resolve;
    script.onerror = () => reject(new Error(`Impossibile caricare ${src}`));
    document.head.append(script);
  });
}

(async function bootstrapTrackerLens() {
  try {
    await loadScript(cmswiftCoreUrl);
    await loadScript(cmswiftUiUrl);
    installCmswiftIconPathPatch(window.CMSwift);
  } catch (error) {
    renderBootError(error);
    return;
  }

  function waitForCMSwift(callback) {
    if (window.__trackerLensBootErrors?.length) {
      renderBootError(window.__trackerLensBootErrors[0]);
      return;
    }

    if (window.CMSwift?.ready && window.CMSwift?.router && window.CMSwift?.ui) {
      callback(window.CMSwift);
      return;
    }

    window.setTimeout(() => waitForCMSwift(callback), 16);
  }

  waitForCMSwift((CMSwift) => {
    CMSwift.ready(() => {
      try {
        configureRouter(CMSwift);
        CMSwift.router.start();
      } catch (error) {
        renderBootError(error);
      }
    });
  });
})();

window.addEventListener("error", (event) => {
  window.__trackerLensBootErrors?.push(event.error || event.message);
  renderBootError(event.error || event.message);
});

window.addEventListener("unhandledrejection", (event) => {
  window.__trackerLensBootErrors?.push(event.reason || "Unhandled promise rejection");
  renderBootError(event.reason || "Unhandled promise rejection");
});

function renderBootError(error) {
  const target = document.querySelector("#app");
  if (!target) return;

  target.innerHTML = "";
  const box = document.createElement("pre");
  box.className = "tl-boot-error";
  box.textContent = error?.stack || String(error);
  target.append(box);
}

function configureRouter(CMSwift) {
  _.setTheme?.("dark");
  _.router.setOutlet("#app");
  _.router.add("/app", (ctx) => AppLayout(CMSwift, ctx, DashboardPage(CMSwift)));

  navigationItems
    .filter((item) => item.path !== "/app")
    .forEach((item) => {
      _.router.add(item.path, (ctx) => AppLayout(
        CMSwift,
        ctx,
        PlaceholderPage(CMSwift, item.label, "Workspace module ready for cloud data integration.", item.icon)
      ));
    });

  _.router.notFound((ctx) => AppLayout(
    CMSwift,
    ctx,
    PlaceholderPage(CMSwift, "Page not found", "The requested route does not exist in Trackers Lens.", "alert-circle")
  ));
}
