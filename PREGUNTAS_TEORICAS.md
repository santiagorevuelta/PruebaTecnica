# Preguntas Teoricas

---

## 1. Diferencia entre hasMany() y belongsToMany() en Eloquent

**hasMany** representa una relacion uno a muchos. Un modelo padre tiene varios registros de otro modelo, y ese modelo hijo guarda la clave foranea del padre en su propia tabla. Por ejemplo, un usuario tiene muchos prestamos: la tabla prestamos guarda el id del usuario.

**belongsToMany** representa una relacion muchos a muchos. Ninguno de los dos modelos guarda la clave del otro directamente. En cambio, existe una tercera tabla llamada tabla pivot que almacena los IDs de ambos lados. Por ejemplo, un autor puede escribir muchos libros y un libro puede tener muchos autores: la tabla autores_libros almacena las combinaciones.

La diferencia clave es estructural: hasMany necesita una clave foranea en la tabla del hijo, mientras que belongsToMany necesita una tabla intermedia. Cuando la relacion puede existir en ambas direcciones al mismo tiempo, se usa belongsToMany.

---

## 2. Diferencia entre all(), get() y find() en Eloquent

**all()** trae todos los registros de una tabla sin ninguna condicion. No permite encadenar filtros ni ordenamiento antes de ejecutarse. Es util solo cuando se necesitan absolutamente todos los datos de una tabla pequena.

**get()** ejecuta la consulta que se haya construido previamente con el query builder. Permite agregar condiciones, ordenamiento, limites y joins antes de llamarlo. Es el metodo mas versatil y el que se usa en la mayoria de los casos reales porque siempre se necesita filtrar o limitar los resultados.

**find()** busca un unico registro por su clave primaria. Devuelve el modelo si lo encuentra o null si no existe. Se usa cuando ya se conoce el ID exacto del registro que se necesita.

En resumen: all() para traer todo sin filtros, get() para traer un subconjunto con condiciones, find() para traer un registro puntual por ID.

---

## 3. Patron Repository en Laravel

El patron Repository es una capa que separa la logica de acceso a datos del resto de la aplicacion. En lugar de que los controladores escriban consultas Eloquent directamente, delegan esa responsabilidad a clases Repository especializadas.

Los controladores solo conocen la interfaz del repositorio y llaman a metodos con nombres de negocio como `buscarDisponibles()` o `paginarConFiltros()`. La implementacion concreta puede cambiar sin afectar al controlador.

**Cuando implementarlo:**
- La aplicacion es grande y varias partes del codigo repiten las mismas consultas.
- Se quiere testear la logica de negocio sin depender de la base de datos, usando un repositorio falso (mock).
- Se prevee que el origen de datos puede cambiar en el futuro.

**Cuando no vale la pena:**
- Proyectos pequenos o medianos donde Eloquent directamente en el controlador es suficiente y claro.
- Agrega archivos y capas de indirection que pueden dificultar la lectura sin un beneficio real.

---

## 4. Problema N+1 en Eloquent y como solucionarlo

El problema N+1 ocurre cuando se hace una consulta para obtener una lista de registros y luego el sistema ejecuta una consulta adicional por cada elemento de esa lista para cargar una relacion. Si se obtienen 20 libros y se accede a los autores de cada uno dentro de un loop, se ejecutan 21 consultas en total: 1 para los libros y 20 para los autores.

Esto degrada el rendimiento de forma silenciosa porque el codigo parece correcto pero genera una carga excesiva en la base de datos.

La solucion es el Eager Loading usando el metodo `with()` al momento de hacer la consulta principal. Esto le indica a Laravel que cargue las relaciones en una segunda consulta unica, no una por registro. El resultado final es solo 2 consultas independientemente de cuantos registros haya.

La regla practica es: si se va a iterar sobre una coleccion y acceder a una relacion dentro del loop, siempre agregar `with('nombre_relacion')` en la consulta original.

---

## 5. Diferencia entre autenticacion y autorizacion en APIs

Son conceptos distintos que trabajan en orden secuencial.

**Autenticacion** verifica la identidad: confirma que el usuario es quien dice ser. Es el proceso de login. En este proyecto se implementa con Sanctum: el cliente envia sus credenciales, el servidor verifica que sean correctas y devuelve un token que identifica al usuario en las solicitudes futuras.

**Autorizacion** verifica los permisos: una vez que se sabe quien es el usuario, determina que acciones puede realizar y a que recursos puede acceder. No todos los usuarios autenticados tienen los mismos permisos. Un usuario normal puede ver libros pero no puede eliminarlos; un administrador si puede.

La autenticacion siempre ocurre primero. Si un usuario no esta autenticado, no tiene sentido verificar sus permisos. Si esta autenticado pero no tiene permiso para una accion, la API responde con 403 Forbidden, no 401 Unauthorized.

---

## 6. Diferencia entre PATCH y PUT en APIs REST

Ambos metodos actualizan un recurso existente pero con alcances diferentes.

**PUT** reemplaza el recurso completo. El cliente debe enviar todos los campos del recurso en el cuerpo de la solicitud. Si omite un campo, el servidor puede interpretar que ese campo debe borrarse o quedar vacio. Se asume que el cliente tiene la representacion completa y actualizada del recurso.

**PATCH** actualiza parcialmente el recurso. El cliente envia solo los campos que quiere modificar y el resto permanece sin cambios. Es mas eficiente cuando solo se necesita actualizar uno o dos campos de un objeto grande.

En la practica, PATCH es mas comun en APIs modernas porque permite actualizaciones quirurgicas sin necesidad de conocer y reenviar el estado completo del recurso. PUT se usa cuando el flujo de trabajo garantiza que el cliente siempre trabaja con la version completa del objeto.
