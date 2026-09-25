# Public website — desktop app positioning

Updated: 2026-09-25. Scope: public website only. The attempted web account refresh was reverted after the user clarified that the intended target was the desktop app profile.

The website presents TL as a local AI operating environment in a desktop app.
Python, Node.js/JavaScript, Flow Map, configurable AI models and local SQLite
replace the former browser-extension story. Italian, English and Spanish cover
all public routes, forms, metadata and the illustrative workflow preview.

## Evidence from the TL App repository

Reviewed in the sibling `trackerLens` application:

- `package.json`, `electron/main.cjs`, `core/desktop/tl-core.cjs`: Electron app,
  Node.js host, restricted Core boundary and JavaScript execution.
- `docs/ai/flow-map/overview.md`: nodes, ports, channels, runtime state and logs.
- `docs/ai/runtime/python-node-sdk.md`, `runtimes/python/packs/`: managed NLP,
  embeddings, hybrid RAG, reranking, annotations and graph-relations packages.
- `docs/ai/current-focus.md`, `js/aiRuntimeCenter.js`: provider profiles, local
  and external models, API/Login connections and independent node settings.
- `docs/ai/project-state.md`: desktop SQLite persistence and app navigation.

## Editorial boundaries

- Node.js is the desktop/Core host; do not imply unrestricted npm or Node.js
  script execution from a Flow Map node.
- Python capabilities depend on installed managed packages and models.
- Custom Node sandbox execution requires availability, permissions and activation.
- Local storage does not imply that configured external providers receive no data.
- Do not announce download links, supported OS installers or release dates until
  artifacts are available. The existing launch notification form remains the CTA.
- Remove unsupported usage figures and release-completion percentages.
- Marketplace, sync and collaboration remain future work. Web dashboard mock data
  is not evidence of desktop functionality or cloud feature availability.
- Development snapshots are not public release announcements.

Validation: landing asset build, Laravel feature suite, full translation-key audit,
Chrome desktop/mobile rendering and same-page anchors. No dashboard code changed in this positioning work.
