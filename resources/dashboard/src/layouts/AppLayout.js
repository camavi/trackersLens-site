import { icon, iconButton } from "../components/icons.js";
import { navigationItems, searchSuggestions } from "../services/dashboardData.js";
import logoUrl from "../icons/logo.svg";

export function AppLayout(CMSwift, ctx, page) {
  return _.Layout({
    class: "tl-shell",
    drawerWidth: 280,
    header: Topbar(CMSwift),
    drawer: Sidebar(CMSwift, ctx),
    stickyAside: true,
    mode: 'global',
    page
  });
}

function Topbar(CMSwift) {
  return _.Header({
    class: "tl-topbar",
    sticky: true,
    left: _.a({ class: "tl-brand", href: "/app" },
      _.img({ src: logoUrl, alt: "Trackers Lens", width: 34, height: 34 }),
      _.strong("TRACKERS "),
      _.span("LENS")
    ),
    title: null,
    body: _.div({ class: "tl-global-search" },
      _.Search({
        label: "Search assets, boxes, documentation...",
        items: searchSuggestions,
        minLength: 0,
        clearable: true,
        showShortcode: true,
        shortcode: "cmd+k",
        inputClass: "tl-search-input"
      })
    ),
    right: _.Row({ align: "center", gap: "sm", wrap: false },
      iconButton(CMSwift, "bell", "Notifications", { class: "has-notification" }),
      iconButton(CMSwift, "help-circle", "Help"),
      _.Tooltip({ label: "Thomas Lane online" },
        _.div({ class: "tl-avatar-wrap" },
          _.Avatar({ class: "tl-avatar", label: "TL", size: "md" }),
          _.span({ class: "tl-online-dot" })
        )
      )
    )
  });
}

function Sidebar(CMSwift, ctx) {
  return _.Drawer({
    class: "tl-sidebar",
    header: _.div({ class: "tl-sidebar-head" },
      _.span({ class: "tl-sidebar-logo" }, icon(CMSwift, "apps", { size: 24 })),
      _.div({},
        _.strong("Trackers Lens"),
        _.span("Cloud Command Center")
      )
    ),
    footer: PlanCard(CMSwift)
  },
    _.nav({ class: "tl-nav", "aria-label": "Main navigation" },
      ...navigationItems.map((item) => NavLink(CMSwift, ctx, item))
    )
  );
}

function NavLink(CMSwift, ctx, item) {
  const active = ctx.path === item.path || (item.path === "/app" && ctx.path === "/app/");

  return _.a({
    class: `tl-nav-link${active ? " is-active" : ""}`,
    href: item.path,
    "aria-current": active ? "page" : null
  },
    icon(CMSwift, item.icon, { size: 21 }),
    _.span(item.label)
  );
}

function PlanCard(CMSwift) {
  return _.Card({ class: "tl-plan-card" },
    _.span({ class: "tl-muted" }, "Current Plan"),
    _.Row({ align: "center", gap: "sm" },
      _.strong({ class: "tl-plan-name" }, "Pro"),
      _.Badge({ class: "tl-plan-star", label: "star", icon: icon(CMSwift, "star-fill", { size: 14 }) })
    ),
    _.Progress({ class: "tl-plan-progress", value: 70, max: 100 }),
    _.span({ class: "tl-muted" }, "7 / 10 boxes published"),
    _.Btn({ class: "tl-upgrade-btn", color: "warning" }, "Upgrade Plan")
  );
}
