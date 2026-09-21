# Sepehr Elementor Widgets

> A modular Elementor addon built with PHP OOP architecture, focused on reusable widgets, clean asset management, secure AJAX interactions, and maintainable WordPress plugin development.

[![WordPress](https://img.shields.io/badge/WordPress-6.x-21759B?logo=wordpress\&logoColor=white)](https://wordpress.org/)
[![Elementor](https://img.shields.io/badge/Elementor-3.x-92003B?logo=elementor\&logoColor=white)](https://elementor.com/)
[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php\&logoColor=white)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## Overview

**Sepehr Elementor Widgets** is a custom Elementor addon developed as a practical WordPress plugin project.

The plugin is designed around a modular PHP architecture where widgets, core services, assets, and plugin initialization are separated into dedicated responsibilities.

The project focuses on:

* Reusable Elementor widgets
* Object-oriented PHP development
* Modular plugin architecture
* Secure AJAX interactions
* Conditional asset loading
* Responsive widget controls
* Translation-ready structure
* Maintainable and scalable code organization

---

## Widgets

### Dynamic Post Grid Pro

An advanced post grid widget designed for dynamic WordPress content.

**Features**

* AJAX category filtering
* AJAX Load More
* Custom post type support
* Configurable posts per page
* Responsive column controls
* Category include/exclude controls
* Responsive layouts
* Glassmorphism card styling
* Elementor editor controls

![Dynamic Post Grid Pro](screenshots/dynamic-post-grid-pro.png)

---

### Pricing Table Pro

A flexible pricing table widget designed for modern landing pages and service websites.

**Features**

* Multiple pricing options
* Custom pricing content
* Elementor-based controls
* Responsive layout
* Custom typography
* Custom styling controls

![Pricing Table Pro](screenshots/pricing-table-pro.png)

---

### Testimonial Carousel

A responsive testimonial carousel for displaying customer feedback and reviews.

**Features**

* Responsive carousel layout
* Custom testimonial content
* Image support
* Elementor controls
* Responsive behavior
* Dedicated CSS and JavaScript assets

![Testimonial Carousel](screenshots/testimonial-carousel.png)

---

### Image Card

A reusable image-based content card suitable for landing pages, services, portfolios, and promotional sections.

**Features**

* Image support
* Custom title and content
* Responsive layout
* Elementor controls
* Custom styling options

![Image Card](screenshots/image-card.png)

---

## Architecture

The plugin follows a modular PHP OOP structure.

```text
sepehr-elementor-widgets/
│
├── assets/
│   ├── css/
│   └── js/
│
├── includes/
│   ├── Autoloader.php
│   │
│   └── Core/
│       ├── Asset_Manager.php
│       ├── Plugin.php
│       └── Widget_Manager.php
│
├── languages/
│
├── screenshots/
│
├── widgets/
│   ├── Dynamic_Post_Grid.php
│   ├── Dynamic_Post_Grid_Pro.php
│   ├── Image_Card.php
│   ├── Pricing_Table.php
│   ├── Pricing_Table_Pro.php
│   └── Testimonial_Carousel.php
│
├── sepehr-elementor-widgets.php
├── README.md
└── LICENSE
```

### Core Responsibilities

| Component                      | Responsibility                          |
| ------------------------------ | --------------------------------------- |
| `sepehr-elementor-widgets.php` | Plugin entry point                      |
| `Autoloader.php`               | Class loading                           |
| `Plugin.php`                   | Plugin bootstrap and initialization     |
| `Widget_Manager.php`           | Elementor widget registration           |
| `Asset_Manager.php`            | CSS and JavaScript registration/loading |
| `widgets/`                     | Individual Elementor widgets            |
| `assets/css/`                  | Widget styles                           |
| `assets/js/`                   | Widget JavaScript                       |
| `languages/`                   | Translation resources                   |
| `screenshots/`                 | README documentation images             |

---

## Design Principles

The project follows several development principles:

### Separation of Responsibilities

Core plugin initialization, widget registration, asset management, and widget implementation are kept separate.

### One Widget — One Class

Each widget has its own PHP class, making the code easier to maintain and extend.

### Centralized Widget Registration

Widgets are registered through the `Widget_Manager` instead of being initialized independently from multiple locations.

### Centralized Asset Management

Stylesheets and JavaScript files are handled through the `Asset_Manager`.

### WordPress Safety

The plugin uses WordPress security practices such as:

* Direct-access protection
* WordPress APIs
* Sanitization
* Escaping
* Nonce-based AJAX validation where required
* Controlled AJAX requests

---

## AJAX

Some widgets use AJAX to provide dynamic interactions without requiring a full page reload.

For example, **Dynamic Post Grid Pro** uses AJAX for:

* Category filtering
* Loading additional posts

The AJAX flow is separated from the presentation layer so that the widget remains responsible for rendering while JavaScript handles the user interaction.

---

## Asset Management

The plugin uses dedicated CSS and JavaScript assets for widgets that require frontend behavior.

Assets are managed centrally instead of loading every project asset globally.

This approach helps keep the plugin structure clean and reduces unnecessary frontend resources.

---

## Elementor Integration

The widgets extend Elementor's native widget system using:

```php
Elementor\Widget_Base
```

Controls are registered through Elementor's control API, allowing users to configure widget content and styling directly from the Elementor editor.

The project also uses Elementor group controls where appropriate for typography, borders, shadows, and other design properties.

---

## Installation

### Requirements

* WordPress
* Elementor
* PHP 8.x
* A standard WordPress hosting environment

### Installation Steps

1. Download or clone the repository.

```bash
git clone https://github.com/sepehrwordpres/sepehr-elementor-widgets.git
```

2. Copy the plugin into:

```text
wp-content/plugins/
```

3. Open the WordPress admin dashboard.

4. Go to:

```text
Plugins → Installed Plugins
```

5. Activate **Sepehr Elementor Widgets**.

6. Open Elementor and start using the available widgets.

---

## Development

The project is currently structured as a custom WordPress Elementor addon and is intended to be extended with additional reusable widgets.

New widgets should follow the existing architecture:

```text
widgets/
    New_Widget.php
```

Then the widget should be registered through:

```text
includes/Core/Widget_Manager.php
```

Frontend assets should be handled through:

```text
includes/Core/Asset_Manager.php
```

This keeps widget registration and asset management centralized.

---

## Project Goals

The main goals of this project are:

* Build reusable Elementor components
* Demonstrate professional WordPress plugin architecture
* Practice object-oriented PHP
* Implement AJAX-powered Elementor interactions
* Maintain clean separation between PHP, CSS, and JavaScript
* Create a scalable foundation for future widgets

---

## Roadmap

Possible future improvements include:

* Additional advanced Elementor widgets
* More AJAX-powered components
* Expanded styling controls
* Additional accessibility improvements
* Performance optimizations
* Expanded translation support
* Automated testing
* WordPress coding-standard integration
* Public plugin documentation

---

## Screenshots

All project screenshots are available in the [`screenshots`](screenshots/) directory.

---

## Author

**Sepehr**

WordPress & PHP Developer focused on:

* WordPress Development
* Elementor Widget Development
* Custom Plugin Development
* PHP
* Laravel
* REST API
* SEO

---

## License

This project is licensed under the MIT License.

See the [LICENSE](LICENSE) file for details.
