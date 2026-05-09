# Actra Framework (Legacy)

⚠️ **Important Notice: This framework is deprecated.**

This project is no longer maintained and has been fully replaced by the [yuf framework](https://github.com/Actra-AG/yuf). New projects should use yuf instead.

---

## 📢 Upgrade Guide

If you are a frontend developer working with an older version of this framework, please refer to the [**UPGRADE.md**](./UPGRADE.md) file. It contains essential details regarding changes in HTML rendering, CSS styling, and breaking naming conventions.

---

## Overview

The Actra Framework was a full-stack PHP framework providing a robust set of components for building web applications.

### Key Components

- **Core Engine:** Request/Response handling, routing, and environment configuration.
- **Authentication:** Support for various methods including Microsoft Identity and JWT.
- **Database Layer:** Query building and execution.
- **Form System:** Flexible form creation, validation, and rendering.
- **Template Engine:** Custom HTML snippet and document generation.
- **Utilities:**
    - Mailer (SMTP, HTML/Text)
    - Pagination
    - Phone number validation/rendering
    - Security (CSP, CSRF)
    - Session management

## Usage

The framework is typically initialized via the `framework\Core` class:

```php
use framework\Core;

$core = new Core(
    defaultTimeZone: 'Europe/Zurich',
    siteDirectoryName: 'site',
    // ... other configuration
);
```

## License

This project is licensed under the [MIT License](./LICENSE).
