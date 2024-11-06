# Entwicklungsumgebung

## Visual Sudio Code einrichten

### Vorbereitungen:

+ Visual Studio Code installieren
+ MariaDB installieren und eine neue Datenbank (z.B. wenom) anlegen
+ php (Version 8.2) installieren und in den Umgebungsvariablen „Path“ anpassen
+ In der php.ini die Erweiterungen curl, sodium, fileinfo und pdo_mysql auskommentiert
+ Composer installieren (aktuellste Version)
+Node.js (mit npm) installieren (Version 20) und in den Umgebungsvariablen „Path“ setzen


### WeNoM in Visual Studio Code einrichten und ausführen:

+ WeNoM Quellen herunterladen
+ Visual Studio Code öffnen und den WeNoM Ordner öffnen (File > Open Folder)
+ Die Datei .env.example kopieren und in .env umbenennen und die Datenbankparameter bearbeiten.Zum Beispiel: 

```bash 
DB_CONNECTION=mysql 
DB_HOST=localhost 
DB_PORT=3306 
DB_DATABASE=wenom 
DB_USERNAME=root 
DB_PASSWORD= passwort
```

+ VSC-Terminal öffnen (Terminal > New Terminal) und folgende Befehle ausführen:

```bash 
composer install 
php artisan key:generate
php artisan migrate:fresh –seed
npm install
npm run build
```

### WeNoM starten und im Browser aufrufen

+ Im Terminal `php artisan serve` ausführen
+ Im Browser http://127.0.0.1:8000 aufrufen