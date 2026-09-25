function pointsToPath(points, width, height, padding = 2) {
  const min = Math.min(...points);
  const max = Math.max(...points);
  const range = max - min || 1;
  const step = (width - padding * 2) / (points.length - 1);

  return points.map((point, index) => {
    const x = padding + index * step;
    const y = height - padding - ((point - min) / range) * (height - padding * 2);
    return `${index === 0 ? "M" : "L"}${x.toFixed(1)} ${y.toFixed(1)}`;
  }).join(" ");
}

export function Sparkline(CMSwift, points, tone) {
  const width = 220;
  const height = 54;
  const path = pointsToPath(points, width, height, 3);

  return _.svg({
    class: `tl-sparkline is-${tone}`,
    viewBox: `0 0 ${width} ${height}`,
    role: "img",
    "aria-label": "Trend chart"
  },
    _.path({ class: "tl-sparkline-glow", d: path }),
    _.path({ class: "tl-sparkline-line", d: path })
  );
}

export function DonutChart(CMSwift, segments) {
  const total = segments.reduce((sum, item) => sum + item.value, 0);
  let offset = 25;
  const circles = segments.map((item) => {
    const dash = (item.value / total) * 100;
    const circle = _.circle({
      class: "tl-donut-segment",
      cx: 70,
      cy: 70,
      r: 52,
      pathLength: 100,
      stroke: item.color,
      "stroke-dasharray": `${dash} ${100 - dash}`,
      "stroke-dashoffset": -offset
    });
    offset += dash;
    return circle;
  });

  return _.div({ class: "tl-donut-wrap" },
    _.svg({ class: "tl-donut", viewBox: "0 0 140 140", role: "img", "aria-label": "Account overview donut chart" },
      _.circle({ class: "tl-donut-track", cx: 70, cy: 70, r: 52 }),
      ...circles
    ),
    _.div({ class: "tl-donut-center" },
      _.span("Total"),
      _.strong(String(total)),
      _.span("Boxes")
    )
  );
}

export function ResourceChart(CMSwift, resources) {
  const width = 390;
  const height = 210;
  const cpu = resources[0].values;
  const network = resources[2].values;
  const barWidth = 8;

  return _.div({ class: "tl-resource-chart" },
    _.svg({ viewBox: `0 0 ${width} ${height}`, role: "img", "aria-label": "Resource usage chart" },
      ...[0, 25, 50, 75, 100].map((tick) => _.g({},
        _.text({ x: 0, y: 184 - tick * 1.52, class: "tl-chart-label" }, `${tick}%`),
        _.line({ x1: 42, x2: 378, y1: 180 - tick * 1.52, y2: 180 - tick * 1.52, class: "tl-chart-grid" })
      )),
      ...resources[1].values.map((value, index) => {
        const x = 58 + index * 22;
        const heightValue = value * 1.52;
        return _.rect({
          class: "tl-resource-bar",
          x,
          y: 180 - heightValue,
          width: barWidth,
          height: heightValue,
          fill: index % 2 ? "#19c9d2" : "#21e782"
        });
      }),
      _.path({ class: "tl-resource-line is-network", d: pointsToPath(network, 330, 110, 0), transform: "translate(48 66)" }),
      _.path({ class: "tl-resource-line is-cpu", d: pointsToPath(cpu, 330, 136, 0), transform: "translate(48 44)" })
    )
  );
}
