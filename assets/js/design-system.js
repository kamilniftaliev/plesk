/**
 * Design System JavaScript
 * Auto-applies design system classes and manages icon mapping
 * Version: 1.0.0
 */

(function () {
  "use strict";

  /**
   * Icon mapping based on field labels and input names
   * Maps field identifiers to Font Awesome icon classes
   */
  const iconMapping = {
    // User & Authentication
    username: "fa-user",
    user: "fa-user",
    name: "fa-user",
    "full name": "fa-user",
    "first name": "fa-user",
    "last name": "fa-user",
    fname: "fa-user",
    lname: "fa-user",

    // Email
    email: "fa-envelope",
    "e-mail": "fa-envelope",
    mail: "fa-envelope",

    // Password
    password: "fa-lock",
    pass: "fa-lock",
    "current password": "fa-lock",
    "new password": "fa-key",
    "confirm password": "fa-key",
    "repeat password": "fa-key",

    // Phone & Contact
    phone: "fa-phone",
    telephone: "fa-phone",
    mobile: "fa-mobile",
    contact: "fa-phone",
    "phone number": "fa-phone",
    whatsapp: "fa-whatsapp",
    telegram: "fa-telegram",

    // Address & Location
    address: "fa-map-marker",
    location: "fa-map-marker",
    city: "fa-building",
    state: "fa-map",
    country: "fa-globe",
    region: "fa-map",
    zip: "fa-map-pin",
    postal: "fa-map-pin",

    // Business
    company: "fa-building",
    organization: "fa-building",
    business: "fa-briefcase",

    // Identification
    id: "fa-id-card",
    license: "fa-id-card",
    authid: "fa-id-badge",
    "auth id": "fa-id-badge",
    imei: "fa-mobile",
    serial: "fa-barcode",

    // Financial
    price: "fa-dollar",
    cost: "fa-dollar",
    amount: "fa-dollar",
    balance: "fa-money",
    credit: "fa-credit-card",
    payment: "fa-credit-card",
    salary: "fa-money",
    commission: "fa-percent",

    // Server & Technical
    server: "fa-server",
    host: "fa-server",
    hostname: "fa-server",
    ip: "fa-globe",
    "ip address": "fa-globe",
    port: "fa-plug",
    url: "fa-link",
    domain: "fa-globe",
    api: "fa-code",
    "api key": "fa-key",
    token: "fa-key",

    // Date & Time
    date: "fa-calendar",
    time: "fa-clock-o",
    datetime: "fa-calendar",
    birthday: "fa-birthday-cake",
    expiry: "fa-calendar-times-o",
    expire: "fa-calendar-times-o",

    // Status & Type
    status: "fa-info-circle",
    type: "fa-tag",
    category: "fa-tags",
    role: "fa-user-circle",
    level: "fa-signal",
    priority: "fa-flag",

    // Description & Content
    description: "fa-align-left",
    note: "fa-sticky-note",
    notes: "fa-sticky-note",
    comment: "fa-comment",
    message: "fa-comment",
    remarks: "fa-file-text",

    // Database
    database: "fa-database",
    table: "fa-table",
    query: "fa-search",

    // Device
    device: "fa-mobile",
    model: "fa-mobile",
    brand: "fa-tag",

    // Customer
    customer: "fa-users",
    client: "fa-user",
    reseller: "fa-user-circle",

    // Network
    network: "fa-wifi",
    wifi: "fa-wifi",
    connection: "fa-plug",

    // File
    file: "fa-file",
    upload: "fa-upload",
    download: "fa-download",
    attachment: "fa-paperclip",

    // Search
    search: "fa-search",
    find: "fa-search",
    query: "fa-search",

    // Settings
    setting: "fa-cog",
    settings: "fa-cogs",
    config: "fa-wrench",
    configuration: "fa-wrench",

    // Security
    security: "fa-shield",
    permission: "fa-lock",
    access: "fa-key",

    // Default fallback
    default: "fa-pencil",
  };

  /**
   * Get icon class for a field based on label text or input name
   * @param {string} text - Label text or input name
   * @returns {string} Font Awesome icon class
   */
  function getIconForField(text) {
    if (!text) return iconMapping["default"];

    const normalized = text.toLowerCase().trim();

    // Direct match
    if (iconMapping[normalized]) {
      return iconMapping[normalized];
    }

    // Partial match - check if any key is contained in the text
    for (const [key, icon] of Object.entries(iconMapping)) {
      if (normalized.includes(key)) {
        return icon;
      }
    }

    return iconMapping["default"];
  }

  /**
   * Apply design system classes to form elements
   */
  function applyDesignSystem() {
    // Apply to all input elements
    document
      .querySelectorAll(
        'input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="tel"], input[type="url"]'
      )
      .forEach((input) => {
        if (!input.classList.contains("ds-input")) {
          input.classList.add("ds-input");
        }
      });

    // Apply to all select elements
    document.querySelectorAll("select").forEach((select) => {
      if (!select.classList.contains("ds-select")) {
        select.classList.add("ds-select");
      }
    });

    // Apply to all textarea elements
    document.querySelectorAll("textarea").forEach((textarea) => {
      if (!textarea.classList.contains("ds-textarea")) {
        textarea.classList.add("ds-textarea");
      }
    });

    // Apply to all buttons
    document.querySelectorAll("button.btn, .btn").forEach((btn) => {
      if (!btn.classList.contains("ds-btn")) {
        btn.classList.add("ds-btn");

        // Map Bootstrap button classes to design system
        if (btn.classList.contains("btn-primary")) {
          btn.classList.add("ds-btn-primary");
        } else if (btn.classList.contains("btn-success")) {
          btn.classList.add("ds-btn-success");
        } else if (btn.classList.contains("btn-danger")) {
          btn.classList.add("ds-btn-danger");
        } else if (btn.classList.contains("btn-warning")) {
          btn.classList.add("ds-btn-warning");
        } else if (btn.classList.contains("btn-info")) {
          btn.classList.add("ds-btn-info");
        } else if (btn.classList.contains("btn-default")) {
          btn.classList.add("ds-btn-default");
        }
      }
    });
  }

  /**
   * Apply icons to input-group elements
   */
  function applyInputGroupIcons() {
    document.querySelectorAll(".input-group").forEach((group) => {
      const input = group.querySelector("input, select, textarea");
      let addon = group.querySelector(".input-group-addon");

      if (!input) return;

      // Get the label text for this input
      let labelText = "";
      const formGroup = group.closest(".form-group");
      if (formGroup) {
        const label = formGroup.querySelector("label");
        if (label) {
          labelText = label.textContent;
        }
      }

      // Fallback to input name or placeholder
      if (!labelText) {
        labelText =
          input.getAttribute("name") || input.getAttribute("placeholder") || "";
      }

      // Get the appropriate icon
      const iconClass = getIconForField(labelText);

      // If addon doesn't exist, create it
      if (!addon) {
        addon = document.createElement("span");
        addon.className = "input-group-addon";
        group.insertBefore(addon, group.firstChild);
      }

      // Update icon if it's not already set correctly
      const existingIcon = addon.querySelector("i");
      if (existingIcon) {
        // Remove old icon classes (glyphicon or fa)
        existingIcon.className = "";
        existingIcon.classList.add("fa", iconClass);
      } else {
        // Create new icon
        const icon = document.createElement("i");
        icon.className = "fa " + iconClass;
        addon.innerHTML = "";
        addon.appendChild(icon);
      }
    });
  }

  /**
   * Convert input-group to ds-input-group structure
   */
  function convertToDesignSystemInputGroups() {
    document.querySelectorAll(".input-group").forEach((group) => {
      // Skip if already converted
      if (group.classList.contains("ds-input-group")) return;

      const input = group.querySelector("input, select, textarea");
      const addon = group.querySelector(".input-group-addon");

      if (!input || !addon) return;

      // Add design system class
      group.classList.add("ds-input-group");

      // Get icon from addon
      const icon = addon.querySelector("i");
      if (!icon) return;

      // Create new icon element for ds structure
      const dsIcon = document.createElement("span");
      dsIcon.className = "ds-input-icon";
      dsIcon.appendChild(icon.cloneNode(true));

      // Remove addon and append icon
      addon.remove();
      group.appendChild(dsIcon);
    });
  }

  /**
   * Initialize design system
   */
  function init() {
    // Apply design system classes
    applyDesignSystem();

    // Apply icons to input groups
    applyInputGroupIcons();

    // Optional: Convert to new structure (comment out if you want to keep Bootstrap structure)
    // convertToDesignSystemInputGroups();
  }

  /**
   * Public API
   */
  window.DesignSystem = {
    init: init,
    applyDesignSystem: applyDesignSystem,
    applyInputGroupIcons: applyInputGroupIcons,
    convertToDesignSystemInputGroups: convertToDesignSystemInputGroups,
    getIconForField: getIconForField,
    iconMapping: iconMapping,
  };

  // Auto-initialize on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
