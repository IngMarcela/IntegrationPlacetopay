# Video tutorial de configuración y demostración del proyecto

+ https://youtu.be/dlAvd0IXMQw


# IntegrationPlacetopay

+ Aplicación construida con el framework symfony versión 3.4 para simular compras online mediante integración con la pasarela PlaceToPlay (Pago básico)

# Acciones principales de la aplicación
- La aplicación provee servicios web que permiten listar productos, para que de esa manera se pueda generar una orden de compra y ser posteriormente
listada, cada orden de compra se encuentra unida a la pasarela de pagos de placetopay que entregara un estado de compra por cada acción realizada por 
el cliente.  

# Como correr la aplicación 

- Descargar el proyecto y ejecutar la siguiente línea de comando:
```
composer install
```
- En el directorio app\config ubicar los archivos app.yml y config.yml, dentro del config.yml configurar los datos de 
conexión a la base de datos.
- Ejecutar los siguiente comandos para generar la base de datos y a su vez ingresarle los datos de prueba
```
php bin/console doctrine:schema:update --force
```
```
php bin/console doctrine:fixtures:load
```
- Para evitar el error del cors Access-Control-Allow-Origin al trabajar con AJAX, debemos configurar Apache 
para que comparta recursos.
```
<IfModule mod_headers.c>
  Header set Access-Control-Allow-Origin "*"
</IfModule>
```

 Finalmente el app.yml ingresar los respectivos valores:
- IDENTIFICATOR : tu_identificaro
- SECRETKEY : tu_secret_key
- ENDPOINT : el_punto_de_acceso
- RETURNURL : url_base_front

El front desarrollado para este proyecto está en https://github.com/IngMarcela/frontIntegracionPlacetopay

# Links de documentación utilizada

https://symfony.com/doc/3.4/setup.html

https://symfony.com/doc/3.4/logging.html

https://symfony.com/doc/3.4/components/cache.html

https://symfony.com/doc/3.4/testing.html

https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html

https://github.com/dnetix/redirection

https://dev.placetopay.com/web/redirection/
