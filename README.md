Este repositorio constituye el núcleo del sistema, diseñado bajo una arquitectura híbrida que integra tecnologías de vanguardia para garantizar seguridad, escalabilidad y una experiencia de usuario fluida.
## 🧠 Ingeniería y Patrones de Diseño

En el desarrollo de estos componentes se han aplicado principios sólidos para asegurar un código mantenible y de calidad industrial:

* **Arquitectura Limpia:** Implementación de **Arquitectura Hexagonal** y **DDD (Domain-Driven Design)** para aislar la lógica de negocio de la infraestructura.
* **Patrones de Diseño:** Uso de **CQRS (Command Bus)** para la gestión de acciones, separacion de Query/Command junto con **Value Objects** y **Repositorios**.
* **Optimización Asíncrona:** Gestión de notificaciones mediante **Jobs y Colas**, mejorando el tiempo de respuesta del servidor.
* **Seguridad Avanzada:** Autenticación con **Laravel Sanctum**, además de manejo centralizado de excepciones mediante **Traits**.
* **Metodología:** Desarrollo basado en **Git Flow**, con procesos de Code Review y gestión ágil de tareas (Scrum/Kanban).

## 🛠️ Despliegue Rápido
El proyecto está totalmente dockerizado y cuenta con scripts de automatización para facilitar el setup inicial, consulta las instrucciones en el [Repositorio monolito🔗](https://github.com/PauMorillas/PFG-DAW-Monolito)
---
**Autor:** Pau Morillas Huerta - Proyecto Final de Grado (DAW)
