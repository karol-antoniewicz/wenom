const Ziggy = {"url":"http:\/\/webnotenmanager.test","port":null,"defaults":{},"routes":{"debugbar.openhandler":{"uri":"_debugbar\/open","methods":["GET","HEAD"]},"debugbar.clockwork":{"uri":"_debugbar\/clockwork\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"debugbar.assets.css":{"uri":"_debugbar\/assets\/stylesheets","methods":["GET","HEAD"]},"debugbar.assets.js":{"uri":"_debugbar\/assets\/javascript","methods":["GET","HEAD"]},"debugbar.cache.delete":{"uri":"_debugbar\/cache\/{key}\/{tags?}","methods":["DELETE"],"parameters":["key","tags"]},"login":{"uri":"login","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]},"user-password.update":{"uri":"user\/password","methods":["PUT"]},"password.confirmation":{"uri":"user\/confirmed-password-status","methods":["GET","HEAD"]},"password.confirm":{"uri":"user\/confirm-password","methods":["POST"]},"profile.show":{"uri":"user\/profile","methods":["GET","HEAD"]},"other-browser-sessions.destroy":{"uri":"user\/other-browser-sessions","methods":["DELETE"]},"current-user-photo.destroy":{"uri":"user\/profile-photo","methods":["DELETE"]},"passport.token":{"uri":"oauth\/token","methods":["POST"]},"passport.authorizations.authorize":{"uri":"oauth\/authorize","methods":["GET","HEAD"]},"passport.token.refresh":{"uri":"oauth\/token\/refresh","methods":["POST"]},"passport.authorizations.approve":{"uri":"oauth\/authorize","methods":["POST"]},"passport.authorizations.deny":{"uri":"oauth\/authorize","methods":["DELETE"]},"passport.tokens.index":{"uri":"oauth\/tokens","methods":["GET","HEAD"]},"passport.tokens.destroy":{"uri":"oauth\/tokens\/{token_id}","methods":["DELETE"],"parameters":["token_id"]},"passport.clients.index":{"uri":"oauth\/clients","methods":["GET","HEAD"]},"passport.clients.store":{"uri":"oauth\/clients","methods":["POST"]},"passport.clients.update":{"uri":"oauth\/clients\/{client_id}","methods":["PUT"],"parameters":["client_id"]},"passport.clients.destroy":{"uri":"oauth\/clients\/{client_id}","methods":["DELETE"],"parameters":["client_id"]},"passport.scopes.index":{"uri":"oauth\/scopes","methods":["GET","HEAD"]},"passport.personal.tokens.index":{"uri":"oauth\/personal-access-tokens","methods":["GET","HEAD"]},"passport.personal.tokens.store":{"uri":"oauth\/personal-access-tokens","methods":["POST"]},"passport.personal.tokens.destroy":{"uri":"oauth\/personal-access-tokens\/{token_id}","methods":["DELETE"],"parameters":["token_id"]},"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"secure.check":{"uri":"api\/secure\/check","methods":["GET","HEAD"]},"secure.export":{"uri":"api\/secure\/export","methods":["GET","HEAD"]},"secure.import":{"uri":"api\/secure\/import","methods":["POST"]},"secure.truncate":{"uri":"api\/secure\/truncate","methods":["POST"]},"teilleistungen.index":{"uri":"api\/teilleistungen\/index\/{reset}","methods":["GET","HEAD"],"parameters":["reset"]},"teilleistungen.get_kurs":{"uri":"api\/teilleistungen\/kurs\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"teilleistungen.get_klasse":{"uri":"api\/teilleistungen\/klasse\/{klasse}","methods":["GET","HEAD"],"parameters":["klasse"]},"teilleistungen.update_note":{"uri":"api\/teilleistungen\/update-note\/{teilleistung}\/{note}","methods":["PUT"],"parameters":["teilleistung","note"],"bindings":{"teilleistung":"id"}},"api.matrix.index":{"uri":"api\/matrix\/index","methods":["GET","HEAD"]},"api.matrix.update":{"uri":"api\/matrix\/update","methods":["PUT"]},"passport.index":{"uri":"api\/settings\/passport","methods":["GET","HEAD"]},"passport.store":{"uri":"api\/settings\/passport","methods":["POST"]},"settings.mail_test":{"uri":"api\/settings\/send-testmail","methods":["POST"]},"api.settings.matrix.index":{"uri":"api\/settings\/matrix\/index","methods":["GET","HEAD"]},"api.settings.matrix.update":{"uri":"api\/settings\/matrix\/update","methods":["PUT"]},"api.settings.index":{"uri":"api\/settings\/index\/{group}","methods":["GET","HEAD"],"parameters":["group"]},"api.settings.update":{"uri":"api\/settings\/update\/{group}","methods":["PUT"],"parameters":["group"]},"api.settings.bulk_update":{"uri":"api\/settings\/bulk-update\/{group}","methods":["PUT"],"parameters":["group"]},"api.settings.two_factor_authentication":{"uri":"api\/settings\/two-factor-authentication","methods":["PUT"]},"api.settings.mail_send_credentials":{"uri":"api\/settings\/mail-send-credentials","methods":["PUT"]},"api.settings.filters":{"uri":"api\/settings\/filters","methods":["GET","HEAD"]},"api.fehlstunden.fs":{"uri":"api\/fehlstunden.\/fs\/{leistung}","methods":["POST"],"parameters":["leistung"],"bindings":{"leistung":"id"}},"api.fehlstunden.fsu":{"uri":"api\/fehlstunden.\/fsu\/{leistung}","methods":["POST"],"parameters":["leistung"],"bindings":{"leistung":"id"}},"api.fehlstunden.gfs":{"uri":"api\/fehlstunden.\/gfs\/{schueler}","methods":["POST"],"parameters":["schueler"],"bindings":{"schueler":"id"}},"api.fehlstunden.gfsu":{"uri":"api\/fehlstunden.\/gfsu\/{schueler}","methods":["POST"],"parameters":["schueler"],"bindings":{"schueler":"id"}},"user_settings.set_filters":{"uri":"api\/benutzereinstellungen\/filters","methods":["POST"]},"user_settings.get_all_filters":{"uri":"api\/benutzereinstellungen\/filters","methods":["GET","HEAD"]},"user_settings.get_filters":{"uri":"api\/benutzereinstellungen\/filters\/{group}","methods":["GET","HEAD"],"parameters":["group"]},"user_settings.get_last_login":{"uri":"api\/benutzereinstellungen\/user-data","methods":["GET","HEAD"]},"user_settings.get_settings":{"uri":"api\/benutzereinstellungen\/get-settings","methods":["POST"]},"user_settings.set_settings":{"uri":"api\/benutzereinstellungen\/set-settings","methods":["POST"]},"api.fachbezogene_bemerkung":{"uri":"api\/fachbezogene-bemerkung\/{leistung}","methods":["POST"],"parameters":["leistung"],"bindings":{"leistung":"id"}},"api.mahnung":{"uri":"api\/mahnung\/{leistung}","methods":["POST"],"parameters":["leistung"],"bindings":{"leistung":"id"}},"api.noten":{"uri":"api\/noten\/{leistung}\/{type?}","methods":["POST"],"parameters":["leistung","type"],"bindings":{"leistung":"id"}},"api.schueler_bemerkung":{"uri":"api\/schueler-bemerkung\/{schueler}","methods":["POST"],"parameters":["schueler"],"bindings":{"schueler":"id"}},"api.floskeln":{"uri":"api\/floskeln\/{floskelgruppe}","methods":["GET","HEAD"],"parameters":["floskelgruppe"]},"api.fachbezogene_floskeln":{"uri":"api\/fachbezogene-floskeln\/{fach}","methods":["GET","HEAD"],"parameters":["fach"],"bindings":{"fach":"id"}},"api.leistungsdatenuebersicht":{"uri":"api\/leistungsdatenuebersicht","methods":["GET","HEAD"]},"api.mein_unterricht":{"uri":"api\/mein-unterricht","methods":["GET","HEAD"]},"api.klassenleitung":{"uri":"api\/klassenleitung","methods":["GET","HEAD"]},"settings.school":{"uri":"einstellungen\/schule","methods":["GET","HEAD"]},"settings.filter":{"uri":"einstellungen\/filter","methods":["GET","HEAD"]},"settings.matrix":{"uri":"einstellungen\/matrix","methods":["GET","HEAD"]},"settings.sicherheit":{"uri":"einstellungen\/sicherheit","methods":["GET","HEAD"]},"settings.2fa":{"uri":"einstellungen\/2fa","methods":["GET","HEAD"]},"settings.synchronisation":{"uri":"einstellungen\/synchronisation","methods":["GET","HEAD"]},"otp":{"uri":"two-factor\/otp","methods":["GET","HEAD"]},"otp.verify":{"uri":"two-factor\/otp\/verify","methods":["POST"]},"mein_unterricht":{"uri":"\/","methods":["GET","HEAD"]},"teilleistungen":{"uri":"teilleistungen","methods":["GET","HEAD"]},"leistungsdatenuebersicht":{"uri":"leistungsdatenuebersicht","methods":["GET","HEAD"]},"klassenleitung":{"uri":"klassenleitung","methods":["GET","HEAD"]},"user_settings.filter":{"uri":"benutzereinstellungen\/filter","methods":["GET","HEAD"]},"user_settings.security":{"uri":"benutzereinstellungen\/security","methods":["GET","HEAD"]},"request_password.index":{"uri":"passwort-anfordern","methods":["GET","HEAD"]},"request_password.execute":{"uri":"passwort-anfordern","methods":["POST"]},"request_password.reset_form":{"uri":"passwort-aendern\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"request_password.update":{"uri":"passwort-aendern","methods":["POST"]},"impressum":{"uri":"impressum","methods":["GET","HEAD"]},"datenschutz":{"uri":"datenschutz","methods":["GET","HEAD"]},"barrierefreiheit":{"uri":"barrierefreiheit","methods":["GET","HEAD"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };