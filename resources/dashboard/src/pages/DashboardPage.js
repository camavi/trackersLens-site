import {
  AccountOverviewCard,
  ActivityCard,
  KpiGrid,
  QuickActionsCard,
  ResourceUsageCard,
  SystemStatusCard,
  TopBoxesCard
} from "../components/dashboardWidgets.js";

export function DashboardPage(CMSwift) {
  return _.Page({ class: "tl-page" },
    _.Container({ class: "tl-dashboard-container", width: "100%" },
      KpiGrid(CMSwift),
      _.Grid({ class: "tl-dashboard-grid", cols: 1, gap: "md", pc: { cols: 12 } },
        _.GridCol({ pc: { span: 4 } }, ActivityCard(CMSwift)),
        _.GridCol({ pc: { span: 4 } }, AccountOverviewCard(CMSwift)),
        _.GridCol({ pc: { span: 4 } }, ResourceUsageCard(CMSwift))
      ),
      _.Grid({ class: "tl-dashboard-grid", cols: 1, gap: "md", tablet: { cols: 2 }, pc: { cols: 3 } },
        _.GridCol({}, TopBoxesCard(CMSwift)),
        _.GridCol({}, QuickActionsCard(CMSwift)),
        _.GridCol({}, SystemStatusCard(CMSwift))
      )
    )
  );
}

export function PlaceholderPage(CMSwift, title, subtitle, iconName) {
  return _.Page({ class: "tl-page" },
    _.Container({ class: "tl-dashboard-container", width: "100%" },
      _.Card({
        class: "tl-panel tl-placeholder",
        title,
        subtitle,
        icon: _.Icon({ name: `#${iconName}`, size: 26 })
      },
        _.div({ class: "tl-placeholder-state" }, _.Icon({ name: `#${iconName}`, size: 26 }), _.strong("Coming soon"), _.span(subtitle))
      )
    )
  );
}

const MARKETPLACE_KINDS = [
  { id: "node", label: "Custom Nodes", icon: "box" },
  { id: "flowmap", label: "Flow Maps", icon: "workflow" },
  { id: "workspace", label: "Workspaces", icon: "layout-dashboard" }
];

