# Ganchudo Code-Review
Esta librería sirve para unificar las tareas de revisión de código de tu proyecto, facilitando la integración con hooks de tu sistema de control de versiones.
La librería ejecutará este análisis por etapas, imprimiendo en pantalla todas las etapas que hayan fallado, y devolviendo una señal positiva (0) si todas las etapas han ejecutado correctamente, o negativa (1) si alguna de las etapas tuvo problemas.
Cada etapa corresponde a la ejecución de un comando de consola, de tu herramienta de análisis preferida. Se recomienda usar ficheros de configuración, si tu herramienta los tuviera, para reducir al mínimo el comando de consola a ejecutar.


## Configuración
Para configurar ganchudo, crea en cualquier carpeta de tu proyecto (preferiblemente en la raíz), un fichero en formato YAML con el formato que se detallará a continuación.
Este fichero se recomienda llamarlo ```ganchudo.yml.dist``` y situarlo en la raíz, por homogeneidad con otras herramientas de la misma naturaleza.

Ejemplo:
```yaml
ganchudo:
    inspectors:
        -   name: 'Composer Validation'
            command: 'composer.phar validate --strict'
        -   name: 'Php Linter'
            command: 'php -l <iterator>'
            iterator:
                in: ['src', 'tests']
                exclude: []
                file: '*.php'
        -   name: 'Php Code Sniffer'
            command: 'vendor/bin/phpcs --standard=phpcs.xml.dist'
        -   name: 'PhpUnit'
            command: 'vendor/bin/phpunit --configuration phpunit.xml.dist --no-coverage --testdox --colors=always'
            timeout: 3600
```
## Formato
Ganchudo distingue dos tipos de inspectores: inspectores simples, e inspectores mediante iteradores. El directorio raíz que se usará para ejecutar los comandos es el directorio donde está el fichero de configuración indicado.
### Inspector Simple
Como su nombre indica, es el tipo de etapa es la mas simple. Consiste en ejecutar un único comando de consola, al que identificaremos con un nombre.
- ```name```: Obligatorio. String con el nombre de la etapa.
- ```command```: Obligatorio. String con el comando de consola a ejecutar
- ```timeout```: Opcional. Integer positivo con timeout en segundos del proceso a ejecutar.

### Inspector Iterador
Es una etapa un poco mas complejo que la anterior. Determinadas herramientas requieren ser ejecutadas directamente sobre cada fichero del proyecto, como por ejemplo un ```PHP -l```.
Para cubrir esta necesidad, la etapa se define como la anterior, pero con algunas diferencias:
- ```name```: Obligatorio. String con el nombre de la etapa.
- ```command```: Obligatorio. String con el comando de consola a ejecutar, pero insertando en el lugar correcto la cadena ```<iterator>```, que será sustituída posteriormente por cada uno de los ficheros que produzcan coincidencia, con las opciones que vamos a detallar a continuación.
- ```iterator```: Array con las siguientes opciones:
  - ```in```: Obligatorio. Array de directorios fuente.
  - ```file```: Obligatorio. Patrón de coincidencia con el nombre del fichero. Acepta el patrón glob, y patrones regulares, relativos a ```in```.
  - ```exclude```: Opcional. Array de directorios a excluir, relativos a los indicados en ```in```.

## Ejecución
Para ejecutar ganchudo, crea un fichero de configuración de ganchudo, y escribe en la terminal, en la carpeta raíz de tu proyecto:
```
    $> vendor/bin/ganchudo ganchudo.yml.dist
```

Si todo va bien, el proceso terminará con la señal 0. Si alguna etapa falla, terminará con 1.

## Integración con los hooks de GIT
Si se quiere ejecutar esta revisión de código como pre-requisito de aceptar un commit de git, bastaría com crear un fichero llamado ```pre-commit``` en la carpeta ```.git\hooks``` de tu proyecto, con el siguiente contenido:
```
#!/usr/bin/bash
SCRIPT=$(../../vendor/bin/ganchudo ../../ganchudo.yml.dist)
STATUS=$?
echo "$SCRIPT"
exit $STATUS
```

Si tienes un entorno de trabajo bajo docker-compose, el script tendría un aspecto como:
```
#!/usr/bin/bash
SCRIPT=$(docker-compose -f "<docker-compose-yml-file>" run --rm <service-name> sh -c "vendor/bin/ganchudo ganchudo.yml.dist" 2>&1)
STATUS=$?
echo "$SCRIPT"
exit $STATUS
```