<template>
    <div v-cloak>
        <Head>
            <title>Datenschutz</title>
        </Head>

        <AuthLayout>
            <template #main>
                <main>
                    <h1 class="headline-1">DATENSCHUTZBESTIMMUNGEN</h1>
                    <h2 class="headline-2">Verantwortlicher und Datenschutzbeauftragter</h2>

                    <h3 class="headline-3">Verantwortlicher</h3>
                    <p>Verantwortlicher gem. Art. 4 Nr. 7 EU-Datenschutz-Grundverordnung (EU-DSGVO) ist</p>
                    <p>{{ settings.general.management_name }} <br> {{ settings.general.address }}</p>
                    <p>Den Datenschutzbeauftragten erreichen Sie per E-Mail unter {{ settings.general.gdpr_email }} oder über die Adresse:</p>

                    <h3 class="headline-3">Datenschutzbeauftragter</h3>
                    <p>{{ settings.general.gdpr_address }}</p>
                    <p>Die Schule unterliegt als öffentliche Stelle den Bestimmungen des Nordrhein-Westfälischen Datenschutzgesetzes. Es ist sichergestellt, dass die Vorschriften über den Datenschutz auch von beteiligten externen Dienstleistern beachtet werden.</p>

                    <h2 class="headline-2">Ihre Rechte als Nutzerinnen und Nutzer</h2>
                    <p>Sie haben nach Maßgabe der Artikel 15, 16, 17, 18 und 21 EU-DSGVO gegenüber uns folgende Rechte hinsichtlich der Sie betreffenden personenbezogenen Daten:</p>
                    <ul>
                        <li>Recht auf Auskunft,</li>
                        <li>Recht auf Berichtigung oder Löschung,</li>
                        <li>Recht auf Einschränkung der Verarbeitung,</li>
                        <li>Recht auf Widerspruch gegen die Verarbeitung,</li>
                        <li>Recht auf Datenübertragbarkeit.</li>
                    </ul>
                    <p>Wenn Sie der Auffassung sind, dass eine Verarbeitung Ihrer personenbezogener Daten gegen Datenschutzrecht verstößt, haben Sie das Recht, sich bei der zuständigen Aufsichtsbehörde zu beschweren, die Sie unter folgenden Kontaktdaten erreichen können:</p>
                    <p>Landesbeauftragte für Datenschutz und Informationsfreiheit Nordrhein-Westfalen <br> Postfach 20 04 44 <br> 40102 Düsseldorf <br> Tel.: 0211/38424-0 <br> Fax: 0211/38424-10 <br> E-Mail: poststelle@ldi.nrw.de</p>

                    <h2 class="headline-2">Hinweise zur Datenverarbeitung</h2>
                    <p>Bereitstellung der Website und Erstellung von Protokoll- oder Log-Dateien:</p>
                    <p>Das Internetangebot der Schule wird bei {{ settings.general.hosting_provider_name }} gehostet.</p>
                    <p>{{ settings.general.hosting_provider_address }}</p>
                    <p>verarbeitet im Auftrag zu diesem Zweck die unten genannten Daten.</p>

                    <h3 class="headline-3">Speicherung</h3>
                    <p>Welche Daten werden verarbeitet?</p>
                    <p>Sofern nicht anders angegeben, werden nachfolgend erläuterte Daten bei gespeichert:</p>

                    <h3 class="headline-3">Log-Dateien</h3>
                    <p>Bei jedem Zugriff auf Inhalte des Internetangebotes werden dort vorübergehend Daten in so genannten Log-Dateien gespeichert, die möglicherweise eine Identifizierung zulassen. Die folgenden Daten werden bei jedem Aufruf der Internetseite erhoben:</p>
                    <ul>
                        <li>Datum und Uhrzeit des Abrufs</li>
                        <li>Name des aufgerufenen Internetdienstes, der aufgerufenen Ressource und der verwendeten Aktion</li>
                        <li>Abfrage, die der Client gestellt hat</li>
                        <li>übertragene Datenmenge</li>
                        <li>Meldung, ob der Abruf erfolgreich war</li>
                        <li>IP-Adresse des aufrufenden Rechners</li>
                        <li>Clientinformationen (u.a. Browser, Betriebssystem)</li>
                    </ul>
                    <p>Diese Daten aus den Protokoll- bzw. Logdateien dienen zur Abwehr und Analyse von Angriffen auf das Portal sowie einer statistischen Auswertung über das Besucheraufkommen (s.u. „Tracking und Statistik“) und werden bis zu 48 Stunden direkt und ausschließlich für Administratoren zugänglich aufbewahrt. Danach sind sie nur noch indirekt über die Rekonstruktion von Sicherungsbändern verfügbar und werden nach vier Wochen endgültig gelöscht.</p>
                    <p>Rechtsgrundlage für die vorübergehende Speicherung der Daten ist Art. 6 Abs. 1 lit. e, Abs. 3 DS-GVO i. V. m. § 3 Abs. 1 Datenschutzgesetz NRW (DSG).</p>

                    <h3 class="headline-3">Personenbezogene Daten</h3>
                    <p>Die von Ihnen eingegebenen personenbezogenen Daten verwenden wir nur zu dem von Ihnen gewünschten Zweck und nur innerhalb der Schulverwaltung der o.g. Schule.</p>
                    <p>Die anfallenden personenbezogenen Daten, werden gelöscht sobald die Speicherung nicht mehr für den von Ihnen verfolgten Zweck oder im Zusammenhang mit damit ausgelösten Verwaltungsvorgängen und den hierfür geltenden Aufbewahrungspflichten erforderlich ist.</p>
                    <p>Rechtsgrundlage für die vorübergehende Speicherung der Daten ist §120 Abs. 1 Schulgesetz NRW (SchulG) und VO-DV I § 2 (1) & (3)</p>

                    <h3 class="headline-3">Datenübertragung</h3>
                    <p>Der WebNotenmanager ist als Teil des Schulverwaltungssystem zu sehen. Somit dürfen personenbezogene Daten nur verschlüsselt und ausschließlich in das zur o.g. Schule gehörende Schulverwaltungssytsem "SVWS-Server" übertragen werden. Rechtsgrundlage für die vorübergehende Speicherung der Daten ist §120 Abs. 1 Schulgesetz NRW (SchulG) und VO-DV I § 2 (1) & (3)</p>

                    <h3 class="headline-3">Copyright</h3>
                    <p>Verwendung und Weitergabe von Kopien in elektronischer oder ausgedruckter Form sind außerhalb des durch die Schulvorschriften geregelten Rahmens nicht erlaubt.</p>
                </main>
            </template>
        </AuthLayout>
    </div>
</template>


<script setup lang="ts">
    import AuthLayout from '../Layouts/AuthLayout.vue';
    import { Head } from '@inertiajs/inertia-vue3';
    import { Settings } from '@/Interfaces/Interface';
    import { PropType } from 'vue';

    let props = defineProps({
        settings: {
            type: Object as PropType<Settings>,
            required: true,
        },
    });
</script>


<style scoped>

    main {
        @apply bg-white rounded-lg shadow-lg p-8 flex flex-col gap-6 w-full max-w-6xl mx-auto my-8 overflow-y-auto
    }

    main div {
        @apply flex flex-col gap-6 overflow-y-auto pr-8
    }

    h1 {
        @apply uppercase
    }

    h1, h2 {
        @apply border-b border-gray-300 pb-2 leading-relaxed
    }

    p {
        @apply text-lg leading-7
    }

    ul {
        @apply list-disc list-outside ml-6 text-lg
    }

</style>
