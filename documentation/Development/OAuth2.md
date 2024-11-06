# Webservices

## OAuth 2

### Setup
`php artisan passport:install` - Diese Funktion generiert Verschlüsselungsschlüssel und erstellt "Personal Access" und "Password Grant" OAuth-Clients für Laravel Passport.

`php artisan passport:client` - Diese Funktion erstellt einen neuen OAuth2-Client für Laravel Passport. Dabei werden 3 Fragen gestellt:

|English|Deutsch|Optional|Default|
|---|---|---|---|
|Which user ID should the client be assigned to?|Welcher Benutzer-ID soll der Client zugeordnet werden?|Ja|null|
|What should we name the client?|Wie soll der Client benannt werden?|Nein||
|Where should we redirect the request after authorization?|Wohin soll die Anfrage nach der Autorisierung weitergeleitet werden?|Ja|http://localhost/auth/callback|


Bei Erfolg bekommt man die CLIENT_ID und CLIENT_SECRET.
```bash
New client created successfully.
Client ID: CLIENT_ID
Client secret: CLIENT_SECRET
```

Diese werden beim Einloggen vom SWVS-Server auf den WeNoM-Server im nächsten Kapitel verpflichtend.

---
### Login

```bash
curl -X POST http://localhost/oauth/token \
     -H 'Content-Type: application/x-www-form-urlencoded' \
     -u CLIENT_ID:CLIENT_SECRET \
     -d 'grant_type=client_credentials'
```
|Type|Code|Response|
|---|---|---|
|Success|200|`{"token_type":"Bearer","expires_in":EXPIRATION_DATE,"access_token":"ACCESS_TOKEN"}`|
|Error|401|`{"error":"invalid_client","error_description":"Client authentication failed","message":"Client authentication failed"}`|

Das `ACCESS_TOKEN` muss gespeichert werden und bei jede zukunftige Abfrage mitgelifert werden.

---

### Test Route
```bash
curl -X GET http://localhost/api/secure/check \
     -H 'Authorization: Bearer YOUR_ACCESS_TOKEN' \
     -H 'Accept: application/json'
```

|Type|Code|Response|
|---|---|---|
|Success|200|`{"message":"Erfolg."}`|
|Error|401|`{"message":"Unauthenticated."}`|


---
## Import
```bash
curl -X POST http://localhost/api/secure/import \
     -H 'Authorization: Bearer YOUR_ACCESS_TOKEN' \
     -H 'Accept: application/json' \
     -F 'file=@enm.json.gz'
```

|Type|Code|Response|
|---|---|---|
|Success|200||
|Error|401|`{"message":"Unauthenticated."}`|
|Error|400|`{"message":"Ein Fehler ist beim JSON-Dekodierung aufgetreten."}`|
|Error|400|`{"message":"Schulnummer nicht gültig."}`|
|Error|426|`{"message":"Die Revisionsnummern der Synchronisation stimmt nicht mit der des SVWS-Servers überein. Die Sychronisation wird abgebrochen.."}`|
---

## Export
```bash
curl -X GET http://localhost/api/secure/export \
     -H 'Authorization: Bearer YOUR_ACCESS_TOKEN' \
     -H 'Accept: application/json' \
     --output ~/export.gz
```

|Type|Code|Response|
|---|---|---|
|Success|200|`Binary output`|
|Error|401|`{"message":"Unauthenticated."}`|
|Error|500|`{"message":"Ein Fehler ist beim Komprimieren der Daten aufgetreten"}`|
---

## Truncate
Alle Tabellen ausser `migrations, users, oauth_clients, settings` werden geleert. Aus die Tabelle `users` werden alle Benutzer entfernt die haben die `administrator` Flag gesetzt auf `false`.

```bash
curl -X POST http://localhost/api/secure/truncate \
     -H 'Authorization: Bearer YOUR_ACCESS_TOKEN' \
     -H 'Accept: application/json'
```

|Type|Code|Response|
|---|---|---|
|Success|200|`{"message": {"tables": {"kept": 4,"kept_tables": [ 'migrations', 'users', 'oauth_clients', 'settings', 'oauth_access_tokens', '2fa_auth_codes', 'oauth_auth_codes', 'oauth_personal_access_clients', 'oauth_refresh_tokens', 'password_resets', 'personal_access_tokens', 'sessions', 'user_login', 'user_settings', 'users', ],"truncated": 31}}}`|
|Error|401|`{"message":"Unauthenticated."}`|

### Response
- `tables`
     - `.kept` - Anzahl Tabellen die nicht geleert worden sind.
     - `.kept_tables` - Liste von Tabellen die nicht geleert worden sind.
     - `.truncated` - Anzahl Tabellen die geleert worden sind.
