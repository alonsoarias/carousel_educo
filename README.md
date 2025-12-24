# [EduCo] Carousel Educo

Plugin de bloque para Moodle que permite crear un carrusel (slider) de imágenes con títulos, textos, botones y enlaces personalizables.

## Requisitos

- **Moodle:** 3.9 o superior
- **Tema:** edash (dependencia obligatoria)
- **PHP:** 7.2 o superior

## Características

- Hasta 5 diapositivas configurables por instancia
- Cada diapositiva soporta:
  - Imagen personalizada (JPG, PNG, GIF, WebP)
  - Título
  - Texto descriptivo
  - Botón con enlace personalizable
- Carrusel responsive con Bootstrap
- Controles de navegación (anterior/siguiente)
- Indicadores de diapositiva
- Rotación automática cada 5 segundos
- Múltiples instancias del bloque en la misma página
- Soporte multiidioma (Español e Inglés)

## Instalación

1. Descarga el plugin y extrae los archivos
2. Copia la carpeta `carousel_educo` a `/blocks/` en tu instalación de Moodle
3. Accede como administrador a tu sitio Moodle
4. Ve a **Administración del sitio > Notificaciones** para completar la instalación
5. Purga las cachés si es necesario

## Configuración

1. Activa el modo de edición en la página donde deseas añadir el carrusel
2. Haz clic en **Agregar un bloque** y selecciona **[EduCo] Carrusel Educo**
3. Haz clic en el icono de configuración del bloque
4. Configura:
   - **Número de diapositivas:** Selecciona de 1 a 5
   - Para cada diapositiva:
     - **Título:** Texto que aparece sobre la imagen
     - **Texto:** Descripción que aparece debajo del título
     - **Imagen:** Sube una imagen (máximo 10MB)
     - **Texto del botón:** Texto para el botón de acción (opcional)
     - **URL del botón:** Enlace del botón (opcional)

## Estructura del Plugin

```
carousel_educo/
├── block_carousel_educo.php    # Clase principal del bloque
├── edit_form.php               # Formulario de configuración
├── lib.php                     # Funciones auxiliares (pluginfile)
├── version.php                 # Información de versión
├── README.md                   # Este archivo
├── CHANGELOG.md                # Historial de cambios
├── db/
│   └── access.php              # Definición de capacidades
└── lang/
    ├── en/
    │   └── block_carousel_educo.php   # Cadenas en inglés
    └── es/
        └── block_carousel_educo.php   # Cadenas en español
```

## Capacidades

| Capacidad | Descripción | Roles por defecto |
|-----------|-------------|-------------------|
| `block/carousel_educo:addinstance` | Añadir bloque a cursos | Profesor editor, Manager |
| `block/carousel_educo:myaddinstance` | Añadir bloque a Mi página | Usuario |

## Dependencias

Este plugin requiere el **tema edash** instalado y activo. El bloque utiliza funcionalidades específicas de este tema para su correcto funcionamiento.

## Soporte de Idiomas

- Español (es)
- Inglés (en)

## Licencia

Este plugin está licenciado bajo la [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.html).

## Autor

**EduCo** - 2024

## Soporte

Para reportar problemas o solicitar mejoras, por favor crea un issue en el repositorio del proyecto.
