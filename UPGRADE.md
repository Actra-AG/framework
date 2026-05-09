# Upgrade Guide

This document tracks all changes that affect the HTML output (rendering) and CSS styling of the Actra Framework, ordered by date.

---

## 2026

### January 31, 2026
* **DbResultTable Rendering:** The `div.table-meta-footer` is no longer rendered if it is empty (e.g., when no pagination is present).

### January 27, 2026
* **ExceptionHandler:** Replaced internal placeholders array with `HtmlReplacementCollection` for improved rendering management.

---

## 2025

### December 15, 2025
* **Navigation IDs:** If no individual value for `id="nav-X"` is set, the default entry now considers the optional `fileGroup`.
    * With fileGroup: `id="nav-{fileGroup}-{fileName}"`.
    * Without fileGroup: `id="nav-{fileName}"` (extensions are removed).

### December 10, 2025
* **Template Engine:** The `<tst:if>` tag now supports the **IN** operator.

### November 25, 2025
* **Error Documents:**
    * The placeholders `language` and `charset` can now be used in `error_docs`.
    * Implemented `SnippetTag` and `TemplateEngine` for error documents; `<tst>` placeholders must now be used.

### November 20, 2025
* **Automatic Active State:** The ID `id="nav-{fileName}"` is automatically supplemented with `class="active"`, even without additional PHP code.

### November 12, 2025
* **Breaking Change (Naming):** * The placeholder `bodyID` must be replaced by `bodyClassName`.
    * CSS references to classes starting with `body_*` must be updated to `body-*`.

### September 09, 2025
* **File Uploads:**
    * If only one file is allowed, the hint (max. 1) is no longer displayed.
    * The `<ul>` class for cached file lists was renamed from `.list-fileupload` to `.fileupload-list`.

### August 24, 2025
* **TextAreas:** Now support the optional `autofocus` attribute.

### August 23, 2025
* **Table Styling:** * The class `.table-global-wrap` was replaced by `.table-wrap`.
    * The `<table>` tag now receives the `.table` class by default.

### August 14, 2025
* **Table Meta:** Results count and pagination are now wrapped in `div.table-meta` (specifically `.table-meta-header` or `.table-meta-footer`).

### June 11, 2025
* **Accessibility & Forms:** * Form rendering now includes `role="alert"` and `aria-live="assertive"` for errors.
    * Standard rendering for options now includes `.form-check` and `.form-check-label` classes.

### May 20, 2025
* **Input Attributes:** Added support for the optional `maxlength` attribute in input fields.

### April 03, 2025
* **Form Markup:** In form error paragraphs (`<p>`), the `<b>` tag was replaced by `<strong>` for single error messages.

### March 26, 2025
* **Form Attributes:** Added an optional `novalidate` attribute for forms.

### January 03, 2025
* **Input Types:** `DateField` now renders as `type="date"` and `TimeField` as `type="time"`.