import { DonutChart, ResourceChart, Sparkline } from "./charts.js";
import { icon } from "./icons.js";
import {
  activities,
  boxSegments,
  kpis,
  quickActions,
  resources,
  systemServices,
  topBoxes
} from "../services/dashboardData.js";

export function KpiGrid(CMSwift) {
  return _.Grid({ class: "tl-kpi-grid", cols: 1, gap: "md", tablet: { cols: 2 }, pc: { cols: 4 } },
    ...kpis.map((item) => _.GridCol({},
      _.Kpi({
        class: `tl-kpi-card tl-panel is-${item.tone}`,
        title: item.label,
        value: item.value,
        delta: item.delta,
        note: "vs last 30 days",
        trend: "up",
        icon: icon(CMSwift, item.icon, { size: 22 }),
        aside: icon(CMSwift, "dots-vertical", { size: 18 }),
        media: Sparkline(CMSwift, item.points, item.tone)
      })
    ))
  );
}

export function ActivityCard(CMSwift) {
  return _.Card({
    class: "tl-panel tl-activity-card",
    title: "Recent Activity",
    aside: _.Btn({ class: "tl-soft-button", outline: true }, "View all")
  },
    _.div({ class: "tl-activity-list" },
      ...activities.map((item) => _.div({ class: "tl-activity-row" },
        _.span({ class: `tl-icon-orb is-${item.tone}` }, icon(CMSwift, item.icon, { size: 20 })),
        _.div({ class: "tl-list-copy" },
          _.strong(item.title),
          _.span(item.detail)
        ),
        _.time(item.time)
      ))
    )
  );
}

export function AccountOverviewCard(CMSwift) {
  const total = boxSegments.reduce((sum, item) => sum + item.value, 0);

  return _.Card({ class: "tl-panel tl-account-card", title: "Account Overview" },
    _.Row({ class: "tl-account-layout", align: "center", gap: "lg", wrap: true },
      DonutChart(CMSwift, boxSegments),
      _.div({ class: "tl-segment-list" },
        ...boxSegments.map((item) => _.div({ class: "tl-segment-row" },
          _.span({ class: "tl-segment-dot", style: { background: item.color } }),
          _.span(item.label),
          _.strong(`${item.value} (${Math.round((item.value / total) * 100)}%)`)
        ))
      )
    )
  );
}

export function ResourceUsageCard(CMSwift) {
  return _.Card({
    class: "tl-panel tl-resource-card",
    title: "Resource Usage",
    aside: _.Btn({ class: "tl-soft-button", outline: true, iconRight: icon(CMSwift, "chevron-down", { size: 14 }) }, "Last 7 days")
  },
    _.div({ class: "tl-chart-legend" },
      ...resources.map((item) => _.span({},
        _.i({ style: { background: item.color } }),
        item.label
      ))
    ),
    ResourceChart(CMSwift, resources)
  );
}

export function TopBoxesCard(CMSwift) {
  return _.Card({ class: "tl-panel", title: "Top Performing Boxes" },
    _.div({ class: "tl-box-list" },
      ...topBoxes.map((item, index) => _.div({ class: "tl-box-row" },
        _.span({ class: `tl-box-icon is-${index % 4}` }, icon(CMSwift, item.icon, { size: 17 })),
        _.strong(item.name),
        _.Badge({ class: "tl-access-badge", label: item.access, outline: true }),
        _.span({ class: "tl-views" }, _.strong(item.views), " Views"),
        _.span({ class: "tl-growth" }, item.growth)
      ))
    )
  );
}

export function QuickActionsCard(CMSwift) {
  return _.Card({ class: "tl-panel", title: "Quick Actions" },
    _.Grid({ cols: 1, gap: "md", tablet: { cols: 2 } },
      ...quickActions.map((item) => _.GridCol({},
        _.Card({ class: "tl-action-card", clickable: true },
          _.Row({ align: "center", gap: "md" },
            _.span({ class: "tl-action-icon" }, icon(CMSwift, item.icon, { size: 30 })),
            _.div({ class: "tl-list-copy" },
              _.strong(item.title),
              _.span(item.subtitle)
            )
          )
        )
      ))
    )
  );
}

export function SystemStatusCard(CMSwift) {
  return _.Card({
    class: "tl-panel tl-status-card",
    title: "System Status",
    aside: _.Badge({ class: "tl-live-badge", label: "All systems operational" })
  },
    _.div({ class: "tl-status-list" },
      ...systemServices.map((name) => _.div({ class: "tl-status-row" },
        _.span({ class: "tl-status-dot" }),
        _.strong(name),
        _.span("Operational")
      ))
    ),
    _.Separator(),
    _.Row({ align: "center", gap: "sm" },
      _.span({ class: "tl-muted" }, "Need help?"),
      _.a({ class: "tl-link", href: "/docs/api-contract" }, "View Documentation", icon(CMSwift, "external-link", { size: 14 }))
    )
  );
}
