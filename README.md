# SportHub-Cordoba---TFG
Proyecto Final Ciclo Grado Superior DAW, página web de ayuda informativa y organizativa para los atletas residentes en la provincia de Córdoba

---

## 🐳 Docker

El proyecto está dockerizado con tres servicios: Apache (PHP 8.1), MySQL 5.7 y phpMyAdmin.

### Requisitos
- [Docker](https://www.docker.com/) y [Docker Compose](https://docs.docker.com/compose/) instalados

### Levantar el proyecto

```bash
docker compose up --build
```

### Puertos

| Servicio    | URL                                      |
|-------------|------------------------------------------|
| Aplicación  | http://localhost/proyectoCGS/            |
| phpMyAdmin  | http://localhost:8080                    |
| MySQL       | `localhost:3306` (interno, servicio `mysql`) |

### Parar los contenedores

```bash
docker compose down
```

> Para eliminar también los datos de la base de datos: `docker compose down -v`
