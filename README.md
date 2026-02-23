# 🔄 Migración PFG: Core Backend a Laravel 11

Este repositorio representa la evolución técnica del proyecto original, donde se ha realizado una migración integral del backend desde Java/Spring Boot hacia un ecosistema **PHP 8.3+ con Laravel 11**. El objetivo principal ha sido aplicar patrones de diseño avanzados para garantizar un desacoplamiento total y una escalabilidad de grado industrial.

## 🧠 Ingeniería de Software y Patrones de Diseño

En esta reescritura se han aplicado principios de **Clean Architecture** para asegurar que el sistema sea mantenible y testeable:

* **Arquitectura Hexagonal:** Aislamiento estricto de la lógica de dominio frente a frameworks y agentes externos.
* **DDD (Domain-Driven Design):** Modelado del sistema basado en el negocio, utilizando **Value Objects** para garantizar la validez de los datos y **Entidades** ricas.
* **CQRS (Command Query Responsibility Segregation):** Separación completa de las operaciones de lectura (Queries) y escritura (Commands) mediante un **Command Bus** para una gestión de acciones más limpia.
* **Patrón Repository:** Abstracción de la capa de persistencia para facilitar el intercambio de fuentes de datos.

## ⚡ Optimización y Seguridad

* **Procesamiento Asíncrono:** Implementación de **Jobs y Queues** (Colas) para la gestión de notificaciones y tareas pesadas, optimizando los tiempos de respuesta de la API.
* **Manejo de Excepciones:** Centralización de la lógica de errores mediante **Traits** personalizados, proporcionando respuestas estandarizadas y profesionales.
* **Seguridad y Auth:** Sistema de autenticación moderna mediante **Laravel Sanctum** y gestión de permisos granular.
* **Vistas Dinámicas:** Uso de **Blade** para la generación de componentes específicos y comunicación eficiente con el frontend.

## 🛠️ Despliegue e Infraestructura

El proyecto está diseñado para funcionar en entornos aislados mediante **Docker**. Para obtener las instrucciones detalladas de despliegue, scripts de automatización (`cleanup.sh`) y configuración de infraestructura, consulta el repositorio principal:

* 🔗 **[Repositorio Monolito Principal](https://github.com/PauMorillas/PFG-DAW-Monolito)**

## 📈 Metodología de Trabajo
* **Git Flow:** Control de versiones con ramas de funcionalidad y revisiones de código.
* **Agilidad:** Gestión de tareas bajo marcos **Scrum/Kanban** para un desarrollo iterativo.

---
**Autor:** Pau Morillas Huerta - Proyecto Final de Grado (DAW)
