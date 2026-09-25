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
        _.Spinner({ label: "Cloud module loading" })
      )
    )
  );
}
