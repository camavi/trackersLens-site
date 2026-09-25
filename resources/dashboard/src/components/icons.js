export function icon(CMSwift, name, options = {}) {
  return _.Icon({
    name: `#${name}`,
    size: options.size || 20,
    class: options.class || "",
    style: options.style || {}
  });
}

export function iconButton(CMSwift, name, label, options = {}) {
  return _.Tooltip({ label },
    _.Btn({
      class: `tl-icon-button ${options.class || ""}`.trim(),
      "aria-label": label,
      outline: true,
      onClick: options.onClick
    }, icon(CMSwift, name, { size: options.size || 20 }))
  );
}