export function MarketplacePage(CMSwift) {
  const route = new URLSearchParams(window.location.search);
  const requestedKind = route.get("kind");
  let kind = MARKETPLACE_KINDS.some((entry) => entry.id === requestedKind) ? requestedKind : "node";
  const pendingRelease = route.get("artifact") && route.get("version") ? { artifactId: route.get("artifact"), version: route.get("version"), kind } : null;
  let pendingReleaseInfo = null;
  let pendingReleaseError = "";
  let query = "";
  let loading = false;
  let message = "";
  let user = null;
  let items = [];
  let page = 1;
  let total = 0;
  let hasMore = false;
  let loadSequence = 0;
  const host = _.div({ class: "tl-marketplace-page" });
  const render = () => {
    const search = _.input({ class: "tl-market-search-input", value: query, placeholder: "Search nodes, Flow Maps and Workspaces…", "aria-label": "Search marketplace" });
    search.addEventListener("input", (event) => { query = event.target.value; });
    search.addEventListener("keydown", (event) => { if (event.key === "Enter") { event.preventDefault(); void load(1); } });
    const kinds = _.div({ class: "tl-market-kind-list", role: "tablist", "aria-label": "Marketplace type" }, ...MARKETPLACE_KINDS.map((entry) => _.Btn({ class: `tl-market-kind${entry.id === kind ? " is-active" : ""}`, role: "tab", "aria-selected": entry.id === kind, onclick: () => { kind = entry.id; void load(1); } }, _.Icon({ name: `#${entry.icon}`, size: 17 }), entry.label)));
    const grid = _.div({ class: "tl-market-grid", "aria-live": "polite" });
    if (loading) grid.append(_.div({ class: "tl-market-state" }, _.Spinner({ label: "Loading catalog" }), _.span("Loading releases…")));
    else if (!user) grid.append(_.div({ class: "tl-market-state" }, _.Icon({ name: "#user-round", size: 28 }), _.strong("Sign in to download"), _.span("The public catalog is available without an account. Sign in to access and download a release.")));
    else if (message) grid.append(_.div({ class: "tl-market-state is-error" }, _.Icon({ name: "#cloud-off", size: 28 }), _.strong("Catalog unavailable"), _.span(message), _.Btn({ class: "tl-market-button", onclick: () => void load() }, "Try again")));
    else if (!items.length) grid.append(_.div({ class: "tl-market-state" }, _.Icon({ name: "#package-open", size: 28 }), _.strong("No releases found"), _.span(query ? "Try a different search." : "There are no public releases in this category yet.")));
    else items.forEach((item) => grid.append(_.Card({ class: "tl-market-card" },
      _.div({ class: "tl-market-card-top" }, _.span({ class: "tl-market-card-icon" }, _.Icon({ name: `#${MARKETPLACE_KINDS.find((entry) => entry.id === kind)?.icon || "box"}`, size: 23 })), _.span({ class: "tl-market-free" }, "FREE")),
      _.small(`${kind.toUpperCase()} · v${item.version}`), _.h2(item.title), _.p(item.description || "No description provided."),
      _.div({ class: "tl-market-card-meta" }, _.span(item.publisher || "Trackers Lens creator"), _.span(item.license || "License not specified")),
      _.Btn({ class: "tl-market-button", onclick: () => void download(item) }, "Download release", _.Icon({ name: "#download", size: 16 }))
    )));
    host.replaceChildren(
      _.section({ class: "tl-market-hero" },
        _.div({ class: "tl-market-eyebrow" }, _.span(), "TRACKERS LENS · COMMUNITY CATALOG"),
        _.div({ class: "tl-market-hero-copy" }, _.div(_.h1("Build on what others have made."), _.p("Explore reusable Custom Nodes, Flow Maps and Workspaces. Every release is versioned and checked before download.")), _.span({ class: "tl-market-hero-mark" }, _.Icon({ name: "#boxes", size: 54 }))),
        _.div({ class: "tl-market-stats" }, _.div(_.strong(user ? user.name : "—"), _.span("Signed in as")), _.div(_.strong("3"), _.span("Artifact types")), _.div(_.strong("Free"), _.span("Catalog access")), _.span({ class: "tl-market-account" }, user ? _.Btn({ onclick: () => void logout() }, "Sign out") : null))
      ),
      _.section({ class: "tl-market-browser" },
        _.div({ class: "tl-market-heading" }, _.div(_.span("DISCOVER"), _.h2("Marketplace"), _.p("Free community releases. Paid listings and checkout are not available yet.")), _.div({ class: "tl-market-search" }, _.Icon({ name: "#search", size: 18 }), search)),
        kinds,
        !user ? authPanel(CMSwift) : null,
        pendingRelease ? selectedReleasePanel() : null,
        _.div({ class: "tl-market-results-heading" }, _.strong(`${total} releases`), _.span(`${({ node: "Custom Nodes", flowmap: "Flow Maps", workspace: "Workspaces" })[kind]} · page ${page}`)),
        total > 0 ? _.div({ class: "tl-market-pagination" }, _.Btn({ disabled: loading || page <= 1, onclick: () => void load(page - 1) }, "Previous"), _.span(`Page ${page}`), _.Btn({ disabled: loading || !hasMore, onclick: () => void load(page + 1) }, "Next")) : null,
        grid
      )
    );
  };
  const authPanel = () => {
    const email = _.input({ type: "email", autocomplete: "email", placeholder: "Email address", "aria-label": "Email address" });
    const password = _.input({ type: "password", autocomplete: "current-password", placeholder: "Password", "aria-label": "Password" });
    const status = _.span({ role: "status" });
    const form = _.form({ class: "tl-market-auth", onsubmit: async (event) => {
      event.preventDefault(); status.textContent = "Signing in…";
      try { await csrf(); const response = await fetch("/api/login", { method: "POST", credentials: "same-origin", headers: { Accept: "application/json", "Content-Type": "application/json", "X-XSRF-TOKEN": cookie("XSRF-TOKEN") }, body: JSON.stringify({ email: email.value, password: password.value, remember: true }) }); const data = await response.json().catch(() => ({})); if (!response.ok) throw new Error(data?.message || "Sign in failed."); user = data.user || await readUser(); message = ""; await resolvePendingRelease(); await load(); }
      catch (error) { status.textContent = error.message; }
    } }, _.strong("Sign in to explore releases"), _.div({ class: "tl-market-auth-fields" }, email, password, _.Btn({ type: "submit", class: "tl-market-button" }, "Sign in", _.Icon({ name: "#arrow-right", size: 16 }))), status);
    return form;
  };
  const cookie = (name) => decodeURIComponent(document.cookie.split(";").map((value) => value.trim()).find((value) => value.startsWith(`${name}=`))?.slice(name.length + 1) || "");
  const csrf = async () => { const response = await fetch("/sanctum/csrf-cookie", { credentials: "same-origin", headers: { Accept: "application/json" } }); if (!response.ok || !cookie("XSRF-TOKEN")) throw new Error("Could not start a secure account session."); };
  const readUser = async () => { const response = await fetch("/api/user", { credentials: "same-origin", headers: { Accept: "application/json" } }); if (!response.ok) return null; return response.json(); };
  const resolvePendingRelease = async () => {
    if (!pendingRelease || !user) return;
    pendingReleaseError = "";
    try {
      const response = await fetch(`/api/catalog/${encodeURIComponent(pendingRelease.artifactId)}/versions/${encodeURIComponent(pendingRelease.version)}`, { credentials: "same-origin", headers: { Accept: "application/json" } });
      const data = await response.json();
      if (!response.ok) throw new Error(data?.message || "Selected release is unavailable.");
      if (data.item?.kind !== pendingRelease.kind || data.item?.artifactId !== pendingRelease.artifactId || data.item?.version !== pendingRelease.version) throw new Error("The selected release identity does not match.");
      pendingReleaseInfo = data.item;
    } catch (error) { pendingReleaseError = error.message; pendingReleaseInfo = null; }
  };
  const selectedReleasePanel = () => _.Card({ class: "tl-market-selected-release" },
    _.strong("Selected public release"),
    pendingReleaseInfo ? _.div(_.span(`${pendingReleaseInfo.kind.toUpperCase()} · v${pendingReleaseInfo.version}`), _.h3(pendingReleaseInfo.title), _.p(pendingReleaseInfo.description || "No description provided."), _.Btn({ class: "tl-market-button", onclick: () => void downloadExact(pendingReleaseInfo) }, "Download selected release", _.Icon({ name: "#download", size: 16 })))
      : _.span(pendingReleaseError || (user ? "Loading selected release…" : "Sign in to continue with this release."))
  );
  const load = async (requestedPage = page) => {
    const sequence = ++loadSequence;
    user = user || await readUser(); loading = Boolean(user); message = ""; render();
    if (!user) { loading = false; render(); return; }
    if (pendingRelease && !pendingReleaseInfo && !pendingReleaseError) await resolvePendingRelease();
    loading = true; render();
    try { const params = new URLSearchParams({ kind, page: String(requestedPage), query }); const response = await fetch(`/api/public/catalog?${params}`, { credentials: "same-origin", headers: { Accept: "application/json" } }); const data = await response.json(); if (!response.ok) throw new Error(data?.message || "Could not load catalog."); if (sequence !== loadSequence) return; items = data.items || []; page = data.page || requestedPage; total = data.total || 0; hasMore = data.hasMore === true; }
    catch (error) { if (sequence === loadSequence) { message = error.message; items = []; total = 0; hasMore = false; } }
    finally { if (sequence === loadSequence) { loading = false; render(); } }
  };
  const download = async (item) => {
    try {
      await downloadExact(item);
    } catch (error) { message = error.message; render(); }
  };
  const downloadExact = async (item) => {
    try {
      const selectedKind = item.kind;
      const response = await fetch(`/api/catalog/${encodeURIComponent(item.artifactId)}/versions/${encodeURIComponent(item.version)}`, { credentials: "same-origin", headers: { Accept: "application/json" } });
      const data = await response.json(); if (!response.ok) throw new Error(data?.message || "Download failed.");
      if (data.item?.kind !== selectedKind || data.item?.artifactId !== item.artifactId || data.item?.version !== item.version || (item.sha256 && data.item?.sha256 !== item.sha256)) throw new Error("Release identity changed; refresh and retry.");
      const bundleDigest = [...new Uint8Array(await crypto.subtle.digest("SHA-256", new TextEncoder().encode(data.bundleJson)))].map((value) => value.toString(16).padStart(2, "0")).join("");
      if (bundleDigest !== data.item.sha256) throw new Error("Release bundle checksum does not match.");
      let filename, bytes;
      if (selectedKind === "node") { const release = JSON.parse(data.bundleJson); bytes = Uint8Array.from(atob(release.archiveBase64), (char) => char.charCodeAt(0)); filename = `${release.manifest.id}-${release.manifest.version}.tl-node.zip`; }
      else { bytes = new TextEncoder().encode(data.bundleJson); filename = `${item.title.replace(/[^a-z0-9_-]+/gi, "-")}-${item.version}.tl-catalog.json`; }
      const digest = [...new Uint8Array(await crypto.subtle.digest("SHA-256", bytes))].map((value) => value.toString(16).padStart(2, "0")).join("");
      const expected = selectedKind === "node" ? JSON.parse(data.bundleJson).archiveSha256 : data.item.sha256;
      if (digest !== expected) throw new Error("Downloaded release checksum does not match.");
      const url = URL.createObjectURL(new Blob([bytes], { type: "application/octet-stream" })); const anchor = document.createElement("a"); anchor.href = url; anchor.download = filename; anchor.click(); window.setTimeout(() => URL.revokeObjectURL(url), 1000);
      message = "Download started.";
    } catch (error) { message = error.message; render(); }
  };
  const logout = async () => { try { await csrf(); await fetch("/api/logout", { method: "POST", credentials: "same-origin", headers: { Accept: "application/json", "X-XSRF-TOKEN": cookie("XSRF-TOKEN") } }); } finally { user = null; items = []; await load(); } };
  void load();
  return _.Page({ class: "tl-page tl-marketplace-route" }, _.Container({ class: "tl-dashboard-container", width: "100%" }, host));
}
