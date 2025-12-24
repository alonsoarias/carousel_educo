# Changelog

Todos los cambios notables en este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2024-12-24

### Corregido
- Corregido el manejo de imágenes que no se guardaban ni mostraban correctamente
- Cambiado el file area de 'content' a 'slide_image' para consistencia
- Corregido el selector de número de slides (ahora usa array asociativo 1-5)
- Añadida sanitización HTML con `format_string()`, `format_text()` y `s()` para prevenir XSS
- Corregida validación de URLs para los botones

### Añadido
- Soporte para imágenes WebP
- ID único por instancia de carrusel para evitar conflictos
- Mejoras de accesibilidad (aria-labels, aria-current)
- Cadena de privacidad para cumplimiento GDPR
- Encabezados GPL y documentación PHPDoc en todos los archivos
- Opciones de caché en la entrega de archivos
- Este archivo CHANGELOG.md
- Archivo README.md con documentación completa

### Mejorado
- Responsividad del carrusel con media queries
- Los slides no activos se colapsan en el formulario de configuración
- Mejor manejo de errores y validaciones
- Documentación del código

## [1.0.0] - 2024-08-01

### Añadido
- Versión inicial del plugin
- Soporte para hasta 5 diapositivas
- Configuración de título, texto, imagen, botón y enlace por diapositiva
- Carrusel Bootstrap con controles de navegación
- Indicadores de diapositiva
- Rotación automática
- Soporte para español e inglés
- Sistema de permisos con capacidades Moodle
- Integración con sistema de archivos de Moodle
