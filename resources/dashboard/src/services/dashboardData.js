export const searchSuggestions = [
  { label: "Crypto Tracker", description: "Published box" },
  { label: "BTC Price WebSocket", description: "Cloud asset" },
  { label: "Production API Key", description: "Credential" },
  { label: "Marketplace documentation", description: "Docs" }
];

export const navigationItems = [
  { label: "Overview", path: "/app", icon: "layout-dashboard" },
  { label: "Cloud Assets", path: "/app/cloud-assets", icon: "cloud" },
  { label: "Marketplace", path: "/app/marketplace", icon: "box" },
  { label: "My Boxes", path: "/app/my-boxes", icon: "packages" },
  { label: "Publish Box", path: "/app/publish-box", icon: "cube-plus" },
  { label: "Profile", path: "/app/profile", icon: "user-circle" },
  { label: "API Keys", path: "/app/api-keys", icon: "key" },
  { label: "Statistics", path: "/app/statistics", icon: "chart-bar" },
  { label: "Settings", path: "/app/settings", icon: "settings" }
];

export const kpis = [
  {
    label: "Total Boxes",
    value: "24",
    delta: "+18%",
    tone: "gold",
    icon: "cube",
    points: [22, 26, 30, 24, 21, 25, 33, 26, 22, 19, 25, 20, 27, 38, 35, 41, 52, 50, 57]
  },
  {
    label: "Cloud Assets",
    value: "156",
    delta: "+23%",
    tone: "violet",
    icon: "cloud-data-connection",
    points: [18, 22, 27, 20, 16, 21, 29, 20, 15, 24, 22, 34, 31, 38, 52, 45, 50, 58, 61]
  },
  {
    label: "API Requests",
    value: "2.4M",
    delta: "+31%",
    tone: "cyan",
    icon: "api",
    points: [19, 22, 28, 24, 18, 23, 31, 27, 21, 29, 25, 34, 43, 38, 44, 55, 51, 58, 62]
  },
  {
    label: "Followers",
    value: "1.2K",
    delta: "+12%",
    tone: "green",
    icon: "users",
    points: [17, 21, 26, 20, 18, 19, 27, 25, 30, 28, 23, 25, 29, 37, 33, 38, 42, 51, 54]
  }
];

export const activities = [
  { icon: "cube", tone: "gold", title: "Box published", detail: "Crypto Tracker", time: "2m ago" },
  { icon: "box", tone: "violet", title: "New asset added", detail: "BTC Price WebSocket", time: "15m ago" },
  { icon: "key", tone: "gold", title: "API key generated", detail: "Production Key", time: "1h ago" },
  { icon: "refresh", tone: "cyan", title: "Box updated", detail: "News Monitor", time: "3h ago" },
  { icon: "receipt-2", tone: "green", title: "New sale", detail: "AI Sentiment Box", time: "5h ago" }
];

export const boxSegments = [
  { label: "Published", value: 12, color: "#ffd21f" },
  { label: "Private", value: 7, color: "#b95cff" },
  { label: "Drafts", value: 5, color: "#4ca3ff" }
];

export const resources = [
  { label: "CPU %", color: "#ffd21f", values: [18, 38, 28, 41, 32, 53, 37, 48, 60, 55, 64, 74, 66, 82] },
  { label: "Memory %", color: "#20e682", values: [12, 21, 18, 29, 20, 22, 16, 20, 18, 15, 21, 17, 32, 19] },
  { label: "Network %", color: "#c7d2e3", values: [74, 74, 73, 75, 72, 74, 76, 74, 77, 69, 68, 70, 71, 73] }
];

export const topBoxes = [
  { icon: "coin-bitcoin", name: "Crypto Tracker", access: "Public", views: "2.4K", growth: "+23%" },
  { icon: "brain", name: "AI Sentiment Analyzer", access: "Public", views: "1.8K", growth: "+18%" },
  { icon: "news", name: "News Monitor", access: "Private", views: "1.2K", growth: "+12%" },
  { icon: "chart-candle", name: "Stock Market Watch", access: "Public", views: "980", growth: "+8%" },
  { icon: "device-analytics", name: "Web Traffic Analyzer", access: "Private", views: "760", growth: "+5%" }
];

export const quickActions = [
  { icon: "upload", title: "Publish New Box", subtitle: "Share your box" },
  { icon: "key", title: "Create API Key", subtitle: "Generate new key" },
  { icon: "shopping-bag", title: "Browse Marketplace", subtitle: "Discover boxes" },
  { icon: "chart-histogram", title: "View Statistics", subtitle: "Detailed analytics" }
];

export const systemServices = [
  "API Service",
  "Cloud Storage",
  "Database",
  "WebSocket Gateway",
  "Marketplace"
];
