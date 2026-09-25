const STORAGE_KEY = "trackerlens.locale";

export const supportedLocales = [
  { code: "it", label: "Italiano" },
  { code: "en", label: "English" },
  { code: "fr", label: "Francais" },
  { code: "es", label: "Espanol" },
  { code: "de", label: "Deutsch" }
];

const messages = {
  it: {
    "app.title": "TrackerLens",
    "app.subtitle": "Dashboard",
    "brand.workspace": "Workspace",
    "nav.aria": "Navigazione principale",
    "nav.dashboard": "Dashboard",
    "nav.projects": "Progetti",
    "nav.settings": "Impostazioni",
    "language.label": "Lingua",
    "dashboard.title": "Dashboard",
    "dashboard.subtitle": "Base CMSwift pronta per collegare i dati TrackerLens.",
    "dashboard.stats.sources": "Sorgenti",
    "dashboard.stats.reports": "Report",
    "dashboard.stats.status": "Stato",
    "dashboard.stats.toConfigure": "Da configurare",
    "dashboard.stats.inPreparation": "In preparazione",
    "dashboard.stats.ready": "Ready",
    "dashboard.stats.cmswiftActive": "CMSwift attivo",
    "dashboard.nextSteps": "Prossimi passi",
    "dashboard.nextSteps.api": "Collegare API e store applicativo",
    "dashboard.nextSteps.screens": "Definire schermate operative",
    "dashboard.nextSteps.components": "Preparare componenti riutilizzabili in src/components",
    "projects.title": "Progetti",
    "projects.subtitle": "Area pronta per liste, filtri e dettaglio progetto.",
    "projects.archive": "Archivio progetti",
    "projects.empty.title": "Nessun progetto caricato",
    "projects.empty.description": "La struttura e' pronta per collegare i dati reali.",
    "settings.title": "Impostazioni",
    "settings.subtitle": "Preferenze locali della dashboard.",
    "settings.appearance": "Aspetto",
    "settings.darkTheme": "Tema scuro",
    "notFound.title": "Pagina non trovata",
    "notFound.description": "Il percorso richiesto non esiste nella dashboard."
  },
  en: {
    "app.title": "TrackerLens",
    "app.subtitle": "Dashboard",
    "brand.workspace": "Workspace",
    "nav.aria": "Main navigation",
    "nav.dashboard": "Dashboard",
    "nav.projects": "Projects",
    "nav.settings": "Settings",
    "language.label": "Language",
    "dashboard.title": "Dashboard",
    "dashboard.subtitle": "CMSwift base ready to connect TrackerLens data.",
    "dashboard.stats.sources": "Sources",
    "dashboard.stats.reports": "Reports",
    "dashboard.stats.status": "Status",
    "dashboard.stats.toConfigure": "To configure",
    "dashboard.stats.inPreparation": "In preparation",
    "dashboard.stats.ready": "Ready",
    "dashboard.stats.cmswiftActive": "CMSwift active",
    "dashboard.nextSteps": "Next steps",
    "dashboard.nextSteps.api": "Connect APIs and application store",
    "dashboard.nextSteps.screens": "Define operational screens",
    "dashboard.nextSteps.components": "Prepare reusable components in src/components",
    "projects.title": "Projects",
    "projects.subtitle": "Area ready for lists, filters, and project detail.",
    "projects.archive": "Project archive",
    "projects.empty.title": "No projects loaded",
    "projects.empty.description": "The structure is ready to connect real data.",
    "settings.title": "Settings",
    "settings.subtitle": "Local dashboard preferences.",
    "settings.appearance": "Appearance",
    "settings.darkTheme": "Dark theme",
    "notFound.title": "Page not found",
    "notFound.description": "The requested path does not exist in the dashboard."
  },
  fr: {
    "app.title": "TrackerLens",
    "app.subtitle": "Tableau de bord",
    "brand.workspace": "Espace de travail",
    "nav.aria": "Navigation principale",
    "nav.dashboard": "Tableau de bord",
    "nav.projects": "Projets",
    "nav.settings": "Parametres",
    "language.label": "Langue",
    "dashboard.title": "Tableau de bord",
    "dashboard.subtitle": "Base CMSwift prete pour connecter les donnees TrackerLens.",
    "dashboard.stats.sources": "Sources",
    "dashboard.stats.reports": "Rapports",
    "dashboard.stats.status": "Statut",
    "dashboard.stats.toConfigure": "A configurer",
    "dashboard.stats.inPreparation": "En preparation",
    "dashboard.stats.ready": "Pret",
    "dashboard.stats.cmswiftActive": "CMSwift actif",
    "dashboard.nextSteps": "Prochaines etapes",
    "dashboard.nextSteps.api": "Connecter les API et le store applicatif",
    "dashboard.nextSteps.screens": "Definir les ecrans operationnels",
    "dashboard.nextSteps.components": "Preparer les composants reutilisables dans src/components",
    "projects.title": "Projets",
    "projects.subtitle": "Zone prete pour listes, filtres et detail projet.",
    "projects.archive": "Archive des projets",
    "projects.empty.title": "Aucun projet charge",
    "projects.empty.description": "La structure est prete pour connecter les donnees reelles.",
    "settings.title": "Parametres",
    "settings.subtitle": "Preferences locales du tableau de bord.",
    "settings.appearance": "Apparence",
    "settings.darkTheme": "Theme sombre",
    "notFound.title": "Page introuvable",
    "notFound.description": "Le chemin demande n'existe pas dans le tableau de bord."
  },
  es: {
    "app.title": "TrackerLens",
    "app.subtitle": "Panel",
    "brand.workspace": "Espacio de trabajo",
    "nav.aria": "Navegacion principal",
    "nav.dashboard": "Panel",
    "nav.projects": "Proyectos",
    "nav.settings": "Ajustes",
    "language.label": "Idioma",
    "dashboard.title": "Panel",
    "dashboard.subtitle": "Base CMSwift lista para conectar los datos de TrackerLens.",
    "dashboard.stats.sources": "Fuentes",
    "dashboard.stats.reports": "Informes",
    "dashboard.stats.status": "Estado",
    "dashboard.stats.toConfigure": "Por configurar",
    "dashboard.stats.inPreparation": "En preparacion",
    "dashboard.stats.ready": "Listo",
    "dashboard.stats.cmswiftActive": "CMSwift activo",
    "dashboard.nextSteps": "Proximos pasos",
    "dashboard.nextSteps.api": "Conectar APIs y store aplicativo",
    "dashboard.nextSteps.screens": "Definir pantallas operativas",
    "dashboard.nextSteps.components": "Preparar componentes reutilizables en src/components",
    "projects.title": "Proyectos",
    "projects.subtitle": "Area lista para listas, filtros y detalle de proyecto.",
    "projects.archive": "Archivo de proyectos",
    "projects.empty.title": "No hay proyectos cargados",
    "projects.empty.description": "La estructura esta lista para conectar datos reales.",
    "settings.title": "Ajustes",
    "settings.subtitle": "Preferencias locales del panel.",
    "settings.appearance": "Apariencia",
    "settings.darkTheme": "Tema oscuro",
    "notFound.title": "Pagina no encontrada",
    "notFound.description": "La ruta solicitada no existe en el panel."
  },
  de: {
    "app.title": "TrackerLens",
    "app.subtitle": "Dashboard",
    "brand.workspace": "Workspace",
    "nav.aria": "Hauptnavigation",
    "nav.dashboard": "Dashboard",
    "nav.projects": "Projekte",
    "nav.settings": "Einstellungen",
    "language.label": "Sprache",
    "dashboard.title": "Dashboard",
    "dashboard.subtitle": "CMSwift-Basis bereit zum Verbinden der TrackerLens-Daten.",
    "dashboard.stats.sources": "Quellen",
    "dashboard.stats.reports": "Berichte",
    "dashboard.stats.status": "Status",
    "dashboard.stats.toConfigure": "Zu konfigurieren",
    "dashboard.stats.inPreparation": "In Vorbereitung",
    "dashboard.stats.ready": "Bereit",
    "dashboard.stats.cmswiftActive": "CMSwift aktiv",
    "dashboard.nextSteps": "Nachste Schritte",
    "dashboard.nextSteps.api": "APIs und Application Store verbinden",
    "dashboard.nextSteps.screens": "Operative Ansichten definieren",
    "dashboard.nextSteps.components": "Wiederverwendbare Komponenten in src/components vorbereiten",
    "projects.title": "Projekte",
    "projects.subtitle": "Bereich bereit fur Listen, Filter und Projektdetails.",
    "projects.archive": "Projektarchiv",
    "projects.empty.title": "Keine Projekte geladen",
    "projects.empty.description": "Die Struktur ist bereit, reale Daten zu verbinden.",
    "settings.title": "Einstellungen",
    "settings.subtitle": "Lokale Dashboard-Einstellungen.",
    "settings.appearance": "Darstellung",
    "settings.darkTheme": "Dunkles Theme",
    "notFound.title": "Seite nicht gefunden",
    "notFound.description": "Der angeforderte Pfad existiert im Dashboard nicht."
  }
};

export function createI18n() {
  const locale = normalizeLocale(readStoredLocale() || navigator.language || "it");
  setDocumentLocale(locale);

  return {
    locale,
    t(key) {
      return messages[locale]?.[key] || messages.it[key] || key;
    },
    setLocale(nextLocale) {
      const normalized = normalizeLocale(nextLocale);
      localStorage.setItem(STORAGE_KEY, normalized);
      setDocumentLocale(normalized);
      window.location.reload();
    }
  };
}

function readStoredLocale() {
  try {
    return localStorage.getItem(STORAGE_KEY);
  } catch {
    return null;
  }
}

function normalizeLocale(locale) {
  const code = String(locale).slice(0, 2).toLowerCase();
  return supportedLocales.some((item) => item.code === code) ? code : "it";
}

function setDocumentLocale(locale) {
  document.documentElement.lang = locale;
}
