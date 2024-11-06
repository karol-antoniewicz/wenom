<template>
    <div v-cloak>
        <Head>
            <title>Impressum</title>
        </Head>

        <AuthLayout>
            <template #main>
                <main>
                    <h1 class="headline-1">Impressum</h1>
                    <p>{{ settings.general.name }} <br> {{ settings.general.address }} <br> {{ settings.general.email }}</p>

                    <h2 class="headline-2">Schulleitung</h2>
                    <p>{{ settings.general.management_name }} <br> Telefon-Nr.: {{ settings.general.management_telephone }} <br> E-Mail: {{ settings.general.management_email }}</p>
                    <p>Die {{ settings.general.name }} ist eine Körperschaft des Öffentlichen Rechts. Sie wird durch den Schulleiter gesetzlich vertreten.</p>

                    <h2 class="headline-2">Schulträger</h2>
                    <h3 class="headline-3">Diensteanbieter im Sinne des TMG</h3>
                    <p>{{ settings.general.board_name }} <br> {{ settings.general.board_address }} <br> {{ settings.general.board_contact }}</p>

                    <h2 class="headline-2">Urheberrecht</h2>
                    <p>Texte, Bilder, Grafiken und Fotos sowie die Gestaltung dieser Internetseiten unterliegen dem Urheberrecht. Sie dürfen von Ihnen nur zum privaten und sonstigen eigenen Gebrauch im Rahmen des § 53 Urheberrechtsgesetz (UrhG) ohne jede Änderung vervielfältigt werden. Eine Verbreitung von Kopien (analog oder digital) oder Auszügen ist nur mit schriftlicher Genehmigung zulässig. Texte, Bilder, Grafiken und sonstige Materialien können ganz oder teilweise dem Urheberrecht Dritter unterliegen. Die Inhalte Dritter sind als solche kenntlich gemacht und dürfen ohne Einwilligung des Rechteinhabers nicht vervielfältigt, öffentlich zugänglich gemacht oder öffentlich wiedergegeben werden.</p>

                    <h2 class="headline-2">Haftung für Inhalte</h2>
                    <p>Die Informationen, die Sie auf diesem Internetauftritt vorfinden, wurden nach bestem Wissen und Gewissen sorgfältig zusammengestellt und geprüft. Für die Richtigkeit, Vollständigkeit, Aktualität oder Qualität und jederzeitige Verfügbarkeit der bereit gestellten Informationen wird jedoch keine Gewähr übernommen. Diensteanbieter sind gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Diensteanbieter sind jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt.</p>
                    <p>Dieser Internetauftritt enthält auch Links oder Verweise auf Internetauftritte Dritter. Diese Links zu den Internetauftritten Dritter stellen keine Zustimmung zu deren Inhalten durch den Herausgeber dar. Für die Inhalte der externen Links sind die jeweiligen Anbieter oder Betreiber (Urheber) der Seiten verantwortlich. Mit den Links zu anderen Internetauftritten wird den Nutzern lediglich der Zugang zur Nutzung der Inhalte vermittelt. Die externen Links wurden zum Zeitpunkt der Linksetzung nach bestem Wissen und Gewissen auf eventuelle Rechtsverstöße überprüft. Eine ständige inhaltliche Überprüfung der externen Links ist ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht möglich. Bei Verlinkungen auf die Webseiten Dritter, die außerhalb des Verantwortungsbereichs des Herausgebers liegen, würde eine Haftungsverpflichtung nur bestehen, wenn der Herausgeber von den rechtswidrigen Inhalten Kenntnis erlangen und es technisch möglich und zumutbar wäre, die Nutzung dieser Inhalte zu verhindern. Sollten Sie der Ansicht sein, dass die verlinkten externen Seiten gegen geltendes Recht verstoßen oder sonst unangemessene Inhalte haben, so teilen Sie uns dies bitte mit.</p>
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

    h1, h2 {
        @apply border-b border-gray-300 pb-2 leading-relaxed
    }

    main p {
        @apply text-lg leading-7
    }
</style>
