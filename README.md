# examen_php_73024201
Este es un proyecto hecho en laravel. Para realizar la clonación de este proyecto es lo siguiente realizar la clonación.

Teniendo el proyeco clonado se tiene que ingresar por me dio de la consola o Terminal a la carpeta de la clonacion y ejecutar los siguientes comando:

npm install
npm install composer

luego de terminar con la instalación podemos configurar la conexion a la base de datos, en el archivo env.exmaple, este archivo tiene que se duplicado pero el nombre debe ser '.env'. Y en el archivo cambiar los siguiente:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

para el poryecto esta es la configurción, sientate libre de modificar a su necesidad.

Ahora que se tiene completa las configuraciones iniciales, hacemos correr las migraciones que proporcionaran la estrcutura de la base de datos pero no olvidarse de crear antes la base de datos con el nombre que esta en la configuraciony, el comando para la migracion es:

php artisan migrate

Ahora los seeders proporcionaran los datos iniciales de la aplicación, esto se realiza con la siguiete comando:

php artisan db:seed

listo con todas las ordenes realizadas ahora podemos hacer correr el proyecto con el comando:

php artisan serve

Listo, ahora solo queda hacer la prueba de los endpoint's.

