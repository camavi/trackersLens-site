const LEGACY_SPRITE_PATHS = ["/_cmswift-fe/img/svg/tabler-icons-sprite.svg", "/cmswift-fe/img/svg/tabler-icons-sprite.svg"];
const EXTENSION_SAFE_SPRITE_PATH = "/build/dashboard/cmswift-fe/img/svg/tabler-icons-sprite.svg";
const PATCH_FLAG = Symbol.for("trackersLens.cmswiftIconPathPatch");

function rewriteSpriteUses(node) {
  if (!node?.querySelectorAll) return node;

  node.querySelectorAll("use").forEach((useNode) => {
    const href = useNode.getAttribute("href") || useNode.getAttribute("xlink:href") || "";
    const legacyPath = LEGACY_SPRITE_PATHS.find((path) => href.startsWith(path));
    if (!legacyPath) return;

    const nextHref = href.replace(legacyPath, EXTENSION_SAFE_SPRITE_PATH);
    useNode.setAttribute("href", nextHref);
    useNode.setAttribute("xlink:href", nextHref);
  });

  return node;
}

function patchIconFactory(target) {
  if (!target?.Icon || target.Icon[PATCH_FLAG]) return;

  const originalIcon = target.Icon;
  const patchedIcon = function patchedCmswiftIcon(...args) {
    return rewriteSpriteUses(originalIcon.apply(this, args));
  };

  Object.defineProperty(patchedIcon, PATCH_FLAG, { value: true });
  target.Icon = patchedIcon;
}

export function installCmswiftIconPathPatch(CMSwift) {
  patchIconFactory(CMSwift?.ui);
  patchIconFactory(window._);
}
